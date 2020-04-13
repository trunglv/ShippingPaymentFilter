<?php
class Lotus_PaymentFilter_Model_Resource_Rule extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct(){
        $this->_init('lotus_paymentfilter/rule', 'rule_id');
    }
}