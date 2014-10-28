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
            'danhmucsanpham' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/danhmuc[/:action][/:id]',
                	'constrains'=> array (
                				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                				'id'=> '[0-9]+',
                				) ,
                    'defaults' => array(
                        'controller' => 'Danhmuc\Controller\Danhmuc',
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
            'Danhmuc\Controller\Danhmuc' => 'Danhmuc\Controller\DanhmucController'
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
