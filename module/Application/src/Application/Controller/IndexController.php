<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Albumanh;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$sm=$this->getServiceLocator();
    	$dbAdapter = $sm->get('db1');
    	$block = $sm->get('Application\Model\ApplicationTable')->getblock($dbAdapter);
        // San pham moi
    	$product_new = $sm->get('Application\Model\ApplicationTable')->getproductnew($dbAdapter);
    	$news = $sm->get('Application\Model\ApplicationTable')->baivietnoibat($dbAdapter);
    	$seopage = $sm->get('Application\Model\ApplicationTable')->getseo($dbAdapter);
    	// San pham noi bat 
        $product_selling = $sm->get('Application\Model\ApplicationTable')->getsanphamnoibat($dbAdapter);
    	
        $renderer = $this->getServiceLocator()->get(
    			'Zend\View\Renderer\PhpRenderer');
    	$renderer->headTitle($seopage['title']);
    	$renderer->headMeta()->appendName('keywords', $seopage['meta']);
    	$renderer->headMeta()->appendName('description', $seopage['keywork']);
    	$slide = $sm->get('Slide\Model\SlideTable')->getslide($dbAdapter, 10);
    	$slidevalue = array();
    	foreach ($slide as $slides)
    	{
    		$slidevalue[] = $sm->get('Slide\Model\SlideTable')->getslidevalue($dbAdapter, 10, $slides['id']);
    	
    	}
        return new ViewModel(array('block'=>$block,
            'new'=>$news, 
            'slide'=>$slide,
            'slidevalue'=>$slidevalue,
            'product_selling'=>$product_selling,
            'product_new'=>$product_new,
            ));
    }
}
