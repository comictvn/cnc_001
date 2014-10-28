<?php
namespace Thongso\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class ThongsoForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('thongso');
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
        
        $units = new Element\Text('units');
        $this->add($units);
        
        $description = new Element\Textarea('description');
        $this->add($description);
        
        $category = new Element\Select('category');
        $this->add($category);
        
        $parents = new Element\Select('parents');
        $this->add($parents);
        
        $active = new Element\Select('active');
        $active->setValueOptions(array('0'=>'Không','1'=>'Có'));
        $this->add($active);
        
       
    }   
}