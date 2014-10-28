<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'thanhtoan' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/thanhtoan[/:action][/:id]',
                	'constrains'=> array (
                				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                				'id'=> '[0-9]+',
                				) ,
                    'defaults' => array(
                        'controller' => 'Thanhtoan\Controller\Thanhtoan',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
        ),
    ),
    
    'controllers' => array(
        'invokables' => array(
            'Thanhtoan\Controller\Thanhtoan' => 'Thanhtoan\Controller\ThanhtoanController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
       
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
   
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
   
);
