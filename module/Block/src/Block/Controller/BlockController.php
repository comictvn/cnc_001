<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Block\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Block\Form\BlockForm;
use Block\Model\Block;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Sql\Ddl\Column\Blob;

class BlockController extends AbstractActionController
{
	protected $BlockTable;
	public function getBlockTable()
	{
		if(!$this->BlockTable)
		{
			$sm=$this->getServiceLocator();
			$this->BlockTable=$sm->get('Block\Model\BlockTable');
		}
		return $this->BlockTable;
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
    	$form = new BlockForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Block = new Block();
    		$form->setInputFilter($Block->getInputFilter());
    		$form->setData($request->getPost());
    	 
    		if($form->isValid())
    		{
    		
    					$data = $form->getData();
    					$Block->exchangeArray($data);
    					$this->getBlockTable()->saveMember($Block);
    					return $this->redirect()->toRoute('Block', array('action'=>'list'));
    				
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
    	$datablock = new Block();
    	$Menu = $this->getBlockTable()->getmemberID($id);
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
		    $sm->get('Block\Model\BlockTable')->deleteblockpage($dbAdapter, $datablockpage);
		    for ($i=0; $i < count($page); $i++) {
		    	$datablockpage['idpage'] = $page[$i];
		    	$sm->get('Block\Model\BlockTable')->insertblockpage($dbAdapter, $datablockpage);
		    }
		    
		 
		    $valueblock1 = explode(',', $_POST['blockvalue1']);
		    $valueblock2 = explode(',', $_POST['blockvalue2']);
		    $blockvalue['idblock'] = $id;
		    $blockvalue['idblockcontent'] = $_POST['idblockcontent1'];
		    $sm->get('Block\Model\BlockTable')->deleteblockcontent($dbAdapter, $blockvalue);
            
            $id_parent_value = explode(',', $_POST['parent']);

            $id_block_content2 = explode(',', $_POST['idblockcontent2']);

            for ($i=0; $i < count($id_parent_value); $i++) {
                $blockvalue['parent'] = $id_parent_value[$i];
                $sm->get('Block\Model\BlockTable')->deleteblockparent($dbAdapter, $blockvalue);
            }

            $i = 0;
		    foreach ($valueblock1 as $valueblock1s) {
                $blockvalue['idblockcontent'] = $_POST['idblockcontent1'];
		    	$blockvalue['value'] = $valueblock1s;
		    	$blockvalue['parent'] = 0;
		    	$ins1 = $sm->get('Block\Model\BlockTable')->insertblockcontent($dbAdapter, $blockvalue);

                foreach($id_block_content2 as $id_block_content2s) {
                    $blockvalue['value'] = $valueblock2[$i];
                    $blockvalue['idblockcontent'] = $id_block_content2s;
                    $blockvalue['parent'] = $ins1;
                    $sm->get('Block\Model\BlockTable')->insertblockcontent($dbAdapter, $blockvalue);
                    $i++;
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
    	
    	$Block = $this->getBlockTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$position = $sm->get('Block\Model\BlockTable')->getposition($dbAdapter);
    	$page = $sm->get('Block\Model\BlockTable')->getpage($dbAdapter);
    	$pageblock = $sm->get('Block\Model\BlockTable')->getpageblock($dbAdapter);
    	$blockcontent = $sm->get('Block\Model\BlockTable')->getblockcontent($dbAdapter);
    	$blockcontent0 = $sm->get('Block\Model\BlockTable')->getblockcontent0($dbAdapter);
    	
    	return new ViewModel(array('Block'=>$Block, 'position'=>$position,
    			'page'=>$page,'pageblock'=>$pageblock,'blockcontent'=>$blockcontent,
    			'blockcontent0'=>$blockcontent0
    	));
    }
    
    public function htmlAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	 
    	$id = (int)$this->params()->fromRoute('id');
    	$form = new BlockForm();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    	$position = $sm->get('Block\Model\BlockTable')->getposition($dbAdapter);
    	$positionform = $sm->get('Block\Model\BlockTable')->getposition1($dbAdapter);
    	$form->get('position')->setValueOptions($positionform);
    	$page = $sm->get('Block\Model\BlockTable')->getpage($dbAdapter);
    	$pageblock = $sm->get('Block\Model\BlockTable')->getpageblock($dbAdapter);
    	$blockcontent = $sm->get('Block\Model\BlockTable')->getblockcontent($dbAdapter);
    	$blockcontent0 = $sm->get('Block\Model\BlockTable')->getblockcontent0($dbAdapter);
    	if($id != 0) {
    		$Block = $this->getBlockTable()->getmemberID($id);
    		$form->bind($Block);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Block->getInputFilter());
    			$form->setData($request->getPost());
    			 
    			if($form->isValid())
    			{
    				$data = $form->getData();
    				
    				$this->getBlockTable()->saveMember($data);
    				return $this->redirect()->toRoute('block', array('action'=>'html'));
    		
    			}
    		
    		}
    	} else {
    		$Block = $this->getBlockTable()->fetchAllhtml();
    		$request = $this->getRequest();
    		if ($request->isPost()) {
    			$Block = new Block();
    			$form->setInputFilter($Block->getInputFilter());
    			$form->setData($request->getPost());
    			echo $_POST['name'];
    			if($form->isValid())
    			{
    				 
    				$data = $form->getData();
    				
    				
    				$Block->exchangeArray($data);
    				$this->getBlockTable()->saveMember($Block);
    				return $this->redirect()->toRoute('block', array('action'=>'html'));
    				 
    			}
    		}
    	}
    	
    	$Block = $this->getBlockTable()->fetchAllhtml();
    	 
    	
    	return new ViewModel(array('form'=>$form, 'id'=>$id,'Block'=>$Block, 'position'=>$position,
    			'page'=>$page,'pageblock'=>$pageblock,'blockcontent'=>$blockcontent,
    			'blockcontent0'=>$blockcontent0
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
    	$this->getBlockTable()->delete($id);
    	return $this->redirect()->toRoute('Block', array('action'=>'list'));
    }
    
   
    
    
}
