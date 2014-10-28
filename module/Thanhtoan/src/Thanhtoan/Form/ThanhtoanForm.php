<?php
namespace Thanhtoan\Form;
use Zend\Form\Element;
use Zend\Form\Form;
class ThanhtoanForm extends Form{

    public function __construct($name=null)
    {
        parent::__construct('thanhtoan');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' =>'id',
            'attributes'=>array(
                'type' =>'hidden',
            ),
        ));  

        
        $description = new Element\Textarea('description');
        $description->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($description);
        
        $cod = new Element\Checkbox('cod');
        $cod->setAttributes(array('class'=>'ibtn'));
        $this->add($cod);
        
        $coddes = new Element\Textarea('coddes');
        $coddes->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($coddes);
        
        $paybank = new Element\Text('paybank');
        $paybank->setValue(1);
        $this->add($paybank);
        
        $bankdes = new Element\Textarea('bankdes');
        $bankdes->setAttributes(array('class'=>'ckeditor','id'=>'editoar4'));
        $this->add($bankdes);
        
       
        
    }   
}