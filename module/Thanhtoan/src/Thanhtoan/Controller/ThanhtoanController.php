<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Thanhtoan\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Thanhtoan\Form\ThanhtoanForm;
use Thanhtoan\Model\Thanhtoan;
use Zend\Authentication\AuthenticationService;

class ThanhtoanController extends AbstractActionController
{
	protected $ThanhtoanTable;
	public function getThanhtoanTable()
	{
		if(!$this->ThanhtoanTable)
		{
			$sm=$this->getServiceLocator();
			$this->ThanhtoanTable=$sm->get('Thanhtoan\Model\ThanhtoanTable');
		}
		return $this->ThanhtoanTable;
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
    		return $this->redirect()->toRoute('Thanhtoan',array('action'=>'add'));
    	}
    	else
    	{
    		$Thanhtoan = $this->getThanhtoanTable()->getmemberID($id);
    		$form = new ThanhtoanForm();
    		$form->bind($Thanhtoan);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Thanhtoan->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getThanhtoanTable()->saveMember($data);
	    			return $this->redirect()->toRoute('Thanhtoan', array('action'=>'edit','id'=>$id));
	    			
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
 
    	$Thanhtoan = $this->getThanhtoanTable()->getmemberID(1);
    	$form = new ThanhtoanForm();
    	$form->bind($Thanhtoan);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$bank = $sm->get('Thanhtoan\Model\ThanhtoanTable')->getbank($dbAdapter);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$Thanhtoan = new Thanhtoan();
    		$form->setInputFilter($Thanhtoan->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			$data = $form->getData();
    			$this->getThanhtoanTable()->saveMember($data);
    			$bank = $sm->get('Thanhtoan\Model\ThanhtoanTable')->getbank($dbAdapter);
    			foreach ($bank as $banks){
    				$data_bank['id'] = $banks['id'];
    				$data_bank['namebank'] = $_POST['namebank'.$banks['id']];
    				$data_bank['address'] = $_POST['address'.$banks['id']];
    				$data_bank['person'] = $_POST['person'.$banks['id']];
    				$data_bank['numberaccount'] = $_POST['numberaccount'.$banks['id']];
    				$data_bank['cmnd'] = $_POST['cmnd'.$banks['id']];
    				$data_bank['phone'] = $_POST['phone'.$banks['id']];
    				$sm->get('Thanhtoan\Model\ThanhtoanTable')->updatebank($dbAdapter, $data_bank);
    			}
    			return $this->redirect()->toRoute('thanhtoan', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'bank'=>$bank));
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
    	$sm->get('Thanhtoan\Model\ThanhtoanTable')->deletecontact($dbAdapter, $id);
    	$this->getThanhtoanTable()->delete($id);
    	exit();
    }
    
   
    
    
}
