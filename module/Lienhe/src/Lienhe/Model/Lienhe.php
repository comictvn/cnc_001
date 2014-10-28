<?php
namespace Lienhe\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
class Lienhe implements InputFilterAwareInterface
{
	public $id;
	public $title;
	public $content;
	public $name;
	public $email;
	public $phone;
	public $address;
	public $company;
	public $date;
	
	protected $inputFilter;
	
	public function exchangeArray($data)
	{
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->title=(isset($data['title']))?$data['title']:null;
		$this->content=(isset($data['content']))?$data['content']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->email=(isset($data['email']))?$data['email']:null;
		$this->phone=(isset($data['phone']))?$data['phone']:null;
		$this->address=(isset($data['address']))?$data['address']:null;
		$this->company=(isset($data['company']))?$data['company']:null;
		$this->date=(isset($data['date']))?$data['date']:null;
		
	}
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception('Not used');
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
	
			
		
			
			
			
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}