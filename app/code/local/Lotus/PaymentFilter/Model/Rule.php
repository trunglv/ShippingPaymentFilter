<?php
class Lotus_PaymentFilter_Model_Rule extends Mage_Rule_Model_Abstract {

    public function _construct(){
        $this->_init('lotus_paymentfilter/rule');
    }

    public function getActionsInstance()
    {
        return Mage::getModel('lotus_paymentfilter/rule_combine');
    }

    public function getConditionsInstance()
    {
        return Mage::getModel('lotus_paymentfilter/rule_combine');
    }

    public function getPaymentMethods(){
        if($paymentMethod = $this->getData('payment_method'))
        return explode(",", $paymentMethod);
        return false;
    }

    public function getShippingMethods(){
        if($shippingMethod = $this->getData('shipping_method'))
            return explode(",", $shippingMethod);

        return false;
    }

    public function getStoreIds(){

        if($storeId = $this->getData('store_id'))
            return explode(",", $storeId);

        return false;
    }


}