<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Model\ApplicationTable;
use Zend\Db\ResultSet\ResultSet;
use Application\Model\Application;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach('dispatch', array($this, 'loadConfiguration' ));
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
    					'Application\Model\ApplicationTable'=>function($sm)
    					{
    						$tableGateway=$sm->get('ApplicationTableGateway');
    						$table=new ApplicationTable($tableGateway);
    						return $table;
    					},
    					'ApplicationTableGateway'=>function($sm)
    					{
    						$dbAdapter=$sm->get('db1');
    						$resultSetPrototype=new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Application());
    						return new TableGateway('block',$dbAdapter,null,$resultSetPrototype);
    					},
    					 
    			),
    	);
    }
    
    public function loadConfiguration(MvcEvent $e)
    {
    	$controller = $e->getTarget();
    	$controllerClass = get_class($controller);
    	$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
    
    	//set 'variable' into layout...
    	$controller->layout()->modulenamespace = $moduleNamespace;
    }
    
 
}
