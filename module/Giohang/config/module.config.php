<?php
return array(
    'controllers'=>array('invokables'=>array(
                                             'Giohang\Controller\Giohang'=>'Giohang\Controller\GiohangController',   
                                               
                                                ),),
    'router'=>array(
        'routes'=>array(                
                'giohang'=>array( 'type'=>'segment',
                                'options'=>array(   'route'=>'/giohang[/:action][/:id][/:sl][/:dg]',
                                                    'contraints'=>array('action'=>'[a-zA-Z][a-zA-Z0-9_-]*','id'=>'[0-9]','sl'=>'[0-9]','dg'=>'[0-9]'),
                                                    'defaults'=>array('controller'=>'Giohang\Controller\Giohang','action'=>'index'),
                                                 ),
                               ),
                
                        ),
                    ),
    'view_manager'=>array(      
        'template_path_stack' =>array('Giohang'=>__DIR__ . '/../view',
        ),
    ),
);