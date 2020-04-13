<?php
/*
Lotus Breath - store.lotusbreath.com
Copyright (C) 2016  Lotus Breath
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class Lotus_PaymentFilter_Model_Validator {

    protected $_appliedRules = null;
    public function getAppliedRules(){
        /**
         * check quote has already sale rule. If yes, we will continue to check s-p filter rules
         */
        if( !Mage::getSingleton('checkout/session')->getQuote()->getAppliedRuleIds() ){
            if($this->_appliedRules != null)
                return $this->_appliedRules;
        }else{
            $this->_appliedRules = false;
        }

        /*
        if($this->_appliedRules !== null){
            return $this->_appliedRules;
        }
        */

        $quote = Mage::getSingleton('checkout/cart')->getQuote();
        $rules = Mage::getModel("lotus_paymentfilter/rule")
            ->getCollection()
            ->addFieldToFilter('status', 1)
        ;
        $rules->getSelect()->order('priority ASC');
        $appliedRules = array();
        foreach($rules as $rule){
            $currentStoreId = $quote->getStoreId();
            if($rule->getStoreIds() && !in_array($currentStoreId, $rule->getStoreIds())){
                continue;
            }
            if ($quote->isVirtual()) {
                $address = $quote->getBillingAddress();
            } else {
                $address = $quote->getShippingAddress();
            }
            //$address = $quote->getShippingAddress();
            $customer = Mage::getSingleton("customer/session")->getCustomer();
            if($customer->getId())
                $address->setData('customer_group', $customer->getGroupId());
            else
                $address->setData('customer_group', 0);

            if($customer->getId())
                $address->setData('customer_ids', $customer->getId());

            if($rule->validate($address)){
                $appliedRules[] = $rule;
                /**
                 * Stop check other rules ( this rule is matched conditions )
                 */
                if($rule->getStopRulesProcessing())
                    break;
            }
        }
        if(count($appliedRules))
            $this->_appliedRules = $appliedRules;
        else{
            $this->_appliedRules = false;
        }
        return $this->_appliedRules;
    }
}