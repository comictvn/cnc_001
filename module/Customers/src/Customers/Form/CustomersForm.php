<?php 
namespace Customers\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class CustomersForm extends Form
{
	public function __construct($name=null)
	{
		parent::__construct('customers');
		
		$this->setAttribute('method','post');

		$customers_id = new Element\Hidden('customers_id');
		$this->add($customers_id);
		
		$customers_name = new Element\Text('customers_name');
		$customers_name->setLabel("Họ tên");
		$customers_name->setAttribute('class'=>'span4')
		$this->add($customers_name);
		
		$address = new Element\Text('address');
		$address->setLabel("Địa chỉ");
		$address->setAttribute('class'=>'span4')
		$this->add($address);
		
		$phone_number = new Element\Text('phone_number');
		$phone_number->setLabel('Điện thoại');
		$phone_number->setAttribute('class'=>'span4')
		$this->add($phone_number);
		
		$delivery_address = new Element\Text('delivery_address');
		$delivery_address->setLabel('Địa chỉ giao hàng');
		$delivery_address->setAttribute('class'=>'span4')
		$this->add($delivery_address);
		
		$email = new Element\Text('email');
		$email->setLabel('Email');
		$email->setAttribute('class'=>'span4')
		$this->add($email);
		
		$submit = new Element\Submit('submit');
		$submit->setLabel('Go');
		$submit->setAttributes(array('class'=>"art-button",'value'=>"Go"));
		$this->add($submit);
	}
}
?>