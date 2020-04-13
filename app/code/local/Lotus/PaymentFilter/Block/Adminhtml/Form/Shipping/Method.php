<?php
class Lotus_PaymentFilter_Block_Adminhtml_Form_Shipping_Method extends
    Varien_Data_Form_Element_Abstract {

    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        $this->setType('label');
    }

    public function getElementHtml()
    {
        $html = '<input id="'.$this->getHtmlId().'" name="'.$this->getName()
            .'" value="'.$this->getValue().'" '.$this->serialize($this->getHtmlAttributes()).'/>'."\n";
        $html.= $this->getAfterElementHtml();
        return $html;
    }
}