<?php
namespace Sanpham\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Crypt\PublicKey\Rsa\PublicKey;

class Sanpham implements InputFilterAwareInterface
{
	
	public $id;
	public $proid;
	public $proname;
	public $alias;
	public $order;
	public $price;
	public $pricesales;
	public $salesto;
	public $salesfrom;
	public $vat;
	public $district;
	public $tag;
	public $active;
	public $video;
	public $quantity;
	public $pronew;
	public $proselling;
	public $procomming;
	public $proindex;
	public $image;
	public $summary;
	public $description;
	public $meta;
	public $keyword;
	public $idcate;
	public $cate_alias;
    
	protected $inputFilter;
	public function exchangeArray($data)
	{
		
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->proid=(isset($data['proid']))?$data['proid']:null;
		$this->proname=(isset($data['proname']))?$data['proname']:null;
		$this->alias=(isset($data['alias']))?$data['alias']:null;
		$this->order=(isset($data['order']))?$data['order']:null;
		$this->price=(isset($data['price']))?$data['price']:null;
		$this->pricesales=(isset($data['pricesales']))?$data['pricesales']:null;
		$this->salesto=(isset($data['salesto']))?$data['salesto']:null;
		$this->salesfrom=(isset($data['salesfrom']))?$data['salesfrom']:null;
		$this->vat=(isset($data['vat']))?$data['vat']:null;
		$this->district=(isset($data['district']))?$data['district']:null;
		$this->tag=(isset($data['tag']))?$data['tag']:null;
		$this->active=(isset($data['active']))?$data['active']:null;
		$this->video=(isset($data['video']))?$data['video']:null;
		$this->quantity=(isset($data['quantity']))?$data['quantity']:null;
		$this->pronew=(isset($data['pronew']))?$data['pronew']:null;
		$this->proselling=(isset($data['proselling']))?$data['proselling']:null;
		$this->procomming=(isset($data['procomming']))?$data['procomming']:null;
		$this->proindex=(isset($data['proindex']))?$data['proindex']:null;
		$this->image=(isset($data['image']))?$data['image']:null;
		$this->summary=(isset($data['summary']))?$data['summary']:null;
		$this->description=(isset($data['description']))?$data['description']:null;
		$this->meta=(isset($data['meta']))?$data['meta']:null;
		$this->keyword=(isset($data['keyword']))?$data['keyword']:null;
		$this->idcate=(isset($data['idcate']))?$data['idcate']:null;
		$this->cate_alias=(isset($data['cate_alias']))?$data['cate_alias']:null;
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
					'name'     => 'alias',
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
											'min'      => 1,
											'max'      => 100,
											'messages' => array(
													'stringLengthTooShort' => "Phải nhập lớn hơn '%min%' kí tự",
													'stringLengthTooLong'=> "Phải nhập nhỏ hơn '%max%' kí tự",)
									),
							),
			
							array('name' => 'NotEmpty',
									'break_chain_on_failure' => true,
									'options' => array('message' => array('isEmpty' => 'Phải nhập alias'),)),
					),
			)));
		
			
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}