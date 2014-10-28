<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Menu\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Menu\Form\MenuForm;
use Menu\Model\Menu;
use Zend\Authentication\AuthenticationService;

class MenuController extends AbstractActionController
{
	protected $MenuTable;
	public function getMenuTable()
	{
		if(!$this->MenuTable)
		{
			$sm=$this->getServiceLocator();
			$this->MenuTable=$sm->get('Menu\Model\MenuTable');
		}
		return $this->MenuTable;
	}
	
	public function addAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity())
		{
			return $this->redirect()->toRoute('member',array('action'=>'login'));
		}
		$this->layout()->setTemplate("layout/layoutedit");
		
		$form = new MenuForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			$Menu = new Menu();
			$form->setInputFilter($Menu->getInputFilter());
			$form->setData($request->getPost());
	
			if($form->isValid())
			{
	
				$data = $form->getData();
				$Menu->exchangeArray($data);
				$this->getMenuTable()->saveMember($Menu);
				return $this->redirect()->toRoute('menu', array('action'=>'list'));
	
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
    	$datamenu = new Menu();
    	$Menu = $this->getMenuTable()->getmemberID($id);
    		
	    	$data['id'] = $Menu->id  ;
		    $data['name'] = $_POST['name'] ;
		    $data['alias'] = $_POST['alias'] ;
		    $data['description'] = $Menu->description ;
		    $data['active'] = $_POST['active'] ;
		    $datamenu->exchangeArray($data);
		    $this->getMenuTable()->saveMember($datamenu);
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
    	$form = new MenuForm();
    	$Menu = $this->getMenuTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	
    	foreach ($Menu as $Menus){
    		$count = $sm->get('Menu\Model\MenuTable')->countitem($dbAdapter, $Menus->id);
    		$data_count[$Menus->id] = $count;
    	}
    	
    	return new ViewModel(array('Menu'=>$Menu,'count'=>$data_count));
    }
    
    public function itemAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$id = (int)$this->params()->fromRoute('id',0);
    	$sm = $this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$Menu = $sm->get('Menu\Model\MenuTable')->getmenuitem($dbAdapter, $id);
  
    	return new ViewModel(array('menu'=>$Menu));
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
    	$sm->get('Menu\Model\MenuTable')->deletecontact($dbAdapter, $id);
    	$this->getMenuTable()->delete($id);
    	exit();
    }
    
   
    
    
}
