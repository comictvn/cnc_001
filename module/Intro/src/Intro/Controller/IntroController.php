<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Intro\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Intro\Form\IntroForm;
use Intro\Model\Intro;
use Zend\Authentication\AuthenticationService;

class IntroController extends AbstractActionController
{
	protected $IntroTable;
	public function getIntroTable()
	{
		if(!$this->IntroTable)
		{
			$sm=$this->getServiceLocator();
			$this->IntroTable=$sm->get('Intro\Model\IntroTable');
		}
		return $this->IntroTable;
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
    		return $this->redirect()->toRoute('Intro',array('action'=>'add'));
    	}
    	else
    	{
    		$Intro = $this->getIntroTable()->getmemberID($id);
    		$form = new IntroForm();
    		$form->bind($Intro);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Intro->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getIntroTable()->saveMember($data);
	    			return $this->redirect()->toRoute('Intro', array('action'=>'edit','id'=>$id));
	    			
    			}
    			 
    		}
    		return array('id'=>$id,'form'=>$form);
    
    	}
    }
    
    public function basicAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$form = new IntroForm();
    	$Intro = $this->getIntroTable()->getmemberID(1);
    	$form->bind($Intro);
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$Intro = new Intro();
    		$form->setInputFilter($Intro->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			$data = $form->getData();
    			$this->getIntroTable()->saveMember($data);
    			return $this->redirect()->toRoute('intro', array('action'=>'basic'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'Intro'=>$Intro));
    }
    
    public function seoAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$form = new IntroForm();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$seo = $sm->get('Intro\Model\IntroTable')->getseo($dbAdapter);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		for ($i=0; $i < count($_POST) + 1; $i++){
    			$data['id'] = $_POST['id'][$i];
    			$data['page'] = $_POST['page'][$i];
    			$data['title'] = $_POST['title'][$i];
    			$data['keywork'] = $_POST['tag'][$i];
    			$data['meta'] = $_POST['meta'][$i];
    			$sm->get('Intro\Model\IntroTable')->updateseo($dbAdapter, $data);
    			
    		}
    		return $this->redirect()->toRoute('intro', array('action'=>'seo'));
    	}
    	return new ViewModel(array('form'=>$form,'seo'=>$seo));
    }
    
    public function googlewebmasterAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
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
    	$sm->get('Intro\Model\IntroTable')->deletecontact($dbAdapter, $id);
    	$this->getIntroTable()->delete($id);
    	exit();
    }
    
   
    
    
}
