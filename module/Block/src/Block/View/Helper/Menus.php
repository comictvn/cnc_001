<?php
namespace Block\View\Helper;
use Zend\View\Helper\AbstractHelper;

use Giohang;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Rbac\Role;

class Menus extends AbstractHelper
{
//use \Zend\ServiceManager\ServiceLocatorAwareTrait;

 protected $data; 

    function __invoke(){

        return $this->data;
        
    }

    var $giohang;
    public function __construct() 
    {
        $this->giohang = new Giohang\Controller\GiohangController();
        $_SESSION['so_sp']=$this->giohang->so_sp();
        $_SESSION['tong_tien']=$this->giohang->tong_tien();
    }

    public function giohang($sm, $table1) {
        $giohang = $this->giohang->product_gio_hang();

        if(!empty($giohang))
        {
        
            $ds_masp=array();
            foreach($giohang as $key =>$value)
            {
                $ds_masp[]=$key;
            }
            $ds_masp=implode(',',$ds_masp); 
            $results=$this->getSanphamTable()->cartlist($ds_masp);
            $product_giohang=array();
            
            foreach ($results as $row)
            {
                $row->qty = $giohang[$row->id];
                $product_giohang[]=$row;
                
            }
            
        }

        $this->data = $product_giohang
        return $this->data;
    }

   
    public function setTable($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
        $this->data = $sm->get($table1)->getnew($dbAdapter);
    	return $this->data;
    }
    
    public function productnew($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getproductnew($dbAdapter);
    	return $this->data;
    }
	
    public function productnewvalue($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getproductnew_value($dbAdapter);
    	return $this->data;
    }
    
    // THÔNG TIN CƠ BẢN WEBSITE
    
    public function intro($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getintro($dbAdapter);
    	return $this->data;
    }
    
    // TỐI ƯU HÓA WEBSITE
    
    public function seopage($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getseopage($dbAdapter);
    	return $this->data;
    }
    
    // DANH MỤC SẢN PHẨM
    
    public function catepro($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getcatepro($dbAdapter);
    	return $this->data;
    }
    
    public function getcateservice($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getcateservice($dbAdapter);
    	return $this->data;
    }
    public function getgioithieu($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data= $sm->get($table1)->getgioithieu($dbAdapter);
    	return $this->data;
    }

    public function getcatetintuc($sm, $table1)
    {
        $dbAdapter = $sm->get('db1');
        $this->data= $sm->get($table1)->getcatetintuc($dbAdapter);
        return $this->data;
    }
    
    // SLIDE
    
    public function slide($sm, $table1, $idblock)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data = $sm->get($table1)->getslide($dbAdapter, $idblock);
    	return $this->data;
    }
    
    public function slidevalue($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data = $sm->get($table1)->getslidevalue($dbAdapter);
    	return $this->data;
    }
    
    // BLOCK
    public function block($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
    	$this->data = $sm->get($table1)->getblock($dbAdapter);
    	return $this->data;
    }
	
}