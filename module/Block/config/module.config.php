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
            'block' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/block[/:action][/:id]',
                	'constrains'=> array (
                				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                				'id'=> '[0-9]+',
                				) ,
                    'defaults' => array(
                        'controller' => 'Block\Controller\Block',
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
            'Block\Controller\Block' => 'Block\Controller\BlockController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
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
    'view_helpers' => array(
          'invokables' => array(
                'Menus' => 'Block\View\Helper\Menus', // thay cái menu_helper thành Menus
          ),
    )
);
