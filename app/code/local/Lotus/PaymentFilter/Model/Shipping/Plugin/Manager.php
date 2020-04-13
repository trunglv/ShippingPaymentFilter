<?php
class Lotus_PaymentFilter_Model_Shipping_Plugin_Manager extends Varien_Object
{
    public function getPlugins(){
        return array(
            Mage::getSingleton('lotus_paymentfilter/shipping_plugin_matrixrate'),
            Mage::getSingleton('lotus_paymentfilter/shipping_plugin_tablerate')
        );
    }
}
