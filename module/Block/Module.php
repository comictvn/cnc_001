<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Block;

use Block\Model\Block;
use Block\Model\BlockTable;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Block\View\Helper\Menus as menus;
use Block\Model\Menu;
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
	public function getServiceConfig()
    {
       return array(
             'factories'=>array(
                   'Block\Model\BlockTable'=>function($sm)
                   {
                      $tableGateway=$sm->get('BlockTableGateway');
                      $table=new BlockTable($tableGateway);
                      return $table;
                   },
                   'BlockTableGateway'=>function($sm)
                   {
                      $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
                      $resultSetPrototype=new ResultSet();
                      $resultSetPrototype->setArrayObjectPrototype(new Block());
                      return new TableGateway('block',$dbAdapter,null,$resultSetPrototype);
                   },
                   'Block\Model\Menu' =>  function($sm) {
                      $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                      $table = new Menu($dbAdapter);
                      return $table;
                   },
             ),
       );
    }
    
	public function getViewHelperConfig(){
        return array(
            'factories' => array(
              'newhome' => function($sm) {
                    $helper = new menus();
                    $helper->setTable($sm->getServiceLocator(),'Block\Model\Menu');
                    return $helper;
                },
               'productnew' => function($sm) {
                    $helper = new menus();
                    $helper->productnew($sm->getServiceLocator(),'Block\Model\Menu');
                    return $helper;
                },
                'productnewvalue' => function($sm) {
                	$helper = new menus();
                	$helper->productnewvalue($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
               	// THÔNG TIN CƠ BẢN WEBSITE
                'intro' => function($sm) {
                	$helper = new menus();
                	$helper->intro($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
                // TỐI ƯU HÓA WEBSITE
                'seopage' => function($sm) {
                	$helper = new menus();
                	$helper->seopage($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
                // DANH MỤC SẢN PHẨM
                'catepro' => function($sm) {
                	$helper = new menus();
                	$helper->catepro($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
                // MENU DICH VU
                'cateservice' => function($sm) {
                	$helper = new menus();
                	$helper->getcateservice($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
                // MENU GIOI THIEU
                'gioithieu' => function($sm) {
                	$helper = new menus();
                	$helper->getgioithieu($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
                // MENU TIN TUC
                'catetintuc' => function($sm) {
                  $helper = new menus();
                  $helper->getcatetintuc($sm->getServiceLocator(),'Block\Model\Menu');
                  return $helper;
                },
                // SLIDE
                'slide' => function($sm, $idblock) {
                	$helper = new menus();
                	$helper->slide($sm->getServiceLocator(),'Block\Model\Menu', $idblock);
                	return $helper;
                },
                // BLOCK
                'block' => function($sm, $idblock) {
                	$helper = new menus();
                	$helper->block($sm->getServiceLocator(),'Block\Model\Menu');
                	return $helper;
                },
                
            )
        );
    }
}
