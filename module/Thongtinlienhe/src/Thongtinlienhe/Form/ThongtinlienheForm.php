<?php
namespace Thongtinlienhe\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class ThongtinlienheForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('thongtinlienhe');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        
        $namecontact = new Element\Text('namecontact');
        $namecontact->setAttributes(array('class'=>'validate[required]'));
        $this->add($namecontact);
        
        $contactperson = new Element\Text('contactperson');
        $contactperson->setAttributes(array('class'=>'validate[required]'));
        $this->add($contactperson);
        
        $business = new Element\Text('business');
        $this->add($business);
        
        $address = new Element\Text('address');
        $this->add($address);
        
        $license = new Element\Text('license');
        $this->add($license);
        
        $taxcode = new Element\Text('taxcode');
        $this->add($taxcode);
        
        $phone = new Element\Text('phone');
        $this->add($phone);
        
        $email = new Element\Text('email');
        $this->add($email);
        
        $yahoo = new Element\Text('yahoo');
        $this->add($yahoo);
        
        $skype = new Element\Text('skype');
        $this->add($skype);
        
        
    }   
}