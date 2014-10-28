<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Danhmuc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Danhmuc\Form\DanhmucForm;
use Danhmuc\Model\Danhmuc;
use Zend\Authentication\AuthenticationService;

class DanhmucController extends AbstractActionController
{
	protected $DanhmucTable;
	public function getDanhmucTable()
	{
		if(!$this->DanhmucTable)
		{
			$sm=$this->getServiceLocator();
			$this->DanhmucTable=$sm->get('Danhmuc\Model\DanhmucTable');
		}
		return $this->DanhmucTable;
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
    	$form = new DanhmucForm();
    	
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Danhmuc = new Danhmuc();
    		$form->setInputFilter($Danhmuc->getInputFilter());
    		$form->setData($request->getPost());
    	 
    		if($form->isValid())
    		{
    		
    					$data = $form->getData();
    					$Danhmuc->exchangeArray($data);
    					$this->getDanhmucTable()->saveMember($Danhmuc);
    					return $this->redirect()->toRoute('Danhmuc', array('action'=>'list'));
    				
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
    		return $this->redirect()->toRoute('Danhmuc',array('action'=>'add'));
    	}
    	else
    	{
    		$Danhmuc = $this->getDanhmucTable()->getmemberID($id);
    		$form = new DanhmucForm();
    		$sm=$this->getServiceLocator();
    		$dbAdapter = $sm->get('db1');
    		$parent = $sm->get('Danhmuc\Model\DanhmucTable')->fetchDanhmuc($dbAdapter);
    		$parent[0] = 'Cấp cha';
    		ksort($parent);
    		$form->get('parent')->setValueOptions($parent);
    		$form->bind($Danhmuc);
    		$form->image = $Danhmuc->image;
    		$form->icon = $Danhmuc->icon;
    		$form->background = $Danhmuc->background;
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Danhmuc->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getDanhmucTable()->saveMember($data);
	    			return $this->redirect()->toRoute('danhmuc', array('action'=>'edit','id'=>$id));
	    			
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
    	$form = new DanhmucForm();
    	$Danhmuc = $this->getDanhmucTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$parent = $sm->get('Danhmuc\Model\DanhmucTable')->fetchDanhmuc($dbAdapter);
    	$parent[0] = 'Cấp cha';
    	ksort($parent);
    	$form->get('parent')->setValueOptions($parent);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Danhmuc = $this->getDanhmucTable()->fetchAll();
    		$Danhmuc = new Danhmuc();
    		$form->setInputFilter($Danhmuc->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			
    			$data = $form->getData();
    			$Danhmuc->exchangeArray($data);
    			$this->getDanhmucTable()->saveMember($Danhmuc);
    			return $this->redirect()->toRoute('danhmucsanpham', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'Danhmuc'=>$Danhmuc));
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
    	$cate = $sm->get('Danhmuc\Model\DanhmucTable')->getcateid($dbAdapter, $id);
    	foreach ($cate as $cates)
    	{
    		$sm->get('Danhmuc\Model\DanhmucTable')->deleteproduct($dbAdapter, $cates['idpro']);
    	}
    	$sm->get('Danhmuc\Model\DanhmucTable')->deletecate($dbAdapter, $id);
    	$this->getDanhmucTable()->delete($id);
    	exit();
    }
    
   
    
    
}
