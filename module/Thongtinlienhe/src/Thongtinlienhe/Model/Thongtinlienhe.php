<?php
namespace Thongtinlienhe\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Thongtinlienhe implements InputFilterAwareInterface
{
	
	public $id;
	public $namecontact;
	public $contactperson;
	public $business;
	public $address;
	public $license;
	public $taxcode;
	public $phone;
	public $email;
	public $yahoo;
	public $skype;

	
	protected $inputFilter;
	public function exchangeArray($data)
	{

		$this->id=(isset($data['id']))?$data['id']:null;
		$this->namecontact=(isset($data['namecontact']))?$data['namecontact']:null;
		$this->contactperson=(isset($data['contactperson']))?$data['contactperson']:null;
		$this->business=(isset($data['business']))?$data['business']:null;
		$this->address=(isset($data['address']))?$data['address']:null;
		$this->license=(isset($data['license']))?$data['license']:null;
		$this->taxcode=(isset($data['taxcode']))?$data['taxcode']:null;
		$this->phone=(isset($data['phone']))?$data['phone']:null;
		$this->email=(isset($data['email']))?$data['email']:null;
		$this->yahoo=(isset($data['yahoo']))?$data['yahoo']:null;
		$this->skype=(isset($data['skype']))?$data['skype']:null;
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
	
			
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}