<?php
namespace Danhmuc\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Danhmuc implements InputFilterAwareInterface
{
	
	public $id;
	public $name;
	public $alias;
	public $order;
	public $parent;
	public $cateindex;
	public $quantity;
	public $description;
	public $image;
	public $icon;
	public $background;
	public $seotitle;
	public $meta;
	public $keyword;
	public $active;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{

		$this->id=(isset($data['id']))?$data['id']:null;
		$this->name=(isset($data['name']))?$data['name']:null;
		$this->alias=(isset($data['alias']))?$data['alias']:null;
		$this->order=(isset($data['order']))?$data['order']:null;
		$this->parent=(isset($data['parent']))?$data['parent']:null;
		$this->cateindex=(isset($data['cateindex']))?$data['cateindex']:null;
		$this->quantity=(isset($data['quantity']))?$data['quantity']:null;
		$this->description=(isset($data['description']))?$data['description']:null;
		$this->image=(isset($data['image']))?$data['image']:null;
		$this->icon=(isset($data['icon']))?$data['icon']:null;
		$this->background=(isset($data['background']))?$data['background']:null;
		$this->seotitle=(isset($data['seotitle']))?$data['seotitle']:null;
		$this->meta=(isset($data['meta']))?$data['meta']:null;
		$this->keyword=(isset($data['keyword']))?$data['keyword']:null;
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
			
			
			
			
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}