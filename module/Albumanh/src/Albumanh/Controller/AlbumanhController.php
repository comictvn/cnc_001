<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Albumanh\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Albumanh\Form\AlbumanhForm;
use Albumanh\Model\Albumanh;
use Zend\Authentication\AuthenticationService;

class AlbumanhController extends AbstractActionController
{
	protected $AlbumanhTable;
	public function getAlbumanhTable()
	{
		if(!$this->AlbumanhTable)
		{
			$sm=$this->getServiceLocator();
			$this->AlbumanhTable=$sm->get('Albumanh\Model\AlbumanhTable');
		}
		return $this->AlbumanhTable;
	}
	
	public function addAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity())
		{
			return $this->redirect()->toRoute('member',array('action'=>'login'));
		}
		$this->layout()->setTemplate("layout/layoutedit");
		
		$form = new AlbumanhForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			$Albumanh = new Albumanh();
			$form->setInputFilter($Albumanh->getInputFilter());
			$form->setData($request->getPost());
	
			if($form->isValid())
			{
	
				$data = $form->getData();
				$Albumanh->exchangeArray($data);
				$this->getAlbumanhTable()->saveMember($Albumanh);
				return $this->redirect()->toRoute('Albumanh', array('action'=>'list'));
	
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
    		return $this->redirect()->toRoute('albumanh',array('action'=>'add'));
    	}
    	else
    	{
    		$Albumanh = $this->getAlbumanhTable()->getmemberID($id);
    		$form = new AlbumanhForm();
    		$form->bind($Albumanh);
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Albumanh->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getAlbumanhTable()->saveMember($data);
	    			return $this->redirect()->toRoute('albumanh', array('action'=>'edit','id'=>$id));
	    			
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
    	$form = new AlbumanhForm();
    	$Albumanh = $this->getAlbumanhTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	
    	foreach ($Albumanh as $Albumanhs){
    		$count = $sm->get('Albumanh\Model\AlbumanhTable')->countitem($dbAdapter, $Albumanhs->id);
    		$data_count[$Albumanhs->id] = $count;
    	}
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Albumanh = new Albumanh();
    		$form->setInputFilter($Albumanh->getInputFilter());
    		$form->setData($request->getPost());
    	
    		if($form->isValid())
    		{
    	
    			$data = $form->getData();
    			$Albumanh->exchangeArray($data);
    			$this->getAlbumanhTable()->saveMember($Albumanh);
    			return $this->redirect()->toRoute('albumanh', array('action'=>'list'));
    	
    		}
    	}
    	
    	return new ViewModel(array('form'=>$form,'Albumanh'=>$Albumanh,'count'=>$data_count));
    }
    
    public function itemAction()
    {
    	$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$this->layout()->setTemplate('layout/layoutadmin');
    	$id = (int)$this->params()->fromRoute('id');
    	
    	$form = new AlbumanhForm();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$anh = $sm->get('Albumanh\Model\AlbumanhTable')->getAlbumanhitem($dbAdapter, $id);
    	$Albumanh = $this->getAlbumanhTable()->getmemberID($id);
        $request = $this->getRequest();
    	if ($request->isPost()) {
    	    $data['album'] = $id;
    		$sm->get('Albumanh\Model\AlbumanhTable')->deleteimagealbum($dbAdapter, $data);

            if(isset($_POST['imageshow'])) {
                foreach ($_POST['imageshow'] as $image)
                {
                    $data['link'] = $image ;
                    $sm->get('Albumanh\Model\AlbumanhTable')->insertimage($dbAdapter, $data);
                }
            }
    		
    		
    		return $this->redirect()->toRoute('albumanh', array('action'=>'item','id'=>$id));
    	}
    	return new ViewModel(array('form'=>$form, 'id'=>$id ,'Albumanh'=>$anh,'album'=>$Albumanh));
    }
    
    public function indexAction()
    {
    	$page = (int) $this->params()->fromRoute('id', 0);
    	$album = $this->getAlbumanhTable()->fetchAll();
    	$iteratorAdapter = new \Zend\Paginator\Adapter\Iterator($album);
    	$paginator = new \Zend\Paginator\Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(12);
    	return new ViewModel(array('result'=>$paginator));
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
    	$sm->get('Albumanh\Model\AlbumanhTable')->deleteimage($dbAdapter, $id);
    	$this->getAlbumanhTable()->delete($id);
    	exit();
    }
    
   
    
    
}
