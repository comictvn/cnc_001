<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Slide\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Slide\Form\SlideForm;
use Slide\Model\Slide;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Ddl\Column\Blob;

class SlideController extends AbstractActionController
{
	protected $SlideTable;
	public function getSlideTable()
	{
		if(!$this->SlideTable)
		{
			$sm=$this->getServiceLocator();
			$this->SlideTable=$sm->get('Slide\Model\SlideTable');
		}
		return $this->SlideTable;
	}
   
    
    public function getInputImage($files)
    {
    	$errors = array();
    	//Kiểm tra kiểu file
    	$validator = new \Zend\Validator\File\Extension('jpg,png,jpeg');
    	if(!$validator->isValid($files))//Kiểu hợp lệ
    	{
    		$dataError = $validator->getMessages();
    		foreach ($dataError as $key=>$row)
    		{
    			if($key=='fileExtensionFalse')
    				$errors[]= 'File không hợp lệ';
    			if($key=='fileExtensionNotFound')
    				$errors[]= 'Tập tin không thể đọc được';
    			 
    		}
    		return $errors;
    	}
    	 
    	//Kiểm tra kích cỡ file
    	$validator = new \Zend\Validator\File\ImageSize();
    	$validator->setMinHeight(100);
    	$validator->setMaxHeight(1000);
    	$validator->setMinWidth(100);
    	$validator->setMaxWidth(1900);
    	if(!$validator->isValid($files)) //Kích cỡ hợp lệ
    	{
    		$dataError = $validator->getMessages();
    		foreach ($dataError as $key=>$row)
    		{
    			if($key == 'fileImageSizeWidthTooBig')
    				$errors[] = 'Chiều rộng quá lớn';
    			if($key == 'fileImageSizeWidthSmall')
    				$errors[] = 'Chiều rộng quá nhỏ';
    			if($key == 'fileImageSizeHeightTooBig')
    				$errors[] = 'Chiều cao quá lớn';
    			if($key == 'fileImageSizeHeightSmall')
    				$errors[] = 'Chiều cao quá nhỏ';
    		}
    		return $errors;
    	}
    	 
    	//Kiểm tra size
    	$validator = new \Zend\Validator\File\FilesSize(1000000);
    	if(!$validator->isValid($files))// Kích thước hợp lệ
    	{
    		$dataError = $validator->getMessages();
    		foreach ($dataError as $key=>$row)
    		{
    			if($key == 'fileFilesSizeTooBig')
    				$errors[] = 'Hình phải có kích thước tối đa 2Mb';
    		}
    		return $errors;
    	}
    	return $errors;
    }
    
    public function addAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate("layout/layoutadmin");
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('user', array('action'=>'login'));
    		 
    	}
    	$form = new SlideForm();
    	
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Slide = new Slide();
    		$form->setInputFilter($Slide->getInputFilter());
    		$form->setData($request->getPost());
    	 
    		if($form->isValid())
    		{
    		
    					$data = $form->getData();
    					$Slide->exchangeArray($data);
    					$this->getSlideTable()->saveMember($Slide);
    					return $this->redirect()->toRoute('Slide', array('action'=>'list'));
    				
    		}
    	}
    	return new ViewModel(array('form'=>$form));
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
    	$datablock = new Slide();
    	$Menu = $this->getSlideTable()->getmemberID($id);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	
	    	$data['id'] = $Menu->id  ;
		    $data['module'] = $Menu->module ;
		    $data['position'] = $_POST['position'] ;
		    $data['name'] = $Menu->name ;
		    $data['html'] = $Menu->html ;
		    $data['link'] = $Menu->link ;
		    $data['active'] = $_POST['active'] ;
		   
		    $page = explode(',', $_POST['page']);
		    $datablockpage['idblock'] = $id;
		    $sm->get('Slide\Model\SlideTable')->deleteblockpage($dbAdapter, $datablockpage);
		    for ($i=0; $i < count($page); $i++) {
		    	$datablockpage['idpage'] = $page[$i];
		    	$sm->get('Slide\Model\SlideTable')->insertblockpage($dbAdapter, $datablockpage);
		    }
		    
		    $blockcontent = $sm->get('Slide\Model\SlideTable')->getblockcontent($dbAdapter);
		    $blockcontent0 = $sm->get('Slide\Model\SlideTable')->getblockcontent0($dbAdapter);
		    $valueblock1 = explode(',', $_POST['blockvalue1']);
		    $valueblock2 = explode(',', $_POST['blockvalue2']);
		    $blockvalue['idblock'] = $id;
		    $blockvalue['idblockcontent'] = $_POST['idblockcontent1'];
		    $sm->get('Slide\Model\SlideTable')->deleteblockcontent($dbAdapter, $blockvalue);
		    foreach ($valueblock1 as $valueblock1s) {
		    	$blockvalue['value'] = $valueblock1s;
		    	$blockvalue['parent'] = 0;
		    	$ins1 = $sm->get('Slide\Model\SlideTable')->insertblockcontent($dbAdapter, $blockvalue);
		    	$blockvalue['idblockcontent'] = $_POST['idblockcontent2'];
		    	$sm->get('Slide\Model\SlideTable')->deleteblockcontent($dbAdapter, $blockvalue);
		    	foreach ($valueblock2 as $valueblock2s) {
		    		$blockvalue['value'] = $valueblock2s;
		    		$blockvalue['parent'] = $ins1;
		    		$sm->get('Slide\Model\SlideTable')->insertblockcontent($dbAdapter, $blockvalue);
		    	}
		    }
		    
		    $datablock->exchangeArray($data);
			$this->getBlockTable()->saveMember($datablock);
		    exit();
    		
    	
    }
    
    public function listAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$form = new SlideForm();
    	$Slide = $this->getSlideTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$position = $sm->get('Slide\Model\SlideTable')->getposition($dbAdapter);
    	$page = $sm->get('Slide\Model\SlideTable')->getpage($dbAdapter);
    	$pageSlide = $sm->get('Slide\Model\SlideTable')->getpageblock($dbAdapter);
    	$Slidecontent = $sm->get('Slide\Model\SlideTable')->getblockcontent($dbAdapter);
    	$Slidecontent0 = $sm->get('Slide\Model\SlideTable')->getblockcontent0($dbAdapter);
    	foreach ($position as $positions)
    	{
    		$vitri[$positions['alias']] = $positions['name'];
    	}
    	
    	$form->get('position')->setValueOptions($vitri);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Slide = new Slide();
    		$form->setInputFilter($Slide->getInputFilter());
    		$form->setData($request->getPost());
    	
    		if($form->isValid())
    		{
    	
    			$data = $form->getData();
    			$Slide->exchangeArray($data);
    			$idslide = $this->getSlideTable()->saveMember($Slide);
    			$datablockpage['idblock'] = $idslide;
    			$page = $_POST['page'];
    			$sm->get('Slide\Model\SlideTable')->deleteblockpage($dbAdapter, $datablockpage);
    			for ($i=0; $i < count($page); $i++) {
    				$datablockpage['idpage'] = $page[$i];
    				$sm->get('Slide\Model\SlideTable')->insertblockpage($dbAdapter, $datablockpage);
    			}
    			return $this->redirect()->toRoute('slide', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('Slide'=>$Slide, 'position'=>$position,
    			'page'=>$page,'pageSlide'=>$pageSlide,'Slidecontent'=>$Slidecontent,
    			'Slidecontent0'=>$Slidecontent0,'form'=>$form
    	));
    }
    
    
    
    public function deleteAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->getSlideTable()->delete($id);
    	return $this->redirect()->toRoute('Slide', array('action'=>'list'));
    }
    
    public function imageAction()
    {
    	
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$id = (int)$this->params()->fromRoute('id',0);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$slide = $sm->get('Slide\Model\SlideTable')->getslide($dbAdapter, $id);
    	$slidevalue = array();
    	foreach ($slide as $slides)
    	{
    		$slidevalue[] = $sm->get('Slide\Model\SlideTable')->getslidevalue($dbAdapter, $id, $slides['id']);
    		
    	}
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$blockvalue['idblock'] = $id;
    		$blockvalue['idblockcontent'] = 8 ;
    		$blockvalue['value'] = @$_POST['value1'];
    		$blockvalue['parent'] = 0;
    		$ins1 = $sm->get('Slide\Model\SlideTable')->insertblockcontent($dbAdapter, $blockvalue);
    		$valueblock2 = $_POST['value2'];
    		//print_r($_POST['value2']); exit();
    		
    		$i = 0;
    		foreach  ($valueblock2 as $valueblock2s) {
    			$blockvalue['idblockcontent'] = $_POST['idblockcontent2'][$i];
    			$blockvalue['value'] = $valueblock2s;
    			$blockvalue['parent'] = $ins1;
    			$sm->get('Slide\Model\SlideTable')->insertblockcontent($dbAdapter, $blockvalue);
    			$i++;
    		}
    		return $this->redirect()->toRoute('slide',array('action'=>'image', 'id'=>$id));
    	}
    	
    	return new ViewModel(array('slide'=>$slide,'slidevalue'=>$slidevalue));
    }
    
    public function editimageAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutedit');
    	$id = (int)$this->params()->fromRoute('id',0);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$data = $sm->get('Slide\Model\SlideTable')->geteditslideimage($dbAdapter, $id);
    	$name = $sm->get('Slide\Model\SlideTable')->geteditslideimage_name($dbAdapter, $id);
    	
    	$data['name'] = $name[0]['value'];
    	$data['image'] = $data[0]['value'];
    	$data['link'] = $data[1]['value'];
    	$data['title'] = $data[2]['value'];
    	$data['content'] = $data[3]['value'];
    	$data['order'] = $data[4]['value'];
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$sm->get('Slide\Model\SlideTable')->deleteimageslide($dbAdapter, $id);
    		$blockvalue['idblock'] = $name[0]['idblock'];
    		$blockvalue['idblockcontent'] = 8 ;
    		$blockvalue['value'] = @$_POST['value1'];
    		$blockvalue['parent'] = 0;
    		$ins1 = $sm->get('Slide\Model\SlideTable')->insertblockcontent($dbAdapter, $blockvalue);
    		$valueblock2 = $_POST['value2'];
    		//print_r($_POST['value2']); exit();
    	
    		$i = 0;
    		foreach  ($valueblock2 as $valueblock2s) {
    			$blockvalue['idblockcontent'] = $_POST['idblockcontent2'][$i];
    			$blockvalue['value'] = $valueblock2s;
    			$blockvalue['parent'] = $ins1;
    			$sm->get('Slide\Model\SlideTable')->insertblockcontent($dbAdapter, $blockvalue);
    			$i++;
    		}
    		return $this->redirect()->toRoute('slide',array('action'=>'editimage', 'id'=>$ins1));
    	}
    	return new ViewModel(array('data'=>$data,'id'=>$id));
    }
    
    public function deleteimageAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutedit');
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$id = (int)$this->params()->fromRoute('id',0);
    	$sm->get('Slide\Model\SlideTable')->deleteimageslide($dbAdapter, $id);
    }
   
    
    
}
