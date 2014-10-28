<?php
namespace Menu\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Menu implements InputFilterAwareInterface
{
	
	public $id;
	public $name;
	public $alias;
	public $description;
	public $active;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->alias=(isset($data['alias']))?$data['alias']:null;
		$this->description=(isset($data['description']))?$data['description']:null;
		$this->active=(isset($data['active']))?$data['active']:null;
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