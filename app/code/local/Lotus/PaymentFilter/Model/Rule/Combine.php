<?php
class Lotus_PaymentFilter_Model_Rule_Combine extends Mage_Rule_Model_Condition_Combine {
    public function __construct()
    {
        parent::__construct();
        $this->setType('lotus_paymentfilter/rule_condition_combine');
    }

    public function getNewChildSelectOptions()
    {
        $addressCondition = Mage::getModel('lotus_paymentfilter/rule_condition_address');
        $addressAttributes = $addressCondition->loadAttributeOptions()->getAttributeOption();
        $attributes = array();
        foreach ($addressAttributes as $code=>$label) {
            $attributes[] = array('value'=>'lotus_paymentfilter/rule_condition_address|'.$code, 'label'=>$label);
        }

        $customerCondition = Mage::getModel("lotus_paymentfilter/rule_condition_customer");
        $customerAttributes = $customerCondition->loadAttributeOptions()->getAttributeOption();
        foreach($customerAttributes as $code => $label){
            $cutomerAttrs[] = array('value'=>'lotus_paymentfilter/rule_condition_customer|'.$code, 'label'=>$label);
        }

        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array('value'=>'salesrule/rule_condition_product_found', 'label'=>Mage::helper('lotus_paymentfilter')->__('Product attribute combination')),
            array('value'=>'salesrule/rule_condition_product_subselect', 'label'=>Mage::helper('lotus_paymentfilter')->__('Products subselection')),

            array('value' => $cutomerAttrs, 'label'=>Mage::helper('lotus_paymentfilter')->__('Customers')),
            array('value'=>'salesrule/rule_condition_combine', 'label'=>Mage::helper('lotus_paymentfilter')->__('Conditions combination')),
            array('label'=>Mage::helper('lotus_paymentfilter')->__('Cart Attribute'), 'value'=>$attributes),

        ));

        $additional = new Varien_Object();
        Mage::dispatchEvent('salesrule_rule_condition_combine', array('additional' => $additional));
        if ($additionalConditions = $additional->getConditions()) {
            $conditions = array_merge_recursive($conditions, $additionalConditions);
        }

        return $conditions;
    }
}