<?php
namespace Albumanh\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Albumanh implements InputFilterAwareInterface
{
	
	public $id;
	public $image;
	public $name;
	public $alias;
	public $tag;
	public $summary;
	public $description;
	public $active;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->image=(isset($data['image']))?$data['image']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->alias=(isset($data['alias']))?$data['alias']:null;
		$this->tag=(isset($data['tag']))?$data['tag']:null;
		$this->summary=(isset($data['summary']))?$data['summary']:null;
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