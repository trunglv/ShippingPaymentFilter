<?php
class Lotus_PaymentFilter_Model_Rule_Condition_Customer_Group extends Mage_Rule_Model_Condition_Abstract {

    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }


    public function loadAttributeOptions()
    {
        $helper = Mage::helper("lotus_paymentfilter");

        $attributes = array(
            'customer_group' => $helper->__('Customer Group'),
            'customer' => $helper->__('Customer')
        );
        $this->setAttributeOption($attributes);
        return $this;
    }

    public function getNewChildSelectOptions(){

    }


}