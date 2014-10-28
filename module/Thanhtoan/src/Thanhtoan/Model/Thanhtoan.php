<?php
namespace Thanhtoan\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Thanhtoan implements InputFilterAwareInterface
{
	
	public $id;
	public $description;
	public $cod;
	public $coddes;
	public $paybank;
	public $bankdes;

	protected $inputFilter;
	public function exchangeArray($data)
	{
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->description=(isset($data['description']))?$data['description']:null;
		$this->cod=(isset($data['cod']))?$data['cod']:null;
		$this->coddes=(isset($data['coddes']))?$data['coddes']:null;
		$this->paybank=(isset($data['paybank']))?$data['paybank']:null;
		$this->bankdes=(isset($data['bankdes']))?$data['bankdes']:null;
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