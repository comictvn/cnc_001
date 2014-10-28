<?php
namespace Member\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Login 
{
	public $username;
	public $password;
	
	
	protected $inputFilter;
	
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception('Not used');
	}
	public function getInputFilter()
	{
		if(!$this->inputFilter)
		{
			$inputFilter = new InputFilter();
			$factory 	 = new InputFactory();
			$inputFilter->add($factory->createInput(array(
					'name'=> 'username',
					'required'=>true,
					'filters'=> array(
						array('name'=>'StripTags'),
						array('name'=>'StringTrim'),
					),
					
					'validators'=> array(
						array('name'=>'NotEmpty',
								'break_chain_on_failure'=>true,
								'options'=> array('message'=>array('isEmpty'=>'Phải nhập tên đăng nhập'),)
					)
					),
					
					
			)));
			
			$inputFilter->add($factory->createInput(array(
					'name'=>'password',
					'required'=> true,
					'filters'=> array(
						array('name'=>'StripTags'),
						array('name'=>'StringTrim'),
					),
					'validators'=> array(
						array('name'=>'NotEmpty',
								'break_chain_on_failure'=>true,
								'options'=> array('message'=> array('isEmpty'=>'Phải nhập mật khẩu'),)
					),
					),
					
			)));
			$this->inputFilter = $inputFilter;
		}
		return $this->inputFilter;
	}
}