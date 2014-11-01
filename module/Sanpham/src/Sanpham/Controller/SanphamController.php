<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sanpham\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Sanpham\Form\SanphamForm;
use Sanpham\Model\Sanpham;
use Zend\Authentication\AuthenticationService;

use Giohang;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Rbac\Role;

class SanphamController extends AbstractActionController
{
	protected $SanphamTable;
	
    var $giohang;
    public function __construct() 
    {
        $this->giohang = new Giohang\Controller\GiohangController();
        $_SESSION['so_sp']=$this->giohang->so_sp();
        $_SESSION['tong_tien']=$this->giohang->tong_tien();
    }

	public function getSanphamTable()
	{
		if(!$this->SanphamTable)
		{
			$sm=$this->getServiceLocator();
			$this->SanphamTable=$sm->get('Sanpham\Model\SanphamTable');
		}
		return $this->SanphamTable;
	}
	
  
    
    
    public function getparamAction(){
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$idcate = $_POST['idcate'];
    	$cate = $sm->get('Sanpham\Model\SanphamTable')->getparam($dbAdapter, $idcate);
    
    	echo json_encode($cate);
    	exit();
    }
    public function getparam1Action(){
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$cate = $sm->get('Sanpham\Model\SanphamTable')->getparam1($dbAdapter);
    
    	echo json_encode($cate);
    	exit();
    }
    
    public function addAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate("layout/layoutadmin");
    
    	$form = new SanphamForm();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$procate = $sm->get('Sanpham\Model\SanphamTable')->getcate($dbAdapter);
    	$cate = $sm->get('Sanpham\Model\SanphamTable')->getcateparam($dbAdapter);
    	$param0 = $sm->get('Sanpham\Model\SanphamTable')->getcateparam0($dbAdapter);
    	$param1 = $sm->get('Sanpham\Model\SanphamTable')->getcateparam1($dbAdapter);
    	$contact = $sm->get('Sanpham\Model\SanphamTable')->getcontact($dbAdapter);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Sanpham = new Sanpham();
    		$form->setInputFilter($Sanpham->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
	    		$data = $form->getData();
	    		$Sanpham->exchangeArray($data);
	    		$idpro = $this->getSanphamTable()->saveMember($Sanpham);
	    		
	    		$data_cate['idpro'] = $idpro;
	    		$cate = @$_POST['cate'];
	    		if(isset($cate)) {
	    		for($i=0; $i < count($cate); $i++) {
	    			$data_cate['idcate'] = $cate[$i];
	    			$sm->get('Sanpham\Model\SanphamTable')->insertcate($dbAdapter, $data_cate);	
	    		}}
	    		
	    		if(isset($_POST['imageshow'])) {
				foreach ($_POST['imageshow'] as $key =>$value) {
					$data_image['idpro'] = $idpro;
					$data_image['link'] = $value;
					$sm->get('Sanpham\Model\SanphamTable')->insertimage($dbAdapter, $data_image);
					
				}
	    		}
	    		
	    		if(isset($_POST['tenlienhe'])) {
				for($j=0; $j < count($_POST['tenlienhe']); $j++) {
					
					$data_contact['namecontact'] = @$_POST['tenlienhe'][$j];
					$data_contact['contactperson'] = @$_POST['contactperson'][$j];
					$data_contact['business'] = @$_POST['business'][$j];
					$data_contact['address'] = @$_POST['address'][$j];
					$data_contact['license'] = @$_POST['license'][$j];
					$data_contact['taxcode'] = @$_POST['taxcode'][$j];
					$data_contact['phone'] = @$_POST['phone'][$j];
					$data_contact['email'] = @$_POST['email'][$j];
					$data_contact['yahoo'] = @$_POST['yahoo'][$j];
					$data_contact['skype'] = @$_POST['skype'][$j];
					$resultcontact = $sm->get('Sanpham\Model\SanphamTable')->insertinfocontact($dbAdapter, $data_contact);
					$data_procontact['idpro'] = $idpro;
					$data_procontact['idinfocontact'] = $resultcontact;
					$sm->get('Sanpham\Model\SanphamTable')->insertprocontact($dbAdapter, $data_procontact);
					
				} }

				if(isset($_POST['contact'])) {
					for($c = 0; $c < count($_POST['contact']); $c++) {
						$data_procontact['idpro'] = $idpro;
						$data_procontact['idinfocontact'] = $_POST['contact'][$c];
						$sm->get('Sanpham\Model\SanphamTable')->insertprocontact($dbAdapter, $data_procontact);
					}
				}
 
				foreach ($param1 as $param1s) {
					$data_param['idparam'] = $param1s['id'];
					$data_param['idpro'] = $idpro ;
					$data_param['value'] = @$_POST['param'.$param1s['id']];
					$sm->get('Sanpham\Model\SanphamTable')->insertparam($dbAdapter, $data_param);
				}
				
				
				
	    		return $this->redirect()->toRoute('sanpham', array('action'=>'list'));
    		}
    	}
    	return new ViewModel(array('form'=>$form,'cate'=>$cate, 
    			'param0'=>$param0, 'param1'=>$param1,
    			'procate'=>$procate,
    			'contact'=>$contact,
    	));
    }
    
	public function editAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutedit');
    	$id = (int)$this->params()->fromRoute('id',0);
    	
    	if(!$id)
    	{
    		return $this->redirect()->toRoute('sanpham',array('action'=>'add'));
    	}
    	else
    	{
    		$Sanpham = $this->getSanphamTable()->getmemberID($id);
    		$form = new SanphamForm();
    		$sm=$this->getServiceLocator();
    		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		$procate = $sm->get('Sanpham\Model\SanphamTable')->getcate($dbAdapter);
    		$procateid = $sm->get('Sanpham\Model\SanphamTable')->getcateid($dbAdapter, $id);
    		$cate = $sm->get('Sanpham\Model\SanphamTable')->getcateparam($dbAdapter);
    		$param0 = $sm->get('Sanpham\Model\SanphamTable')->getcateparam0($dbAdapter);
    		$param1 = $sm->get('Sanpham\Model\SanphamTable')->getcateparam1($dbAdapter);
    		$paramdetail = $sm->get('Sanpham\Model\SanphamTable')->getparamdetails($dbAdapter, $id);
    		$contact = $sm->get('Sanpham\Model\SanphamTable')->getcontact($dbAdapter);
    		$contactid = $sm->get('Sanpham\Model\SanphamTable')->getcontactid($dbAdapter, $id);
    		$imagedetails = $sm->get('Sanpham\Model\SanphamTable')->getproimage($dbAdapter, $id);
    		
    		$form->bind($Sanpham);
    		$form->image = $Sanpham->image;
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Sanpham->getInputFilter());
    			$form->setData($request->getPost());
    		 	
    			if($form->isValid())
    			{
    				// EDIT CATEGORY
    				$data_cate['idpro'] = $id;
    				$cate = @$_POST['cate'];
    				$sm->get('Sanpham\Model\SanphamTable')->deletecate($dbAdapter, $data_cate);
    				if(isset($cate)) {
    					for($i=0; $i < count($cate); $i++) {
    						$data_cate['idcate'] = $cate[$i];
    						$sm->get('Sanpham\Model\SanphamTable')->insertcate($dbAdapter, $data_cate);
    				}}
    				
    				// EDIT IMAGESHOW
    				$data_image['idpro'] = $id;
    				$sm->get('Sanpham\Model\SanphamTable')->deleteimage($dbAdapter, $data_image);
    				if(isset($_POST['imageshow'])) {
    					foreach ($_POST['imageshow'] as $key =>$value) {
    						
    						$data_image['link'] = $value;
    						$sm->get('Sanpham\Model\SanphamTable')->insertimage($dbAdapter, $data_image);
    							
    					}
    				}
    				
    				foreach ($param1 as $param1s) {
    					$data_param['idparam'] = $param1s['id'];
    					$data_param['idpro'] = $id ;
    					$data_param['value'] = @$_POST['param'.$param1s['id']];
    					$sm->get('Sanpham\Model\SanphamTable')->updateparam($dbAdapter, $data_param);
    				}
    				
    				// EDIT CONTACT 
    				$data_procontact['idpro'] = $id;
    				$sm->get('Sanpham\Model\SanphamTable')->deletecontact($dbAdapter, $data_procontact);
    				if(isset($_POST['contact'])) {
    					for($c = 0; $c < count($_POST['contact']); $c++) {
    						$data_procontact['idinfocontact'] = $_POST['contact'][$c];
    						$sm->get('Sanpham\Model\SanphamTable')->insertprocontact($dbAdapter, $data_procontact);
    					}
    				}
    				
	    			$data = $form->getData();
	    			$this->getSanphamTable()->saveMember($data);
	    			return $this->redirect()->toRoute('sanpham', array('action'=>'edit','id'=>$id));
    			}
    			 
    		}
    	}
    	return array('id'=>$id,'form'=>$form,
    			'cate'=>$cate,
    			'param0'=>$param0, 'param1'=>$param1,
    			'procate'=>$procate,
    			'procateid'=>$procateid,
    			'contact'=>$contact,
    			'contactid'=>$contactid,
    			'imagedetails'=>$imagedetails,
    			'paramdetail'=>$paramdetail
    	);
    
    	
    }
    
    public function listAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$Sanpham = $this->getSanphamTable()->getAll();
    	return new ViewModel(array('sanpham'=>$Sanpham));
    }
    
    public function deleteAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->getSanphamTable()->delete($id);
    	return $this->redirect()->toRoute('Sanpham', array('action'=>'list'));
    }
    
    // VIEW 
    
    public function indexAction()
    {
    	$page = (int) $this->params()->fromRoute('id', 0);
    	$product=$this->getSanphamTable()->getAllcateindex();
    	
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$seopage = $sm->get('Sanpham\Model\SanphamTable')->getseo($dbAdapter);
    	 
    	$renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($seopage['title']);
    	$renderer->headMeta()->appendName('keywords', $seopage['meta']);
    	$renderer->headMeta()->appendName('description', $seopage['keywork']);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$cate = $sm->get('Sanpham\Model\SanphamTable')->getcate($dbAdapter);
    	return new ViewModel(array('result'=>$product,  'cate'=> $cate,
    			
    	));
    }
    
    public function detailAction()
    {
    	$alias = $this->params()->fromRoute('alias');
    	$product = $this->getSanphamTable()->getdetail($alias);
        $other_product = $this->getSanphamTable()->getOtherProduct(4);

    	$renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($product->proname);
    	$renderer->headMeta()->appendName('keywords', $product->meta);
    	$renderer->headMeta()->appendName('description', $product->keyword);
    	
    	
    	return new ViewModel(array('result'=>$product,
            'other_product' => $other_product,
            ));
    }
    
    public function cateAction()
    {
    	$page = (int) $this->params()->fromRoute('page', 0);
    	$id = $this->params()->fromRoute('alias');
    	
    	$product = $this->getSanphamTable()->getAllcate($id);
        
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$seopage = $sm->get('Sanpham\Model\SanphamTable')->getseo($dbAdapter);
    	
    	$renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($seopage['title']);
    	$renderer->headMeta()->appendName('keywords', $seopage['meta']);
    	$renderer->headMeta()->appendName('description', $seopage['keywork']);
    	
    	
    	return new ViewModel(array('result'=>$product,'alias'=>$id,
    		
    	));
    }

    public function searchAction() {
        $page = (int) $this->params()->fromRoute('page', 0);
        $name = @$_GET['search'] ;
        
        $product = $this->getSanphamTable()->search($name);
      
        $sm=$this->getServiceLocator();
        $dbAdapter = $sm->get('db1');
        $seopage = $sm->get('Sanpham\Model\SanphamTable')->getseo($dbAdapter);
        
        $renderer = $this->getServiceLocator()->get(
                'Zend\View\Renderer\PhpRenderer');
        $renderer->headTitle($seopage['title']);
        $renderer->headMeta()->appendName('keywords', $seopage['meta']);
        $renderer->headMeta()->appendName('description', $seopage['keywork']);
        
        
        return new ViewModel(array('result'=>$product,
            
        ));
    }

    public function giohangAction()
    {
        
        $request=$this->getRequest();
        if($request->isPost())
        {
            $form=$request->getPost();
            
            $giohang=$this->giohang->product_gio_hang();
            foreach($giohang as $key =>$value)
            {
                if($form["xoa$key"])
                    $this->giohang->xoa_product($form["xoa$key"],$form["price$key"]);
            }
            
            $giohang=$this->giohang->product_gio_hang();
            foreach($giohang as $key =>$value)
            {
                if(is_numeric($form["qty$key"]) && $form["qty$key"]>0 && $form["qty$key"]!= $value)
                {
                    $this->giohang->kiem_tra_cap_nhat($form["id$key"],(int)$form["qty$key"],$form["price$key"]);
                }
            }
        }

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
  
        return new ViewModel(array('ds_sp'=>@$product_giohang ,'tong_tien'=>$this->giohang->tong_tien(),
             
        ));
        
    }
    
    
}
