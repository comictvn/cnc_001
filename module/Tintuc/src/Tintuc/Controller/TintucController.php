<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tintuc\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tintuc\Form\TintucForm;
use Tintuc\Model\Tintuc;
use Zend\Authentication\AuthenticationService;
use Zend\Feed\Reader as feed;

class TintucController extends AbstractActionController
{
	protected $TintucTable;
	public function getTintucTable()
	{
		if(!$this->TintucTable)
		{
			$sm=$this->getServiceLocator();
			$this->TintucTable=$sm->get('Tintuc\Model\TintucTable');
		}
		return $this->TintucTable;
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
    	$form = new TintucForm();
    	
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Tintuc = new Tintuc();
    		$form->setInputFilter($Tintuc->getInputFilter());
    		$form->setData($request->getPost());
    	 
    		if($form->isValid())
    		{
    		
    					$data = $form->getData();
    					$Tintuc->exchangeArray($data);
    					$this->getTintucTable()->saveMember($Tintuc);
    					return $this->redirect()->toRoute('Tintuc', array('action'=>'list'));
    				
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
    		return $this->redirect()->toRoute('Tintuc',array('action'=>'add'));
    	}
    	else
    	{
    		$Tintuc = $this->getTintucTable()->getmemberID($id);
    		
    		$description = $this->use_lang_edit($Tintuc->description);
    		
    		$form = new TintucForm();
    		$sm=$this->getServiceLocator();
    		$dbAdapter = $sm->get('db1');
    		$parent = $sm->get('Tintuc\Model\TintucTable')->fetchdanhmuc($dbAdapter);
    		$parent[0] = 'Chọn danh mục';
    		ksort($parent);
    		$form->get('category')->setValueOptions($parent);
    		$form->bind($Tintuc);
    		$form->image = $Tintuc->image;
    		$request = $this->getRequest();
    		if($request->isPost())
    		{
    			$form->setInputFilter($Tintuc->getInputFilter());
    			$form->setData($request->getPost());
    		 
    			if($form->isValid())
    			{
	    			$data = $form->getData();
	    			$this->getTintucTable()->saveMember($data);
	    			return $this->redirect()->toRoute('tintuc', array('action'=>'edit','id'=>$id));
	    			
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
    	$form = new TintucForm();
    	$Tintuc = $this->getTintucTable()->fetchAll();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$parent = $sm->get('Tintuc\Model\TintucTable')->fetchdanhmuc($dbAdapter);
    	$parent[0] = 'Chọn danh mục';
    	ksort($parent);
    	$form->get('category')->setValueOptions($parent);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$Tintuc = $this->getTintucTable()->fetchAll();
    		$Tintuc = new Tintuc();
    		
    		
    		$form->setInputFilter($Tintuc->getInputFilter());
    		
    		$form->setData($request->getPost());
    		
    		if($form->isValid())
    		{
    			
    			$data = $form->getData();
    			
    			$Tintuc->exchangeArray($data);
    			$this->getTintucTable()->saveMember($Tintuc);
    			return $this->redirect()->toRoute('tintuc', array('action'=>'list'));
    	
    		}
    	}
    	return new ViewModel(array('form'=>$form, 'Tintuc'=>$Tintuc,'cate'=>$parent));
    }
    
    public function rssAction()
    {
    
	    
		try{
		
			$rss = feed\Reader::import('http://vnexpress.net/rss/so-hoa.rss');
		}catch (feed\Exception\RuntimeException $e){
			echo "error : " . $e->getMessage();
			exit;
		}
		
		$channel = array(
				'title'       => $rss->getTitle(),
				'description' => $rss->getDescription(),
				'link'        => $rss->getLink(),
				'items'       => array()
		);
		count($channel); exit();
	
		exit();
	        return new  ViewModel(array(
	            'channel' => $channel
	        ));
	    }
	public function indexAction()
	{
		$page = (int) $this->params()->fromRoute('id', 0);
		$Tintuc = $this->getTintucTable()->fetchAll();
		$tincu = $this->getTintucTable()->tincu();
		$sm=$this->getServiceLocator();
		$dbAdapter = $sm->get('db1');
		$seopage = $sm->get('Tintuc\Model\TintucTable')->getseo($dbAdapter);
		$catenew = $sm->get('Tintuc\Model\TintucTable')->fetchTheloaitin_tuc($dbAdapter);
		$tinnong = $sm->get('Tintuc\Model\TintucTable')->tinnong($dbAdapter);
		foreach ($catenew as $catenews) {
			
			$tin[] = $this->getTintucTable()->fetchAllnewcate($catenews['id']);
			
		} //print_r($tin); exit();
		$renderer = $this->getServiceLocator()->get(
				'Zend\View\Renderer\PhpRenderer');
		$renderer->headTitle($seopage['title']);
		$renderer->headMeta()->appendName('keywords', $seopage['meta']);
		$renderer->headMeta()->appendName('description', $seopage['keywork']);
		$duan = $this->getTintucTable()->duanthuchien();
		$block = $sm->get('Tintuc\Model\TintucTable')->fetchblock($dbAdapter);
		$intro = $this->getTintucTable()->tintuc();
		return new ViewModel(array('tincu'=>$tincu,'catenew'=>$catenew,
				'tinnong'=>$tinnong,'duan'=>$duan, 'block'=>$block, 'intro'=>$intro, 'title' => $seopage['title']));
	}
	
	public function detailAction()
	{
		$alias = $this->params()->fromRoute('alias');
		$tintuc = $this->getTintucTable()->getdetail($alias);
		$tintuclienquan = $this->getTintucTable()->fetchtinlienquan($tintuc->category);
		if(isset($_GET['lang']))
		{
			$title = $tintuc->title_en;
		} else {
			$title = $tintuc->title; 
		}
		
		$renderer = $this->getServiceLocator()->get(
				'Zend\View\Renderer\PhpRenderer');
		$renderer->headTitle($title);
		$renderer->headMeta()->appendName('description', $tintuc->meta);
		$renderer->headMeta()->appendName('keywords', $tintuc->keyword);
		$sm=$this->getServiceLocator();
		$dbAdapter = $sm->get('db1');
		$duan = $this->getTintucTable()->duanthuchien();
		$block = $sm->get('Tintuc\Model\TintucTable')->fetchblock($dbAdapter);
		
		return new ViewModel(array('result'=>$tintuc,
				'block'=>$block,
				'tinlienquan'=>$tintuclienquan,
				'duan'=>$duan, 'title' => $title));
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
    	
    	$this->getTintucTable()->delete($id);
    	exit();
    }
    
    public function gioithieuAction()
    {
    	$page = (int) $this->params()->fromRoute('id', 0);
    	$gioithieu = $this->getTintucTable()->gioithieu();
    	$iteratorAdapter = new \Zend\Paginator\Adapter\Iterator($gioithieu);
    	$paginator = new \Zend\Paginator\Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(5);
    	$intro = $this->getTintucTable()->tingioithieu();
    	$duan = $this->getTintucTable()->duanthuchien();
    	if(isset($_GET['lang']))
    	{
    		$title = $intro->title_en;
    	} else {
    		$title = $intro->title;
    	}
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($title);
    	$renderer->headMeta()->appendName('description', $intro->meta);
    	$renderer->headMeta()->appendName('keywords', $intro->keyword);
    	$block = $sm->get('Tintuc\Model\TintucTable')->fetchblock($dbAdapter);
    	return new ViewModel(array('gioithieu'=>$paginator,'duan'=>$duan, 'intro'=>$intro,'block'=>$block, 'title' => $title));
    }

    public function dichvuAction()
    {
    	$gioithieu = $this->getTintucTable()->gioithieu();
    	$intro = $this->getTintucTable()->tindichvu();

    	$duan = $this->getTintucTable()->duanthuchien();
    	if(isset($_GET['lang']))
    	{
    		$title = $intro->title_en;
    	} else {
    		$title = $intro->title;
    	}
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($title);
    	$renderer->headMeta()->appendName('description', $intro->meta);
    	$renderer->headMeta()->appendName('keywords', $intro->keyword);
    	
    	$block = $sm->get('Tintuc\Model\TintucTable')->fetchblock($dbAdapter);
    	return new ViewModel(array('gioithieu'=>$gioithieu,'duan'=>$duan, 'intro'=>$intro,'block'=>$block, 'title' => $title));
    }
    
    public function danhmucAction()
    {
    	$id = $this->params()->fromRoute('alias', 0);
    	$page = $this->params()->fromRoute('id', 0);
    	$new = $this->getTintucTable()->getdanhmuc($id);
    	$iteratorAdapter = new \Zend\Paginator\Adapter\Iterator($new);
    	$paginator = new \Zend\Paginator\Paginator($iteratorAdapter);
    	$paginator->setCurrentPageNumber($page);
    	$paginator->setItemCountPerPage(5);
    	$duan = $this->getTintucTable()->duanthuchien();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$block = $sm->get('Tintuc\Model\TintucTable')->fetchblock($dbAdapter);
    	$seo = $sm->get('Tintuc\Model\TintucTable')->getseocate($dbAdapter,$id);
    	$renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($seo['seotitle']);
    	$renderer->headMeta()->appendName('keywords', $seo['keyword']);
    	$renderer->headMeta()->appendName('description', $seo['meta']);
    	return new ViewModel(array('new'=>$paginator,'duan'=>$duan,'block'=>$block,'alias'=>$id, 'title' => $seo['seotitle']));
    }
    
    public function use_lang($lang_t, $text)
    {
    	$post_title = $text;
    	$regexp = '/<\!--:(\w+?)-->([^<]+?)<\!--:-->/i';
    	if(preg_match_all($regexp, $post_title, $matches))
    	{
    	
    	
    		$lang = $lang_t ;
    		foreach ($matches[1] as $key => $value) {
    			if($lang == $value) 
    			{
    				$key_l = $key;
    			}
    		}
    		
    		$split_regex = "#(<!--[^-]*-->|\[:[a-z]{2}\])#ism";
    		$blocks = preg_split($split_regex, $matches[0][$key_l], -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    		return $blocks[1];
    		
    	}
    }
    
    public function use_lang_edit($text)
    {
    	$post_title = $text;
    	$regexp = '/<\!--:(\w+?)-->([^<]+?)<\!--:-->/i';
    	$blocks = array();
    	if(preg_match_all($regexp, $post_title, $matches))
    	{
    		 
    		 
    		$lang = array();
    		$lang_text = array();
    		foreach ($matches[1] as $key => $value) {
    			$lang[$key] = $value ;
    		}
    		foreach ($matches[0] as $key => $value_text)
    		{
    			$lang_text[$key] = $value_text ;
    		}
    		$split_regex = "#(<!--[^-]*-->|\[:[a-z]{2}\])#ism";
    		
    		foreach ($lang_text as $key => $value)
    		{
    			$blocks[$lang[$key]] = preg_split($split_regex, $value, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
    		}
    		
    		return $blocks;
    		
    
    		
    
    	}
    }
    
    public function timkiemAction()
    {
    	$id = $_GET['search'];
    	$timkiem = $this->getTintucTable()->timkiem($id); 
    	$intro = $this->getTintucTable()->tindichvu();
    	$duan = $this->getTintucTable()->duanthuchien();
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$block = $sm->get('Tintuc\Model\TintucTable')->fetchblock($dbAdapter);
    	return new ViewModel(array('timkiem'=>$timkiem,'duan'=>$duan, 'intro'=>$intro,'block'=>$block));
    }
   
    
    
}