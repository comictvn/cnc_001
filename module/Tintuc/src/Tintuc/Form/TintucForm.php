<?php
namespace Tintuc\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class TintucForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('tintuc');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        
        $title = new Element\Text('title');
        $title->setAttributes(array('class'=>'validate[required]'));
        $this->add($title);
        
        $title_en = new Element\Text('title_en');
        $title_en->setAttributes(array('class'=>'validate[required]'));
        $this->add($title_en);
        
        $alias = new Element\Text('alias');
        $alias->setAttributes(array('class'=>'validate[required]'));
        $this->add($alias);
        
        $index = new Element\Checkbox('index');
        $this->add($index);
        
        $category = new Element\Select('category');
        $this->add($category);
        
        $resources = new Element\Text('resources');
        $this->add($resources);
        
        $author = new Element\Text('author');
        $this->add($author);
        
        $view = new Element\Text('view');
        $this->add($view);
        
        $tag = new Element\Text('tag');
        $tag->setAttributes(array('class'=>'tags'));
        $this->add($tag);
        
        $image = new Element\Text('image');
        $image->setAttributes(array('id'=>'xImagePath'));
        $this->add($image);
        
        $acitve = new Element\Select('active');
        $acitve->setValueOptions(array('0'=>'Chưa xuất bản', '1'=>'Xuất bản', '2'=>'Hủy'));
        $this->add($acitve);
        
        $summary = new Element\Textarea('summary');
        $summary->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($summary);
        
        $summary_en = new Element\Textarea('summary_en');
        $summary_en->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($summary_en);
        
        $description = new Element\Textarea('description');
        $description->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($description);
        
        $description_en = new Element\Textarea('description_en');
        $description_en->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($description_en);
        
        $meta = new Element\Textarea('meta');
        $this->add($meta);
        
        $keyword = new Element\Text('keyword');
        $keyword->setAttributes(array('class'=>'tags'));
        $this->add($keyword);
        
        $date = new Element\Text('date');
        date_default_timezone_set("Asia/Bangkok");
        $date->setValue(date('Y-m-d H:i:s'));
        $this->add($date);
        
       
    }   
}