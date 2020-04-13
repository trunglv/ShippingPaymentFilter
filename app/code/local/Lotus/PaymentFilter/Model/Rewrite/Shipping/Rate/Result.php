<?php
class Lotus_PaymentFilter_Model_Rewrite_Shipping_Rate_Result extends Mage_Shipping_Model_Rate_Result {

    public function append($result)
    {

        $rules = Mage::getSingleton('lotus_paymentfilter/validator')
            ->getAppliedRules();

        if($rules != false){
            $disabledMethods = array();
            foreach($rules as $rule){
                $disabledMethods = array_merge($disabledMethods, $rule->getShippingMethods() ? $rule->getShippingMethods() : array());
            }
            $allDisabledMethods = array();
            foreach($disabledMethods as $_disabledMethod){
                $parseMethods = explode("|", $_disabledMethod);
                $allDisabledMethods = array_merge($parseMethods, $allDisabledMethods);

            }

            if($result instanceof Mage_Shipping_Model_Rate_Result){
                $rates = $result->getAllRates();
                foreach ($rates as $rate) {

                    $method = $rate ? $rate->getcarrier().'_'.$rate->getMethod() : NULLL;

                    if (!in_array($method,$allDisabledMethods)) {
                        $this->append($rate);
                    }
                }
            }else{
                return parent::append($result);
            }

        }else{
            return parent::append($result);
        }

        return $this;
    }
}