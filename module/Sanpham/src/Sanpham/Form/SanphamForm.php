<?php
namespace Sanpham\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class SanphamForm extends Form{

    public function __construct($name=null)
    {
    	parent::__construct('sanpham');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
            'type' =>'hidden',
            ),
        ));  

        
        $proid = new Element\Text('proid');
        $proid->setAttributes(array('class'=>'validate[required]'));
        $this->add($proid);
        
        $proname = new Element\Text('proname');
        $proname->setAttributes(array('class'=>'validate[required]'));
        $this->add($proname);
        
        $alias = new Element\Text('alias');
        $alias->setAttributes(array('class'=>'validate[required]'));
        $this->add($alias);
        
        $order = new Element\Text('order');
        $this->add($order);
        
        $name = new Element\Text('name');
        $name->setAttributes(array('class'=>'validate[required]'));
        $this->add($name);
        
        $price = new Element\Text('price');
        $price->setAttributes(array('class'=>'validate[required,custom[number]]'));
        $this->add($price);
        
        $pricesales = new Element\Text('pricesales');
        $pricesales->setAttributes(array('class'=>'validate[custom[number]]'));
        $this->add($pricesales);
        
        $salesto = new Element\Text('salesto');
        $salesto->setAttributes(array('id'=>'mask_date'));
        $this->add($salesto);
        
        $salesfrom = new Element\Text('salesfrom');
        $salesfrom->setAttributes(array('id'=>'mask_date'));
        $this->add($salesfrom);
        
        $vat = new Element\Checkbox('vat');
        $vat->setAttributes(array('class'=>'ibtn'));
        $this->add($vat);
        
        $district = new Element\Select('district');
        $district->setValueOptions(array('0'=>'Toàn quốc','1'=>'Miền Nam','2'=>'Miền Bắc','3'=>'Miền Trung'));
        $this->add($district);
        
        $tag = new Element\Text('tag');
        $tag->setAttributes(array('class'=>'tags'));
        $this->add($tag);
        
        $active = new Element\Select('active');
        $active->setValueOptions(array('1'=>'Còn hàng','0'=>'Hết hàng','2'=>'Đang nhập hàng','3'=>'Hàng đang về'));
        $this->add($active);
        
        $video = new Element\Text('video');
        $this->add($video);
        
        $quantity = new Element\Text('quantity');
        $this->add($quantity);
        
        $pronew = new Element\Checkbox('pronew');
        $this->add($pronew);
        
        $proselling = new Element\Checkbox('proselling');
        $this->add($proselling);
        
        $procomming = new Element\Checkbox('procomming');
        $this->add($procomming);
        
        $proindex = new Element\Checkbox('proindex');
        $this->add($proindex);
        
        $image = new Element\Text('image');
        $image->setAttributes(array('id'=>'xImagePath'));
        $this->add($image);
        
        $summary = new Element\Textarea('summary');
        $summary->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($summary);
        
        $description = new Element\Textarea('description');
        $description->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($description);
        
        $meta = new Element\Textarea('meta');
        $this->add($meta);
        
        $keyword = new Element\Text('keyword');
        $keyword->setAttributes(array('class'=>'tags'));
        $this->add($keyword);
       
    }   
}