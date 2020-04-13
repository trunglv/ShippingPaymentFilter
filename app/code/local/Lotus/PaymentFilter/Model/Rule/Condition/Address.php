<?php
class Lotus_PaymentFilter_Model_Rule_Condition_Address
    extends Mage_SalesRule_Model_Rule_Condition_Address {


    public function loadAttributeOptions()
    {
        $attributes = array(
            'base_subtotal' => Mage::helper('salesrule')->__('Subtotal'),
            'total_qty' => Mage::helper('salesrule')->__('Total Items Quantity'),
            'weight' => Mage::helper('salesrule')->__('Total Weight'),
            'payment_method' => Mage::helper('salesrule')->__('Payment Method'),
            'shipping_method' => Mage::helper('salesrule')->__('Shipping Method'),
            'postcode' => Mage::helper('salesrule')->__('Shipping Postcode'),
            'region' => Mage::helper('salesrule')->__('Shipping Region'),
            'region_id' => Mage::helper('salesrule')->__('Shipping State/Province'),
            'country_id' => Mage::helper('salesrule')->__('Shipping Country'),
            'sale_rule' => Mage::helper('salesrule')->__('Shopping Cart Rule'),
        );

        $this->setAttributeOption($attributes);

        return $this;
    }

    public function getValueSelectOptions()
    {
        /**
         * @var $helper Lotus_PaymentFilter_Helper_Data
         */
        $helper = Mage::helper('lotus_paymentfilter');

        if (!$this->hasData('value_select_options')) {
            switch ($this->getAttribute()) {
                case 'country_id':
                    $options = Mage::getModel('adminhtml/system_config_source_country')
                        ->toOptionArray();
                    break;

                case 'region_id':
                    $options = Mage::getModel('adminhtml/system_config_source_allregion')
                        ->toOptionArray();

                    break;

                case 'shipping_method':
                    $options = $helper->getAllShippingOptions(false);
                    break;
                case 'payment_method':
                    $options = Mage::getModel('lotus_paymentfilter/source_payment_methods')
                        ->toOptionArray();
                    break;
                case 'sale_rule':
                    $saleRules = Mage::getModel('salesrule/rule')->getCollection();
                    $saleRuleOptions = array(
                        array(
                            'value' => 0,
                            'label' => Mage::helper('core')->__('No Select')
                        )
                    );
                    foreach($saleRules as $_rule){
                        $saleRuleOptions[] = array(
                            'value' => $_rule->getId(),
                            'label' => '['.$_rule->getName() . '] ' . $_rule->getDescription(),
                        );
                    }

                    $options = $saleRuleOptions;
                    break;
                default:
                    $options = array();
            }
            $this->setData('value_select_options', $options);
        }
        return $this->getData('value_select_options');
    }

    public function getInputType()
    {
        switch ($this->getAttribute()) {
            case 'base_subtotal': case 'weight': case 'total_qty':
                return 'numeric';
            case 'country_id': case 'region_id':
                return 'select';
            case 'shipping_method':
            case 'payment_method':
            case 'sale_rule':
                return 'multiselect';
        }
        return 'string';
    }

    public function getValueElementType()
    {
        switch ($this->getAttribute()) {
            case 'country_id': case 'region_id':
                return 'select';
            case 'shipping_method':
            case 'payment_method':
            case 'sale_rule':
                return 'multiselect';
        }
        return 'text';
    }

    public function validate(Varien_Object $object)
    {
        if( $this->getAttribute() == 'shipping_method' && $object->getData('shipping_method')){
            $newValues = array();
            $parsedValues = $this->getValueParsed();
            foreach($parsedValues as $_value){
                $children = explode("|", $_value );
                foreach($children as $_child){
                    $newValues[] = $_child;
                }

            }
            $this->setValueParsed($newValues);

        }
        if($this->getAttribute() == 'base_subtotal'){
            if( ! $object->getData('base_subtotal') ){
                $totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();
                if(!empty($totals["subtotal"]))
                    $object->setData('base_subtotal', $totals["subtotal"])  ;
            }
        }

        if($this->getAttribute() == 'sale_rule'){
            if($object instanceof Mage_Sales_Model_Quote_Address ){
                $appliedRuleIds = $object->getQuote()->getAppliedRuleIds();
                if($appliedRuleIds)
                    $object->setData('sale_rule', explode(",", $appliedRuleIds));
                else
                    $object->setData('sale_rule', "");

            }
        }

        return $this->validateAttribute($object->getData($this->getAttribute()));
    }
}