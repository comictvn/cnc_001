<?php
namespace Block\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
//use Zend\Db\Sql\Update;

class Menu extends AbstractTableGateway{
    
    protected $tableGateway;
    protected $table    ='subject';
   // protected $table1    ='user';


    public function __construct(Adapter $adapter) {
        
        $this->adapter = $adapter;
    }
    public function getnew($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('news');
    	$select->limit(3);
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	
    	return $results;
    }
    
    public function getproductnew($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('product');
    	$select->where(array('pronew'=>1));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$loai_sp = array();
    	for($i = 0; $i<$results->count(); $i++)
    	{
	    	$kp = $results->current();
	    	$loai_sp[]=$kp;
	    	$results->next();
    	}
    	return $loai_sp;
    }
    
    public function getcateservice($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('catenew');
    	$select->where(array('active'=>1,'parent'=>2));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$loai_sp = array();
    	for($i = 0; $i<$results->count(); $i++)
    	{
    	$kp = $results->current();
    	$loai_sp[]=$kp;
    	$results->next();
    	}
    	return $loai_sp;
    }

    public function getcatetintuc($dbAdapter)
    {
        $sql = new Sql($dbAdapter);
        $select = $sql->select();
        $select->from('catenew');
        $select->where(array('active'=>1,'parent'=>1));
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $loai_sp = array();
        for($i = 0; $i<$results->count(); $i++)
        {
        $kp = $results->current();
        $loai_sp[]=$kp;
        $results->next();
        }
        return $loai_sp;
    }
    
    public function getgioithieu($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('news');
    	$select->where(array('active'=>1,'category'=>5));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$loai_sp = array();
    	for($i = 0; $i<$results->count(); $i++)
    	{
    	$kp = $results->current();
    	$loai_sp[]=$kp;
    	$results->next();
    	}
    	return $loai_sp;
    }
    
    
    public function getproductnew_value($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('block_productnew');
    	$select->where(array('idblock'=>4));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	return $results;
    }
    
    // THÔNG TIN CƠ BẢN WEBSITE
    public function getintro($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('intro');
    	$select->where(array('id'=>1));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$product=array();
		for($i=0;$i<$results->count();$i++)
		{
			$kq=$results->current();
			$product[]=$kq;
			$results->next();
		}
		return $product;
    }
    
    // TỐI ƯU HÓA WEBSITE
    public function getseopage($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('seopage');
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$product=array();
    	for($i=0;$i<$results->count();$i++)
    	{
    	$kq=$results->current();
    	$product[]=$kq;
    	$results->next();
    	}
    	return $product;
    }
    
    // THÔNG TIN CƠ BẢN WEBSITE
    public function getcatepro($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('procate');
    	$select->where(array('active'=>1));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$product=array();
    	for($i=0;$i<$results->count();$i++)
    	{
    	$kq=$results->current();
    	$product[]=$kq;
    	$results->next();
    	}
    	return $product;
    }
    
    // SLIDE 
    
    public function getslide($dbAdapter, $idblock)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('blockcontentvalue');
    	$select->join('blockcontent','blockcontent.id=blockcontentvalue.idblockcontent',array('name'),$select::JOIN_INNER);
    	$select->join('block','block.id=blockcontentvalue.idblock',array('name'),$select::JOIN_INNER);
    	$select->where(array('block.id'=>$idblock,'blockcontentvalue.parent'=>0));
    	$select->order('blockcontentvalue.parent');
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$loai_sp = array();
    	for($i = 0; $i<$results->count(); $i++)
    	{
    	$kp = $results->current();
    	$loai_sp[]= $kp;
    	$results->next();
    	}
    	return $loai_sp;
    }
    
    public function getslidevalue($dbAdapter, $idblock, $parent)
    {
    $sql = new Sql($dbAdapter);
    $select = $sql->select();
    $select->from('blockcontentvalue');
		$select->join('blockcontent','blockcontent.id=blockcontentvalue.idblockcontent',array('name','meta'),$select::JOIN_INNER);
    		$select->join('block','block.id=blockcontentvalue.idblock',array('name'),$select::JOIN_INNER);
    		$select->where("block.id = $idblock and blockcontent.meta != 'title_banner' and blockcontent.meta != 'content_banner' and blockcontentvalue.parent = $parent ");
    
    		$statement = $sql->prepareStatementForSqlObject($select);
    		$results = $statement->execute();
    		$loai_sp = array();
    		for($i = 0; $i<$results->count(); $i++)
    		{
    		$kp = $results->current();
    		$loai_sp[]= $kp;
    		$results->next();
    		}
    		return $loai_sp;
    }
    
    // THÔNG TIN CƠ BẢN WEBSITE
    public function getblock($dbAdapter)
    {
    	$sql = new Sql($dbAdapter);
    	$select = $sql->select();
    	$select->from('block');
    	$select->where(array('active'=>1));
    	$statement = $sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	$product=array();
    	for($i=0;$i<$results->count();$i++)
    	{
    	$kq=$results->current();
    	$product[]=$kq;
    	$results->next();
    	}
    	return $product;
    }
    
    
    public function Data(){
        return "Data";
    }
    public function getData(){
        return "getData";
    }   
}