<?php
namespace Giohang\Model;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
class GiohangTable{
    
    public function listAll($dbAdapter,$ds_masp)
    {
        $sql=new Sql($dbAdapter);
        $select = $sql->select();
        $select->from('product');
        $select->columns(array('id','product_id', 'product_name', 'price', 'image'));  
        $select->where("id in($ds_masp)");        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        return $results;
    }
    public function listAll_1($ds_masp)
    {
        $reader = new \Zend\Config\Reader\Ini();
        $dbconfig = $reader->fromFile('./public/config.ini');
        $db=array(
        'driver'    =>$dbconfig['driver'],
        'dsn'       =>$dbconfig['dsn'],
        'username'  =>$dbconfig['username'],
        'password'  =>$dbconfig['password'],
        'driver_options'    =>array(\PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES "UTF8"'),);
       
        $dbAdapter = new \Zend\Db\Adapter\Adapter($db);
        $sql=new Sql($dbAdapter);
        $select = $sql->select();
        $select->from('sanpham');
        $select->columns(array('ma_sp', 'ten_sp', 'don_gia', 'hinh'));  
        $select->where("ma_sp in($ds_masp)");        
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $sanpham=array();
        for($i=0;$i<$results->count();$i++)
        {
           $kq=$results->current();
           $sanpham[]=$kq;
           $results->next();
        }
        return $sanpham;
    }        
}
?>