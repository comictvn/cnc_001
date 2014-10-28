<?php
namespace Intro\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Intro implements InputFilterAwareInterface
{
	
	public $id;
	public $namesite;
	public $namebus;
	public $favicon;
	public $city;
	public $district;
	public $address;
	public $phone;
	public $email;
	public $googleanalytic;
	public $facebook;
	public $google;
	public $twitter;
	public $active;
	public $desactive;
	public $footer;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{

		$this->id=(isset($data['id']))?$data['id']:null;
		$this->namesite=(isset($data['namesite']))?$data['namesite']:null;
		$this->namebus=(isset($data['namebus']))?$data['namebus']:null;
		$this->favicon=(isset($data['favicon']))?$data['favicon']:null;
		$this->city=(isset($data['city']))?$data['city']:null;
		$this->district=(isset($data['district']))?$data['district']:null;
		$this->address=(isset($data['address']))?$data['address']:null;
		$this->phone=(isset($data['phone']))?$data['phone']:null;
		$this->email=(isset($data['email']))?$data['email']:null;
		$this->googleanalytic=(isset($data['googleanalytic']))?$data['googleanalytic']:null;
		$this->facebook=(isset($data['facebook']))?$data['facebook']:null;
		$this->google=(isset($data['google']))?$data['google']:null;
		$this->twitter=(isset($data['twitter']))?$data['twitter']:null;
		$this->active=(isset($data['active']))?$data['active']:null;
		$this->desactive=(isset($data['desactive']))?$data['desactive']:null;
		$this->footer=(isset($data['footer']))?$data['footer']:null;
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