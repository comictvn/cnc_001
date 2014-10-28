<?php
namespace Application\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Crypt\PublicKey\Rsa\PublicKey;

class Application implements InputFilterAwareInterface
{
	
	public $id;
	public $name;
	public $name_en;

	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->name_en=(isset($data['name_en']))?$data['name_en']:null;
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
					'name'     => 'name',
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
											'min'      => 3,
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
					'name'     => 'name_en',
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
											'min'      => 3,
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