<?php
namespace Member\Form;
use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends	Form
{
	public function __construct($name=null)
	{
		parent::__construct('login');
		$this->setAttribute('method', 'post');
		
		
		
		$username = new Element\Text('username');
		$username->setLabel('Tên đăng nhập');
		$username->setAttributes(array('class'=>'form-control'));
		$this->add($username);
		
		$password = new Element\Password('password');
		$password->setLabel('Mật khẩu');
		$password->setAttributes(array('class'=>'form-control'));
		$this->add($password);
		
		$dang_nhap = new Element\Submit('dang_nhap');
		$dang_nhap->setValue('Đăng nhập');
		$this->add($dang_nhap);
	}
}