<?php
namespace Lienhe\Form;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;
class LienheForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('lienhe');
        $this->setAttribute('method','post');
        
        $this->add(array(
        		'name' =>'id',
        		'attributes'=>array(
        				'type' =>'hidden',
        		),
        ));
        //Tạo captcha
        $dirdata = './template/main/img';
        $captchaImage = new CaptchaImage(
        		array(
        				'font'=> $dirdata . '/fonts/arial.ttf',
        				'width'=> 150,
        				'height'=> 50,
        				'expiration'=> 7200,
        				'dotNoiseLevel'=> 30,
        				'lineNoiseLevel'=> 1
        		)
        );
        $captchaImage->setImgDir($dirdata . '/captcha');
        $captchaImage->setImgUrl($name);
        
        $this->add(
        		array(
        				'type'=>'Zend\Form\Element\Captcha',
        				'name'=>'captcha',
        				'options'=>array('label'=>'Mã xác thực', 'captcha'=>$captchaImage),
        				'attributes'=>array('style'=>'width:100px'),
        		)
        );
        $title = new Element\Text('title');
        $title->setAttributes(array('class'=>'title'));
        $this->add($title);
        
        $content = new Element\Textarea('content');
        $content->setAttributes(array('class'=>'content'));
        $this->add($content);
        
        $name = new Element\Text('name');
        $name->setAttributes(array('class'=>'name'));
        $this->add($name);
        
        $email = new Element\Text('email');
        $email->setAttributes(array('class'=>'email'));
        $this->add($email);
        
        $phone = new Element\Text('phone');
        $phone->setAttributes(array('class'=>'phone'));
        $this->add($phone);
        
        $address = new Element\Text('address');
        $address->setAttributes(array('class'=>'address'));
        $this->add($address);
        
        $company = new Element\Text('company');
        $company->setAttributes(array('class'=>'company'));
        $this->add($company);
        
        $date = new Element\Text('date');
        date_default_timezone_set("Asia/Bangkok");
        $date->setValue(date('Y-m-d H:i:s'));
        $this->add($date);
       
    }   
}