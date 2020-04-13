<?php
class Lotus_PaymentFilter_Model_Shipping_Plugin_Tablerate extends Lotus_PaymentFilter_Model_Shipping_Plugin_Abstract {

    protected $_configs = array(
        'tablerate' =>
            array(
                'collection' => 'shipping/carrier_tablerate_collection',
                'name_field' => 'condition_name',
                'method_code' => 'tablerate',
                'pk' => 'pk'
            )
    );

    public function getMethodName($rate, $field, $carrierCode = null){
        return Mage::getStoreConfig("carriers/$carrierCode/name");
    }
}