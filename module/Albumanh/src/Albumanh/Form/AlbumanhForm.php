<?php
namespace Albumanh\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class AlbumanhForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('albumanh');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  
        
        $image = new Element\Text('image');
        $image->setAttributes(array('id'=>'xImagePath'));
        $this->add($image);

        $name = new Element\Text('name');
        $name->setAttributes(array('class'=>'validate[required]'));
        $this->add($name);
        
        $alias = new Element\Text('alias');
        $this->add($alias);
        
        $summary = new Element\Textarea('summary');
        $this->add($summary);
        
        $description = new Element\Textarea('description');
        $description->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($description);
        
        $active = new Element\Select('active');
        $active->setValueOptions(array('1'=>'Kích hoạt','0'=>'Ngưng'));
        $this->add($active);
        
        $tag = new Element\Text('tag');
        $tag->setAttributes(array('class'=>'tags'));
        $this->add($tag);
        
    }   
}