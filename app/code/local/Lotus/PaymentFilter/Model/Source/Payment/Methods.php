<?php
class Lotus_PaymentFilter_Model_Source_Payment_Methods extends Mage_Adminhtml_Model_System_Config_Source_Payment_Allmethods {

        public function toOptionArray()
    {
        $methods = array(array('value'=>'', 'label'=>''));
        $payments = Mage::getSingleton('payment/config')->getActiveMethods();
        foreach ($payments as $paymentCode=>$paymentModel) {
            $paymentTitle = Mage::getStoreConfig('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = array(
                'label'   => $paymentTitle,
                'value' => $paymentCode,
            );
        }

        return $methods;
    }
}