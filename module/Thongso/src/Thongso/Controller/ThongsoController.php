<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Thongso\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Thongso\Form\ThongsoForm;
use Thongso\Model\Thongso;
use Zend\Authentication\AuthenticationService;

class ThongsoController extends AbstractActionController
{
	protected $ThongsoTable;
	public function getThongsoTable()
	{
		if(!$this->ThongsoTable)
		{
			$sm=$this->getServiceLocator();
			$this->ThongsoTable=$sm->get('Thongso\Model\ThongsoTable');
		}
		return $this->ThongsoTable;
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
    		return $this->redirect()->toRoute('Thongso',array('action'=>'add'));
    	}
    	else
    	{
    		$Thongso = $this->getThongsoTable()->getmemberID($id);
    		$form = new ThongsoForm();
    		$form->bind($Thongso);
    		$sm=$this->getServiceLocator();
    		$dbAdapter = $sm->get('db1');
    		$parent = $sm->get('Thongso\Model\ThongsoTable')->fetchthongso($dbAdapter);
    		 
    		 
    		$cate = $sm->get('Thongso\Model\ThongsoTable')->fetchDanhmuc($dbAdapter);
    		$parent[0] = 'Cấp cha';
    		ksort($parent);
    		$form->get('parents')->setValueOptions($parent);
    		$form->get('category')->setValueOptions($cate);
    		 
    		$parent1 = $sm->get('Thongso\Model\ThongsoTable')->fetchthongso1($dbAdapter);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Thongso->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getThongsoTable()->saveMember($data);
	    			return $this->redirect()->toRoute('thongso', array('action'=>'edit','id'=>$id));
	    			
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
    	$form = new ThongsoForm();
    	$Thongso = $this->getThongsoTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$parent = $sm->get('Thongso\Model\ThongsoTable')->fetchthongso($dbAdapter);
    	
    	
    	$cate = $sm->get('Thongso\Model\ThongsoTable')->fetchDanhmuc($dbAdapter);
    	$parent[0] = 'Cấp cha';
    	ksort($parent);
    	$form->get('parents')->setValueOptions($parent);
    	$form->get('category')->setValueOptions($cate);
    	
    	$parent1 = $sm->get('Thongso\Model\ThongsoTable')->fetchthongso1($dbAdapter);
    	//print_r($parent1); exit();
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		
    		$Thongso = new Thongso();
    		$form->setInputFilter($Thongso->getInputFilter());
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			$data = $form->getData();
    			$Thongso->exchangeArray($data);
    			$this->getThongsoTable()->saveMember($Thongso);
    			return $this->redirect()->toRoute('thongso', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'thongso'=>$Thongso, 'cate'=>$cate, 'parent'=>$parent, 'parent1'=>$parent1));
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
    	$sm->get('Thongso\Model\ThongsoTable')->deletecontact($dbAdapter, $id);
    	$this->getThongsoTable()->delete($id);
    	exit();
    }
    
   
    
    
}
