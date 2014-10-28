<?php
namespace Slide\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Crypt\PublicKey\Rsa\PublicKey;

class Slide implements InputFilterAwareInterface
{

	public $id;
	public $module;
	public $position;
	public $name;
	public $html;
	public $css;
	public $link;
	public $active;

	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->module=(isset($data['module']))?$data['module']:null;
		$this->position=(isset($data['position']))?$data['position']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->html=(isset($data['html']))?$data['html']:null;
		$this->css=(isset($data['css']))?$data['css']:null;
		$this->link=(isset($data['link']))?$data['link']:null;
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