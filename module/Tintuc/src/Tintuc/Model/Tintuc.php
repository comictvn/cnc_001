<?php
namespace Tintuc\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Tintuc implements InputFilterAwareInterface
{
	
	public $id;
	public $title;
	public $title_en;
	public $alias;
	public $index;
	public $category;
	public $resources;
	public $author;
	public $view;
	public $tag;
	public $image;
	public $acitve;
	public $summary;
	public $summary_en;
	public $description;
	public $description_en;
	public $meta;
	public $keyword;
	public $date;
	
	protected $inputFilter;
	public function exchangeArray($data)
	{
		$this->id=(isset($data['id']))?$data['id']:null;
		$this->title=(isset($data['title']))?$data['title']:null;
		$this->title_en=(isset($data['title_en']))?$data['title_en']:null;
		$this->alias=(isset($data['alias']))?$data['alias']:null;
		$this->index=(isset($data['index']))?$data['index']:null;
		$this->category=(isset($data['category']))?$data['category']:null;
		$this->resources=(isset($data['resources']))?$data['resources']:null;
		$this->author=(isset($data['author']))?$data['author']:null;
		$this->view=(isset($data['view']))?$data['view']:null;
		$this->tag=(isset($data['tag']))?$data['tag']:null;
		$this->image=(isset($data['image']))?$data['image']:null;
		$this->active=(isset($data['active']))?$data['active']:null;
		$this->summary=(isset($data['summary']))?$data['summary']:null;
		$this->summary_en=(isset($data['summary_en']))?$data['summary_en']:null;
		$this->description=(isset($data['description']))?$data['description']:null;
		$this->description_en=(isset($data['description_en']))?$data['description_en']:null;
		$this->meta=(isset($data['meta']))?$data['meta']:null;
		$this->keyword=(isset($data['keyword']))?$data['keyword']:null;
		$this->date=(isset($data['date']))?$data['date']:date_time_set('Y-m-d', 'H', 'I', 'S');
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