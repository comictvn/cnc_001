<?php
namespace Giohang\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Sql;
use Zend\File\Transfer\Transfer;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Giohang\Model\GiohangTable;

class GiohangController extends AbstractActionController
{
    protected $giohangTable;
    public function indexAction()
    {
    	date_default_timezone_set('Asia/Saigon');
        $ma_sp=$this->params()->fromRoute('sp');
        echo $ma_sp;
    }
    public function muaAction()
    {
        $id=$this->params()->fromRoute('id');
        $sl=$this->params()->fromRoute('sl');
        $dg=$this->params()->fromRoute('dg');
        $giohang= new Container('giohang');
		if($giohang->offsetExists("product"))
        {
            if(isset($giohang->product[$id]))
                $giohang->tong_tien-=$giohang->product[$id]*$dg;                

            $giohang->product[$id]=$sl;
            $giohang->tong_tien+=$sl*$dg; 
            $giohang->so_sp=count($giohang->product);                                 
        }
        else
        {
            $giohang->product = array($id=>$sl);//$giohang->product[$product]=$sl;
            $giohang->tong_tien=$sl*$dg;
            $giohang->so_sp=1;                      
        }
        $arr = array('ssp'=>$giohang->so_sp,'tt'=>$giohang->tong_tien)  ;  
        return $this->redirect()->toRoute('sanpham', array('action'=>'giohang'));
    }
   public function tong_tien()
   {
        $giohang=new Container('giohang');  
        if($giohang->offsetExists("product"))
        	return $giohang->tong_tien;
        return 0;
   }
   public function so_sp()
   {
        $giohang=new Container('giohang');
        if($giohang->offsetExists("product"))
        	return $giohang->so_sp;
        return 0;
   }
   public function product_gio_hang()
   {
	   	$giohang=new Container('giohang');
	   	if($giohang->offsetExists("product"))
	   		return $giohang->product;
	   	return array();
   }
   //huy gio hang dung trong truong hop thanh toan
   public function huygiohang()
   {
	   	$giohang=new Container('giohang');
        if($giohang->offsetExists("product"))
            $giohang->getManager()->getStorage()->clear();
        return NULL;
   }
   public function xoa_product($vitri,$dg)
   {
        $giohang=new Container('giohang');
        $giohang->tong_tien-=$giohang->product[$vitri]*$dg;
        $gio_hang_moi=array();
        foreach($giohang->product as $key=>$value)
        {
            if($key!=$vitri)
            {
                $gio_hang_moi[$key]=$value;
            }
        }
        if($gio_hang_moi)
        {
            $giohang->product=$gio_hang_moi;
            $giohang->so_sp=count($gio_hang_moi);
        }           
        else
            $giohang->getManager()->getStorage()->clear();
        return false;
   }
   public function huygiohangAction()
   {
        $giohang=new Container('giohang');
        if($giohang->offsetExists("product"))
            $giohang->getManager()->getStorage()->clear();
        return $this->redirect()->toRoute('product');
   }
   public function huygiohangajaxAction()
   {
   	$giohang=new Container('giohang');
   	if($giohang->offsetExists("product"))
   		$giohang->getManager()->getStorage()->clear();
   	exit();
   }
   public function kiem_tra_cap_nhat($vitri,$so_luong,$don_gia)
   {
        $giohang=new Container('giohang');
        if(isset($giohang->product[$vitri]))
        {
            if($giohang->product[$vitri]!=$so_luong)
            {
                $giohang->tong_tien-=$giohang->product[$vitri]*$don_gia;
                $giohang->product[$vitri]=$so_luong;
                $giohang->tong_tien+=$so_luong*$don_gia;
            }
        }
        
        return false;
   }
   public function listAction()
   {
   	$request=$this->getRequest();
   	if($request->isPost())
   	{
   		$form=$request->getPost();
   		//Xóa sản phẩm đang chọn
   		$giohang=$this->product_gio_hang();
   		foreach($giohang as $key =>$value)
   		{
   			if($form["xoa$key"])
   				$this->xoa_product($form["xoa$key"],$form["price$key"]);
   		}
   		//Cập nhật lại sản phẩm
   		$giohang=$this->product_gio_hang();
   		foreach($giohang as $key =>$value)
   		{
   			if(is_numeric($form["qty$key"]) && $form["qty$key"]>0 && $form["qty$key"]!= $value)
   			{
   				$this->kiem_tra_cap_nhat($form["id$key"],(int)$form["qty$key"],$form["price$key"]);
   			}
   		}
   	}
   	$giohang=new Container('giohang');
   	if($giohang->offsetExists("product"))
   	{
   		$_SESSION['tong_tien']=$giohang->tong_tien;
   		$_SESSION['so_sp']=$giohang->so_sp;
   		$ds_masp=array();
   		foreach($giohang->product as $key =>$value)
   		{
   			$ds_masp[]=$key;
   		}
   		$ds_masp=implode(',',$ds_masp);
   		$sm=$this->getServiceLocator();
   		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
   		$giohangTable=new GiohangTable();
   		$results=$giohangTable->listAll($dbAdapter,$ds_masp);
   		$product_giohang=array();
   		for($i=0;$i<$results->count();$i++)
   		{
   		$kq=$results->current();
   		$ma_sp=$kq['id'];
   		$kq['qty']=$giohang->product[$ma_sp];
   		$product_giohang[]=$kq;
   		$results->next();
   		}
   		return new ViewModel(array('ds_sp'=>$product_giohang));
   	}
   		return new ViewModel();
   }
   public function getAlbumTable()
   {
        if(!$this->giohangTable)
        {
            $sm=$this->getServiceLocator();
            $this->giohangTable=$sm->get('Giohang\Model\GiohangTable');
        }
        return $this->giohangTable;
   }
   
   public function testAction()
   {
   	return new ViewModel(array('title'=> 'Danh sách kkk sản phẩm'));
   }
}