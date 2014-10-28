<?php
namespace Danhmuc\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class DanhmucForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('danhmuc');
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
        $alias->setAttributes(array('class'=>'validate[required]'));
        $this->add($alias);
        
        $order = new Element\Text('order');
        $this->add($order);
        
        $parent = new Element\Select('parent');
        $this->add($parent);
        
        $cateindex = new Element\Checkbox('cateindex');
        $this->add($cateindex);
        
        $quantity = new Element\Text('quantity');
        $quantity->setAttributes(array('placeholder'=>'Mặc định là 10'));
        $this->add($quantity);
        
     
        
        $description = new Element\Textarea('description');
        $description->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($description);
        
        $image = new Element\Text('image');
        $image->setAttributes(array('id'=>'xImagePath'));
        $this->add($image);
        
        $icon = new Element\Text('icon');
        $icon->setAttributes(array('id'=>'xImagePath2'));
        $this->add($icon);
        
        $background = new Element\Text('background');
        $background->setAttributes(array('id'=>'xImagePath3'));
        $this->add($background);
        
        $seotitle = new Element\Text('seotitle');
        $this->add($seotitle);
        
        $meta = new Element\Textarea('meta');
        $this->add($meta);
        
        $keyword = new Element\Text('keyword');
        $keyword->setAttributes(array('class'=>'tags'));
        $this->add($keyword);
        
        $active = new Element\Text('active');
        $active->setValue(1);
        $this->add($active);
        
    }   
}