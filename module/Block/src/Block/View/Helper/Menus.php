<?php
namespace Block\View\Helper;
use Zend\View\Helper\AbstractHelper;

class Menus extends AbstractHelper
{
//use \Zend\ServiceManager\ServiceLocatorAwareTrait;

 protected $data; 

function __invoke(){

        return $this->data;
        
    }

   
    public function setTable($sm, $table1)
    {
    	$dbAdapter = $sm->get('db1');
        $this->data= $sm->get($table1)->getnew($dbAdapter);
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