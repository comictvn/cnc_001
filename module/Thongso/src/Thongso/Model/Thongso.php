<?php
namespace Thongso\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Thongso implements InputFilterAwareInterface
{
	public $id;
	public $name;
	public $units;
	public $description;
	public $category;
	public $parents;
	public $active;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{

		$this->id=(isset($data['id']))?$data['id']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->units=(isset($data['units']))?$data['units']:null;
		$this->description=(isset($data['description']))?$data['description']:null;
		$this->category=(isset($data['category']))?$data['category']:null;
		$this->parents=(isset($data['parents']))?$data['parents']:null;
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