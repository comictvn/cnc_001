<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Menuitem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Menuitem\Form\MenuitemForm;
use Menuitem\Model\Menuitem;
use Zend\Authentication\AuthenticationService;

class MenuitemController extends AbstractActionController
{
	protected $MenuitemTable;
	public function getMenuitemTable()
	{
		if(!$this->MenuitemTable)
		{
			$sm=$this->getServiceLocator();
			$this->MenuitemTable=$sm->get('Menuitem\Model\MenuitemTable');
		}
		return $this->MenuitemTable;
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
    		return $this->redirect()->toRoute('Menuitem',array('action'=>'add'));
    	}
    	else
    	{
    		$Menuitem = $this->getMenuitemTable()->getmemberID($id);
    		$form = new MenuitemForm();
    		$form->bind($Menuitem);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Menuitem->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getMenuitemTable()->saveMember($data);
	    			return $this->redirect()->toRoute('Menuitem', array('action'=>'edit','id'=>$id));
	    			
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
    	$id = (int)$this->params()->fromRoute('id',0);
    	$form = new MenuitemForm();
    	$Menuitem = $this->getMenuitemTable()->fetchAll($id);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$Menuitem = new Menuitem();
    		$form->setInputFilter($Menuitem->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			
    			$data = $form->getData();
    			$Menuitem->exchangeArray($data);
    			$this->getMenuitemTable()->saveMember($Menuitem);
    			return $this->redirect()->toRoute('menuitem', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'Menuitem'=>$Menuitem));
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
    	$sm->get('Menuitem\Model\MenuitemTable')->deletecontact($dbAdapter, $id);
    	$this->getMenuitemTable()->delete($id);
    	exit();
    }
    
   
    
    
}
