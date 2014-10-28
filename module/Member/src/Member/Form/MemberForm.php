<?php
namespace Member\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class MemberForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('member');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        
        $username = new Element\Text('username');
        $this->add($username);
        
        $password = new Element\Password('password');
        $this->add($password);
        
        $fullname = new Element\Text('fullname');
        $this->add($fullname);
        
        $birthdate = new Element\Text('birthdate');
        $this->add($birthdate);
        
        $gender = new Element\Select('gender');
        $gender->setValueOptions(array('1'=>'Nam' , '0'=>'Nữ'));
        $this->add($gender);
        
        $address = new Element\Textarea('address');
        $this->add($address);
        
        $email = new Element\Text('email');
        $this->add($email);
        
        $phone = new Element\Text('mobiphone');
        $this->add($phone);
        
        $identitycard = new Element\Text('identitycard');
        $this->add($identitycard);
        
        $role = new Element\Text('role');
        $role->setValue('admin');
        $this->add($role);
       
        
       
    }   
}