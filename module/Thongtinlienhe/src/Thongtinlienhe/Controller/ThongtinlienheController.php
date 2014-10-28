<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Thongtinlienhe\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Thongtinlienhe\Form\ThongtinlienheForm;
use Thongtinlienhe\Model\Thongtinlienhe;
use Zend\Authentication\AuthenticationService;

class ThongtinlienheController extends AbstractActionController
{
	protected $ThongtinlienheTable;
	public function getThongtinlienheTable()
	{
		if(!$this->ThongtinlienheTable)
		{
			$sm=$this->getServiceLocator();
			$this->ThongtinlienheTable=$sm->get('Thongtinlienhe\Model\ThongtinlienheTable');
		}
		return $this->ThongtinlienheTable;
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
    		return $this->redirect()->toRoute('thongtinlienhe',array('action'=>'add'));
    	}
    	else
    	{
    		$Thongtinlienhe = $this->getThongtinlienheTable()->getmemberID($id);
    		$form = new ThongtinlienheForm();
    		$form->bind($Thongtinlienhe);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Thongtinlienhe->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getThongtinlienheTable()->saveMember($data);
	    			return $this->redirect()->toRoute('thongtinlienhe', array('action'=>'edit','id'=>$id));
	    			
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
    	$form = new ThongtinlienheForm();
    	$Thongtinlienhe = $this->getThongtinlienheTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$Thongtinlienhe = new Thongtinlienhe();
    		$form->setInputFilter($Thongtinlienhe->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			
    			$data = $form->getData();
    			$Thongtinlienhe->exchangeArray($data);
    			$this->getThongtinlienheTable()->saveMember($Thongtinlienhe);
    			return $this->redirect()->toRoute('thongtinlienhe', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'Thongtinlienhe'=>$Thongtinlienhe));
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
    	$sm->get('Thongtinlienhe\Model\ThongtinlienheTable')->deletecontact($dbAdapter, $id);
    	$this->getThongtinlienheTable()->delete($id);
    	exit();
    }
    
   
    
    
}
