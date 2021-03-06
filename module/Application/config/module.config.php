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
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
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
    'service_manager' => array(
    		'factories' => array(
    				'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory', // <-- add this
    				
    		),
    ),
    'navigation' => array(
    		'default' => array(
    				array(
		    				'label' => '<span class="isw-grid"></span><span class="text">Trang chủ</span>',
		    				'uri'=>'#',
    				),
    				array(
                            'label' => '<span class="text">Sản phẩm</span>',
                            'uri'=>'#',
                            'wrapClass' => 'text',
                            'class'     => 'text',
                            'data-class' => 'openable',
                            'pages' => array(
                                array(
                                        'label' => '<span class="text">Đăng sản phẩm</span>',
                                        'route' => 'sanpham',
                                        'action' => 'add',
                                
                                ),
                                array(
                                        'label' => '<span class="text">Quản lý sản phẩm</span>',
                                        'route' => 'sanpham',
                                        'action' => 'list',
                                
                                ),
                                array(
                                        'label' => '<span class="text">Quản lý danh mục</span>',
                                        'route' => 'danhmuc',
                                        'action' => 'list',
                                ),
                                array(
                                        'label' => '<span class="text">Thông tin liên hệ</span>',
                                        'route' => 'thongtinlienhe',
                                        'action' => 'list',
                                ),
                                array(
                                        'label' => '<span class="text">Cài đặt thanh toán</span>',
                                        'route' => 'thanhtoan',
                                        'action' => 'list',
                                ),
                                array(
                                        'label' => '<span class="text">Quản lý đơn hàng</span>',
                                        'route' => 'donhang',
                                        'action' => 'list',
                                ),
                                array(
                                        'label' => '<span class="text">Thông số sản phẩm</span>',
                                        'route' => 'thongso',
                                        'action' => 'list',
                                ),
                            )
                            
                    ),
					array(
							'label' => '<span class="text">Nội dung</span>',
							'route' => 'tintuc',
							'action' => 'list',
							'data-class' => 'openable',
							'pages'=>array(
										array(
												'label' => '<span class="text">Quản lý nội dung</span>',
												'route' => 'tintuc',
												'action' => 'list',
										),
										array(
												'label' => '<span class="text">Thể loại</span>',
												'route' => 'theloaitin',
												'action' => 'list',
										),
									
									)
					),
					
					array(
							'label' => '<span class="text">Cài đặt</span>',
							'uri'=>'#',
							'pages'=> array(
										array(
											'label' => '<span class="text">Thông tin cơ bản</span>',
											'route' => 'intro',
											'action' => 'basic',
										),
										array(
												'label' => '<span class="text">Tối ưu tìm kiếm</span>',
												'route' => 'intro',
												'action' => 'seo',
										),
										array(
												'label' => '<span class="text">Google webmaster</span>',
												'route' => 'intro',
												'action' => 'googlewebmaster',
										),
									)
					),
					array(
							'label' => '<span class="text">Slide</span>',
							'route' => 'slide',
							'action' => 'list',
						),
					array(
							'label' => '<span class="text">Album</span>',
							'route' => 'albumanh',
							'action' => 'list',
							'data-class' => 'openable',
					),
					array(
							'label' => '<span class="text">Giao diện</span>',
							'uri'=>'#',
							'pages'=> array(
									array(
											'label' => '<span class="text">Quản lý block</span>',
											'route' => 'block',
											'action' => 'list',
									),
									array(
											'label' => '<span class="text">Quản lý block HTML</span>',
											'route' => 'block',
											'action' => 'html',
									),
							)
					),
					array(
							'label' => '<span class="text">Chức năng khác</span>',
							'uri'=>'#',
							'pages'=> array(
									array(
											'label' => '<span class="text">Ý kiến khách hàng</span>',
											'route' => 'block',
											'action' => 'list',
									),
									array(
											'label' => '<span class="text">Hỏi đáp</span>',
											'route' => 'block',
											'action' => 'html',
									),
									array(
											'label' => '<span class="text">Danh sách liên hệ</span>',
											'route' => 'lienhe',
											'action' => 'list',
									),
							)
					),
					array(
							'label' => '<span class="text">Báo cáo thống kê</span>',
							'uri'=>'#',
							'pages'=> array(
									array(
											'label' => '<span class="text">Báo cáo sản phẩm</span>',
											'route' => 'block',
											'action' => 'list',
									),
									array(
											'label' => '<span class="text">Báo cáo ds khách hàng</span>',
											'route' => 'block',
											'action' => 'html',
									),
									array(
											'label' => '<span class="text">Báo cáo đơn hàng</span>',
											'route' => 'block',
											'action' => 'html',
									),
									array(
											'label' => '<span class="text">Báo cáo doanh thu</span>',
											'route' => 'block',
											'action' => 'html',
									),
							)
					),
					array(
							'label' => '<span class="text">Hướng dẫn</span>',
							'uri'=>'#',
					),
					
    				
    				
    				
    		),
    		
    		
    ),
  
 
);
