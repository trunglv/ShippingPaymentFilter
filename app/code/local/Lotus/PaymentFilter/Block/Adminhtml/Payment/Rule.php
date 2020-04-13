<?php
class Lotus_PaymentFilter_Block_Adminhtml_Payment_Rule
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct(){
        $this->_controller = 'adminhtml_payment_rule';
        $this->_blockGroup = 'lotus_paymentfilter';
        $this->_headerText = Mage::helper('lotus_paymentfilter')->__('Payment and Shipping method Filter Rule');
        parent::__construct();
    }
}