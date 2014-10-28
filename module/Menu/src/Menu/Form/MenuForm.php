<?php
namespace Menu\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class MenuForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('menu');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        $name = new Element\Text('name');
        $name->setAttributes(array('class'=>'validate[required]'));
        $this->add($name);
        
        $alias = new Element\Text('alias');
        $this->add($alias);
        
        $description = new Element\Textarea('description');
        $this->add($description);
        
        $active = new Element\Text('active');
        $active->setValue(1);
        $this->add($active);
        
    }   
}