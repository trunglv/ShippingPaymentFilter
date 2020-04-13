<?php
class Lotus_PaymentFilter_Model_Resource_Rule_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract {

    public function _construct(){
        $this->_init('lotus_paymentfilter/rule', 'lotus_paymentfilter/rule');
    }
}