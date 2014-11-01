<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Lienhe\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Lienhe\Form\LienheForm;
use Lienhe\Model\Lienhe;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Giohang;
use Zend\Authentication\AuthenticationService;
class LienheController extends AbstractActionController
{
	public function getLienheTable()
	{
		if(!$this->LienheTable)
		{
			$sm=$this->getServiceLocator();
			$this->LienheTable=$sm->get('Lienhe\Model\LienheTable');
		}
		return $this->LienheTable;
	}
	protected $LienheTable;
	
	public function indexAction()
	{
		$request = $this->getRequest();
		
		$form = new LienheForm($this->getRequest()->getBaseUrl() . '/template/main/img/captcha/');
		$request = $this->getRequest();
		if($request->isPost())
		{
			$lienhe = new Lienhe();
			$form->setInputFilter($lienhe->getInputFilter());
			$form->setData($request->getPost());
			if($form->isValid())
			{
				$data = $form->getData();
				$lienhe->exchangeArray($data);
				$this->getLienheTable()->saveMember($lienhe);
				return $this->redirect()->toRoute('lienhe', array('action'=>'index'));
			}
		}
		
		$sm=$this->getServiceLocator();
		$dbAdapter = $sm->get('db1');
		$seopage = $sm->get('Lienhe\Model\LienheTable')->getseo($dbAdapter);
		 
		$renderer = $this->getServiceLocator()->get(
				'Zend\View\Renderer\PhpRenderer');
		$renderer->headTitle($seopage['title']);
		$renderer->headMeta()->appendName('keywords', $seopage['meta']);
		$renderer->headMeta()->appendName('description', $seopage['keywork']);
		return new ViewModel(array('form'=>$form,
				
		));
	}
	
	public function listAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity())
		{
			return $this->redirect()->toRoute('member',array('action'=>'login'));
		}
		$this->layout()->setTemplate('layout/layoutadmin');
		$lienhe = $this->getLienheTable()->fetchAll();
		
		return new ViewModel(array('lienhe'=>$lienhe));
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
		$lienhe = $this->getLienheTable()->getmemberID($id);
		return new ViewModel(array('lienhe'=>$lienhe));
	}

	public function deleteAction() {
		$auth = new AuthenticationService();
    	if(!$auth->hasIdentity())
    	{
    		return $this->redirect()->toRoute('member',array('action'=>'login'));
    	}
    	$id = (int) $this->params()->fromRoute('id', 0);
    	$this->getLienheTable()->delete($id);
	}
}
