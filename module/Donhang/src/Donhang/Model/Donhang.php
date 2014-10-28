<?php 
namespace Donhang\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
class Donhang implements InputFilterAwareInterface
{
	public $customers_id;
	public $customers_name;
	public $address;
	public $phone_number;
	public $delivery_address;
	public $email;
	protected $inputFilter;
	public  function exchangeArray($data)
	{
		$this->customers_id=(isset($data['customers_id']))?$data['customers_id']:null;
		$this->customers_name=(isset($data['customers_name']))?$data['customers_name']:null;
		$this->address=(isset($data['address']))?$data['address']:null;
		$this->phone_number=(isset($data['phone_number']))?$data['phone_number']:null;
		$this->delivery_address=(isset($data['delivery_address']))?$data['delivery_address']:null;
		$this->email=(isset($data['email']))?$data['email']:null;
	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \ErrorException('Not used');
	}
	public function getInputFilter()
	{
		if(!$this->inputFilter)
		{
			$inputFilter= new InputFilter();
			$factory= new InputFactory();
			$inputFilter->add($factory->createInput(array(
						'name'=>'customers_id',
						'required'=>true,
						'filters'=>array(array('name'=>'int')),
					)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'customers_name',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 100,
											'messages' => array(
													'stringLengthTooShort' => "Phải nhập lớn hơn '%min%' kí tự",
													'stringLengthTooLong'=> "Phải nhập nhỏ hơn '%max%' kí tự",)
									),
							),
			
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Phải nhập Họ tên'),)),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'address',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 100,
											'messages' => array(
													'stringLengthTooShort' => "Phải nhập lớn hơn '%min%' kí tự",
													'stringLengthTooLong'=> "Phải nhập nhỏ hơn '%max%' kí tự",)
									),
							),
								
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Phải nhập Địa chỉ'),)),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'phone_number',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 100,
											'messages' => array(
													'stringLengthTooShort' => "Phải nhập lớn hơn '%min%' kí tự",
													'stringLengthTooLong'=> "Phải nhập nhỏ hơn '%max%' kí tự",)
									),
							),
			
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Phải nhập Điện thoại'),)),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'delivery_address',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 1,
											'max'      => 100,
											'messages' => array(
													'stringLengthTooShort' => "Phải nhập lớn hơn '%min%' kí tự",
													'stringLengthTooLong'=> "Phải nhập nhỏ hơn '%max%' kí tự",)
									),
							),
								
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Phải nhập Địa chỉ giao hàng'),)),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'email',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Phải nhập email'),)),
							
							array('name' => 'EmailAddress',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('emailAddressInvalid' => 'email không hợp lệ'),)),													
					),
			)));
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}
?>