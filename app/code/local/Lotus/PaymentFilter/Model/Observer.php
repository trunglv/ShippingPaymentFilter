<?php
class Lotus_PaymentFilter_Model_Observer {

    public function paymentMethodIsActive($observer){

        if (! Mage::helper('lotus_paymentfilter')->isEnabled())
            return;

        $checkResult = $observer->getEvent()->getResult();
        $method = $observer->getEvent()->getMethodInstance();
        $disabledMethods = array();

        if ($checkResult->isAvailable)
        {
            $rules = Mage::getSingleton('lotus_paymentfilter/validator')
                ->getAppliedRules();
            if($rules){
                foreach($rules as $rule){
                    if($rule->getPaymentMethods())
                        $disabledMethods = array_merge($disabledMethods, $rule->getPaymentMethods());
                }


                if ( $disabledMethods && in_array($method->getCode(), $disabledMethods ))
                {
                    $checkResult->isAvailable = false;
                }
            }


        }
    }
}