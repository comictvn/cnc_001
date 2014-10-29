<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Customers;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Customers\Model\Customers;
use Customers\Model\CustomersTable;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
                ),
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
                'Customers\Model\CustomersTable'=>function($sm){
                    $tableGateway=$sm->get('CustomersTableGateway');
                    $table= new CustomersTable($tableGateway);
                    return $table;
                },
                'CustomersTableGateway'=> function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype= new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Customers());
                    return new TableGateway('customers',$dbAdapter,null,$resultSetPrototype);
                },
            ),
        );
    }
}
