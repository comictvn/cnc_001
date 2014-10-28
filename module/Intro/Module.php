<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Intro;

use Intro\Model\Intro;
use Intro\Model\IntroTable;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
                   'Intro\Model\IntroTable'=>function($sm)
                   {
                      $tableGateway=$sm->get('IntroTableGateway');
                      $table=new IntroTable($tableGateway);
                      return $table;
                   },
                   'IntroTableGateway'=>function($sm)
                   {
                      $dbAdapter=$sm->get('db1');
                      $resultSetPrototype=new ResultSet();
                      $resultSetPrototype->setArrayObjectPrototype(new Intro());
                      return new TableGateway('intro',$dbAdapter,null,$resultSetPrototype);
                   },
                 
             ),
       );
    }
    

}
