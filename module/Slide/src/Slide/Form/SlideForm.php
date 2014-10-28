<?php
namespace Slide\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class SlideForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('slide');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        
        $module = new Element\Text('module');
        $this->add($module);
        
        $position = new Element\Select('position');
        $this->add($position);
        
        $name = new Element\Text('name');
        $this->add($name);
        
        $html = new Element\Textarea('html');
        $html->setAttributes(array('placeholder'=>'Phần nhập đoạn mã html','class'=>'innerbox html','style'=>'height: 200px'));
        $this->add($html);
        
        $css = new Element\Textarea('css');
        $css->setAttributes(array('placeholder'=>'Phần nhập đoạn mã css','class'=>'innerbox html','style'=>'height: 200px'));
        $this->add($css);
        
        $link = new Element\Text('link');
        $this->add($link);
        
        $active = new Element\Select('active');
        $active->setValueOptions(array('0'=>'Ngưng kích hoạt', '1'=>'Kích hoạt'));
        $this->add($active);
        
    }   
}