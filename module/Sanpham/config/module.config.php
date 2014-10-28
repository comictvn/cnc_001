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
            'sanpham' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/san-pham[/:action[/:id][/:page]]',
                	'constrains'=> array (
                				'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                				
                				) ,
                    'defaults' => array(
                        'controller' => 'Sanpham\Controller\Sanpham',
                        'action'     => 'index',
                    ),
                ),
            ),
        		
        	'chitietsanpham' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/san-pham[/:alias].html',
        					'constrains'=> array (
        							'alias' => '[a-zA-Z][a-zA-Z0-9_-]*',
        					) ,
        					'defaults' => array(
        							'controller' => 'Sanpham\Controller\Sanpham',
        							'action'     => 'detail',
        					),
        			),
        	),
        	
        	'categoryProduct' => array(
        			'type' => 'segment',
        			'options' => array(
        					'route'    => '/san-pham[/:alias[/:page]]/',
        					'constrains'=> array (
        							'alias' => '[a-zA-Z][a-zA-Z0-9_-]*',
        					) ,
        					'defaults' => array(
        							'controller' => 'Sanpham\Controller\Sanpham',
        							'action'     => 'cate',
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
            'Sanpham\Controller\Sanpham' => 'Sanpham\Controller\SanphamController'
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
    'view_helpers' => array(
    		'invokables'=> array(
    				'test_helper' => 'Sanpham\View\Helper\Testhelper',
    		)
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
