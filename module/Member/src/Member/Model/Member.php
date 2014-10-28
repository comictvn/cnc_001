<?php
namespace Member\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Crypt\PublicKey\Rsa\PublicKey;

class Member implements InputFilterAwareInterface
{
	
	public $id;
	public $username;
	public $password;
	public $fullname;
	public $birthdate;
	public $gender;
	public $address;
	public $email;
	public $identitycard;
	public $mobiphone;
	public $role;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->username=(isset($data['username']))?$data['username']:null;
		$this->password=(isset($data['password']))?$data['password']:null;
		$this->fullname=(isset($data['fullname']))?$data['fullname']:null;
		$this->birthdate=(isset($data['birthdate']))?$data['birthdate']:null;
		$this->gender=(isset($data['gender']))?$data['gender']:null;
		$this->address=(isset($data['address']))?$data['address']:null;
		$this->email=(isset($data['email']))?$data['email']:null;
		$this->identitycard=(isset($data['identitycard']))?$data['identitycard']:null;
		$this->mobiphone=(isset($data['mobiphone']))?$data['mobiphone']:null;
		$this->role=(isset($data['role']))?$data['role']:null;
		
		
	}
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception('Not use');
	}
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	public function getInputFilter()
	{
		if(!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory = new InputFactory();
	
			$inputFilter->add($factory->createInput(array(
					'name'=>'id',
					'required'=>true,
					'filters' =>array(array('name' =>'int'),),
			)));
	
			
	
			$inputFilter->add($factory->createInput(array(
					'name'     => 'username',
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
											'min'      => 5,
											'max'      => 50,
											'messages' => array(
													'stringLengthTooShort' => 'Phải nhập hơn %min% kí tự',
													'stringLengthTooLong'=> 'Phải nhập nhỏ hơn %max kí tự',)
									),
							),	
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Giá trị là bắt buộc và không thể để trống'),)),
					),
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'     => 'password',
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
											'min'      => 5,
											'max'      => 50,
											'messages' => array(
													'stringLengthTooShort' => 'Phải nhập hơn %min% kí tự',
													'stringLengthTooLong'=> 'Phải nhập nhỏ hơn %max kí tự',)
									),
							),
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Giá trị là bắt buộc và không thể để trống'),)),
					),
			)));
			
			
			
			
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}