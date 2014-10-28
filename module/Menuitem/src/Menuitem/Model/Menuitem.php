<?php
namespace Menuitem\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Menuitem implements InputFilterAwareInterface
{
	
	public $id;
	public $idmenu;
	public $name;
	public $alias;
	public $link;
	public $parent;
	public $order;
	public $icon;
	public $click;
	public $nofollow;
	public $active;

	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->idmenu=(isset($data['idmenu']))?$data['idmenu']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->alias=(isset($data['alias']))?$data['alias']:null;
		$this->link=(isset($data['link']))?$data['link']:null;
		$this->parent=(isset($data['parent']))?$data['parent']:null;
		$this->order=(isset($data['order']))?$data['order']:null;
		$this->icon=(isset($data['icon']))?$data['icon']:null;
		$this->click=(isset($data['click']))?$data['click']:null;
		$this->nofollow=(isset($data['nofollow']))?$data['nofollow']:null;
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