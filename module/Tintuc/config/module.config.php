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
            'tintuc' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/tin-tuc[/:action[/:id][/:page]]',
                	'constrains'=> array (
                				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                				'id'=> '[0-9]+',
                				) ,
                    'defaults' => array(
                        'controller' => 'Tintuc\Controller\Tintuc',
                        'action'     => 'index',
                    ),
                ),
            ),
        		'chitiettintuc' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route'    => '[/:alias].html',
        						'constrains'=> array (
        								'alias' => '[a-zA-Z][a-zA-Z0-9_-]*',
        							
        						) ,
        						'defaults' => array(
        								'controller' => 'Tintuc\Controller\Tintuc',
        								'action'     => 'detail',
        						),
        				),
        		),
        		'danhmuc' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route'    => '/dich-vu[/:alias][/:id].html',
        						'constrains'=> array (
        								'alias' => '[a-zA-Z][a-zA-Z0-9_-]*',
        								 
        						) ,
        						'defaults' => array(
        								'controller' => 'Tintuc\Controller\Tintuc',
        								'action'     => 'danhmuc',
        						),
        				),
        		),
        		'gioithieu' => array(
        				'type' => 'segment',
        				'options' => array(
        						'route'    => '/gioi-thieu-chung[/:id]',
        						'constrains'=> array (
        								'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
        									
        						) ,
        						'defaults' => array(
        								'controller' => 'Tintuc\Controller\Tintuc',
        								'action'     => 'gioithieu',
        						),
        				),
        		),
        		'dichvu' => array(
        				'type' => 'Literal',
        				'options' => array(
        						'route'    => '/dich-vu',
        						'defaults' => array(
        								'controller' => 'Tintuc\Controller\Tintuc',
        								'action'     => 'dichvu',
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
            'Tintuc\Controller\Tintuc' => 'Tintuc\Controller\TintucController'
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
