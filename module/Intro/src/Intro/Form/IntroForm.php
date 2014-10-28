<?php
namespace Intro\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class IntroForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('intro');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        
        $namesite = new Element\Text('namesite');
        $namesite->setAttributes(array('class'=>'validate[required]'));
        $this->add($namesite);
        
        $namebus = new Element\Text('namebus');
        $this->add($namebus);
        
        $favicon = new Element\Text('favicon');
        $this->add($favicon);
        
        $city = new Element\Text('city');
        $this->add($city);
        
        $district = new Element\Text('district');
        $this->add($district);
        
        $address = new Element\Text('address');
        $this->add($address);
        
        $phone = new Element\Text('phone');
        $this->add($phone);
        
        $email = new Element\Text('email');
        $this->add($email);
        
        $googleanalytic = new Element\Textarea('googleanalytic');
        $this->add($googleanalytic);
        
        $facebook = new Element\Text('facebook');
        $this->add($facebook);
        
        $google = new Element\Text('google');
        $this->add($google);
        
        $twitter = new Element\Text('twitter');
        $this->add($twitter);
        
        $active = new Element\Select('active');
        $active->setValueOptions(array('1'=>'Hoạt động','0'=>'Tạm ngưng'));
        $this->add($active);
        
        $desactive = new Element\Textarea('desactive');
        $desactive->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($desactive);
        
        $footer = new Element\Textarea('footer');
        $footer->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($footer);
        
    }   
}