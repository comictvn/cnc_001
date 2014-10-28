<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Member\Form\MemberForm;
use Member\Model\Member;
use Member\Model\Login;
use Member\Form\LoginForm;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Result as Result;


class MemberController extends AbstractActionController
{
	protected $MemberTable;
	public function getMemberTable()
	{
		if(!$this->MemberTable)
		{
			$sm=$this->getServiceLocator();
			$this->MemberTable=$sm->get('Member\Model\MemberTable');
		}
		return $this->MemberTable;
	}
	

	public function indexAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	
    	$this->layout()->setTemplate("layout/layoutadmin");
    	$Member = $this->getMemberTable()->fetchAll();
    	
    	return new ViewModel(array('khachhang'=>$Member));
    }
    
    public function addAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$form = new MemberForm();
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Member = new Member();
    		$form->setInputFilter($Member->getInputFilter());
    		$form->setData($request->getPost());
    
    		if($form->isValid())
    		{
    
    			$data = $form->getData();
    			$Member->exchangeArray($data);
    			$this->getMemberTable()->saveMember($Member);
    			return $this->redirect()->toRoute('member');
    
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
    	$this->layout()->setTemplate("layout/layoutedit");
    	$id = (int)$this->params()->fromRoute('id',0);
    	if(!$id)
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'add'));
    	}
    	else
    	{
    		$Member = $this->getMemberTable()->getmemberID($id);
    		$form = new MemberForm();
    		$form->bind($Member);
    
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Member->getInputFilter());
    			$form->setData($request->getPost());
    
    			if($form->isValid())
    			{
    				$data = $form->getData();
    				
    				$this->getMemberTable()->saveMember($data);
    				return $this->redirect()->toRoute('member', array('action'=>'index'));
    
    			}
    
    		}
    		return array('id'=>$id,'form'=>$form);
    
    	}
    }
    
    public function loginAction()
    {
    	$this->layout()->setTemplate('layout/login');
    	$form = new LoginForm();
    	$request = $this->getRequest();
    	if($request->isPost())
    	{
    		$login = new Login();
    		$form->setInputFilter($login->getInputFilter());
    		$form->setData($request->getPost());
    		if($form->isValid())
    		{
    			$post = $request->getPost();
    			 
    			$sm = $this->getServiceLocator();
    			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    			 
    			// create auth adapter
    			 
    			$authAdapter = new AuthAdapter($dbAdapter, 'user', 'username' , 'password');
    			$authAdapter->setIdentity($post->get('username'))->setCredential($post->get('password'));
    			$authService = new AuthenticationService();
    			$authService->setAdapter($authAdapter);
    			 
    			//authenticate
    			$result = $authService->authenticate();
    			if($result->isValid())
    			{
    				$storage = $authService->getStorage();
    				$storage->write($authAdapter->getResultRowObject(array('username','fullname' ,'id' , 'role')));
    				$indentity = $authService->getIdentity();
    				$_SESSION['ten_thanh_vien'] = $indentity->fullname;
    				$_SESSION['role'] = $indentity->role;
    				$_SESSION['id'] = $indentity->id;
    				return $this->redirect()->toRoute('member', array('action'=>'index'));
    			}
    		}
    	}
    	return new ViewModel(array('form'=>$form));
    }
    
    public function logoutAction()
    {
    	$auth = new AuthenticationService();
    	$auth->clearIdentity();
    	$_SESSION['ten_thanh_vien'] = Null;
    	return $this->redirect()->toRoute('member', array('action'=>'login'));
    }
    
	public function profileAction()
    {
    	
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    		$donhang = $this->getDonhangTable()->fecthhoatdong($_SESSION['id']);
    		$Member = $this->getMemberTable()->getmemberID($_SESSION['id']);
    		return array('member'=>$Member,'donhang'=> $donhang);
    
    	
    }
    public function deleteAction()
    {
    	 
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	 
    	if($_SESSION['role'] == 'admin')
    	{
    		$id = (int) $this->params()->fromRoute('id', 0);
    		$donhang = $this->getMemberTable()->delete($id);
    		return $this->redirect()->toRoute('member', array('action'=>'index'));
    	}
    	return new ViewModel();
    }
    
}
