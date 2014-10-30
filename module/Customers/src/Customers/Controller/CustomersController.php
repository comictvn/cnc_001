<?php
namespace Customers\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Customers\Form\CustomersForm;
use Giohang;
use Customers\Model\Customers;
use Customers\Model\CustomersTable;
use Zend\Authentication\AuthenticationService;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class CustomersController extends AbstractActionController
{
	var $giohang;
	public function __construct()
	{
		$this->giohang = new Giohang\Controller\GiohangController();
		$_SESSION['so_sp']=$this->giohang->so_sp();
		$_SESSION['tong_tien']=$this->giohang->tong_tien();
	}
	
	protected $sanphamTable;
	public function getSanphamTable()
	{
		if(!$this->sanphamTable)
		{
			$sm=$this->getServiceLocator();
			$this->sanphamTable=$sm->get('Sanpham\Model\SanphamTable');
		}
		return $this->sanphamTable;
	}
	protected $SubjectTable;
	public function getSubjectTable()
	{
		if(!$this->SubjectTable)
		{
			$sm=$this->getServiceLocator();
			$this->SubjectTable=$sm->get('Subject\Model\SubjectTable');
		}
		return $this->SubjectTable;
	}
	protected $AdverTable;
	public function getAdverTable()
	{
		if(!$this->AdverTable)
		{
			$sm=$this->getServiceLocator();
			$this->AdverTable=$sm->get('Adver\Model\AdverTable');
		}
		return $this->AdverTable;
	}
    protected $customersTable;
    
    
    public function listAction()
    {
    	$auth = new AuthenticationService();
    	 
    	if (!$auth->hasIdentity())
    		return $this->redirect()->toRoute('user',array('action'=>'login'));
    	 
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$page = (int) $this->params()->fromRoute('id', 0);
    	$dataItems =  $this->getCustomersTable()->fetchAll();
    	$iteratorAdapter = new \Zend\Paginator\Adapter\Iterator($dataItems);
    	$paginator = new \Zend\Paginator\Paginator($iteratorAdapter);
    	 
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(10);
    	return new ViewModel(array(
    			'customers' => $paginator));
    }
    
    public function addAction()
    {
    	date_default_timezone_set('Asia/Saigon');
    	$giohang=new Giohang\Controller\GiohangController();
    	$_SESSION['tong_tien']=$giohang->tong_tien();
    	$_SESSION['so_sp']=$giohang->so_sp();
    	$form = new CustomersForm();
    	$form->get('submit')->setValue('Cập nhật');
    	$request=$this->getRequest();
    	$giohang1 = $this->giohang->product_gio_hang();
    	if(!empty($giohang1))
    	{
    		$ds_masp=array();
    		foreach($giohang1 as $key =>$value)
    		{
    			$ds_masp[]=$key;
    		}
    		$ds_masp=implode(',',$ds_masp);
    		$results=$this->getSanphamTable()->cartlist($ds_masp);
    		$product_giohang=array();
    		 
    		foreach ($results as $row)
    		{
    			$row->qty = $giohang1[$row->id];
    			$product_giohang[]=$row;
    	
    		}
    		 
    	}
    	if($request->isPost())
    	{
    		$Customers = new Customers();
    		$form->setInputFilter($Customers->getInputFilter());
    		$form->setData($request->getPost());
    		if($form->isValid())
    		{
    			//lưu thông tin khách hàng
    			$dulieu = $form->getData();
    		
    			$Customers->exchangeArray($dulieu);
    			$customers_id= $this->getCustomersTable()->saveCustomers($Customers);
    			//Tạo thông tin hóa đơn
    			$data=array(
    					'bill_date'=>date('Y-m-d'),
    					'customers_id'		=>$customers_id,
    					'bill_value'	=>$_SESSION['tong_tien'],
    					'payment'=> $request->getPost('OrderPaymentMethod'),
    					'content'=>$request->getPost('noidung'),
    					);
    			$sm=$this->getServiceLocator();
    			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			
    			$id_bill= $this->getCustomersTable()->saveBill($dbAdapter,$data);
    			$ds_product = $giohang->product_gio_hang();
    			//Lưu giỏ hàng và bảng chi tiết giỏ hàng
    			foreach ($ds_product as $id =>$qty)
    			{
    				$data=array(
    					'bill_id'=>$id_bill,
    					'id'=>$id,
    					'qty'=>$qty,	
    						);
    				$this->getCustomersTable()->saveDetail_bill($dbAdapter,$data);
    			}
    			
    			$data = $form->getData();
    			
    			
    			  	
    		}
    	}
    	
    	$productRandom = $this->getSanphamTable()->fetchAllrandom();
    	$productMax = $this->getSanphamTable()->fetchproductspecial();
    	$Subject = $this->getSubjectTable()->fetchAll();
    	$Adver = $this->getAdverTable()->fetchAll();
    	return new ViewModel(array('form'=>$form,'title'=>'Thanh toán',
    			
    			'ds_sp'=>@$product_giohang,
    	));
    }
    //client
    public function printAction()
    {
    	date_default_timezone_set('Asia/Saigon');
    	$customers_id=(int)$this->params()->fromRoute('id',0);
    	//Đọc hóa đơn khách hàng
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');    	 
    	$bill= $this->getCustomersTable()->fetchbill($dbAdapter,$customers_id);
    	$productdif = $this->getSanphamTable()->fetchAllrandom();
    	return new ViewModel(array('bill'=>$bill,'title'=>'Đơn đặt hàng',
    			'sanphamkhac'=>$productdif,
    	));
    }
    public function getCustomersTable()
    {
    	if(!$this->customersTable)
    	{
    		$sm=$this->getServiceLocator();
    		$this->customersTable=$sm->get('Customers\Model\CustomersTable');
    	}
    	return $this->customersTable;
    }
	public function billAction()
	{
		$this->layout()->setTemplate('layout/layoutadmin');
		$id=(int)$this->params()->fromRoute('id',0);
		//Đọc hóa đơn khách hàng
		$sm=$this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
		$bill= $this->getCustomersTable()->fetchbill($dbAdapter,$id);
		
		return new ViewModel(array('bill'=>$bill));
	}
	
	public function deleteAction()
    {
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->getCustomersTable()->delete($id);
    	return $this->redirect()->toRoute('customers', array('action'=>'list'));
    }
}