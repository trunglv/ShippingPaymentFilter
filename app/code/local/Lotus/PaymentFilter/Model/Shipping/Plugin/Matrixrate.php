<?php
class Lotus_PaymentFilter_Model_Shipping_Plugin_Matrixrate extends Lotus_PaymentFilter_Model_Shipping_Plugin_Abstract {

    protected $_configs = array(
        'matrixrate' =>
            array(
                'collection' => 'matrixrate_shipping/carrier_matrixrate_collection',
                'name_field' => 'delivery_type',
                'method_code' => 'matrixrate',
                'pk' => 'pk'
            )
    );
}