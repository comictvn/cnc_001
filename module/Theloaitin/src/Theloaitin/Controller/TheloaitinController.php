<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Theloaitin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Theloaitin\Form\TheloaitinForm;
use Theloaitin\Model\Theloaitin;
use Zend\Authentication\AuthenticationService;

class TheloaitinController extends AbstractActionController
{
	protected $TheloaitinTable;
	public function getTheloaitinTable()
	{
		if(!$this->TheloaitinTable)
		{
			$sm=$this->getServiceLocator();
			$this->TheloaitinTable=$sm->get('Theloaitin\Model\TheloaitinTable');
		}
		return $this->TheloaitinTable;
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
    	$form = new TheloaitinForm();
    	
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Theloaitin = new Theloaitin();
    		$form->setInputFilter($Theloaitin->getInputFilter());
    		$form->setData($request->getPost());
    	 
    		if($form->isValid())
    		{
    		
    					$data = $form->getData();
    					$Theloaitin->exchangeArray($data);
    					$this->getTheloaitinTable()->saveMember($Theloaitin);
    					return $this->redirect()->toRoute('Theloaitin', array('action'=>'list'));
    				
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
    	if(!$id)
    	{
    		return $this->redirect()->toRoute('Theloaitin',array('action'=>'add'));
    	}
    	else
    	{
    		$Theloaitin = $this->getTheloaitinTable()->getmemberID($id);
    		$form = new TheloaitinForm();
    		$sm=$this->getServiceLocator();
    		$dbAdapter = $sm->get('db1');
    		$parent = $sm->get('Theloaitin\Model\TheloaitinTable')->fetchTheloaitin($dbAdapter);
    		$parent[0] = 'Cấp cha';
    		ksort($parent);
    		$form->get('parent')->setValueOptions($parent);
    		$form->bind($Theloaitin);
    		$form->image = $Theloaitin->image;
    		$form->icon = $Theloaitin->icon;
    		$form->background = $Theloaitin->background;
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Theloaitin->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getTheloaitinTable()->saveMember($data);
	    			return $this->redirect()->toRoute('Theloaitin', array('action'=>'edit','id'=>$id));
	    			
    			}
    			 
    		}
    		return array('id'=>$id,'form'=>$form);
    
    	}
    }
    
    public function listAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$form = new TheloaitinForm();
    	$Theloaitin = $this->getTheloaitinTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$parent = $sm->get('Theloaitin\Model\TheloaitinTable')->fetchTheloaitin($dbAdapter);
    	$parent[0] = 'Cấp cha';
    	ksort($parent);
    	$form->get('parent')->setValueOptions($parent);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Theloaitin = $this->getTheloaitinTable()->fetchAll();
    		$Theloaitin = new Theloaitin();
    		$form->setInputFilter($Theloaitin->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			
    			$data = $form->getData();
    			$Theloaitin->exchangeArray($data);
    			$this->getTheloaitinTable()->saveMember($Theloaitin);
    			return $this->redirect()->toRoute('theloaitin', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'Theloaitin'=>$Theloaitin));
    }
    
    public function deleteAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	
    	$sm->get('Theloaitin\Model\TheloaitinTable')->deletenew($dbAdapter, $id);
    	
    	$this->getTheloaitinTable()->delete($id);
    	exit();
    }
    
   
    
    
}
