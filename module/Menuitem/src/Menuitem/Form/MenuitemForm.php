<?php
namespace Menuitem\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class MenuitemForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('menuitem');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        $idmenu = new Element\Select('idmenu');
        $this->add($idmenu);
        
        $name = new Element\Text('name');
        $name->setAttributes(array('class'=>'validate[required]'));
        $this->add($name);
        
        $alias = new Element\Text('alias');
        $this->add($alias);
        
        $link = new Element\Text('link');
        $this->add($link);
        
        $parent = new Element\Select('parent');
        $this->add($parent);
        
        $order = new Element\Text('order');
        $this->add($order);
        
        $icon = new Element\Text('icon');
        $this->add($icon);
        
        $click = new Element\Select('click');
        $click->setValue(array('0'=>'Mở ra tab hiện tại', '1'=>'Mở ra tab mới','2'=>'Mở ra cửa sổ mới'));
        $this->add($click);
        
        $nofollow = new Element\Checkbox('nofollow');
        $this->add($nofollow);
        
        $active = new Element\Select('active');
        $active->setValue(array('0'=>'Xuất bản', '1'=>'Chưa công bố'));
        $this->add($active);
        
    }   
}