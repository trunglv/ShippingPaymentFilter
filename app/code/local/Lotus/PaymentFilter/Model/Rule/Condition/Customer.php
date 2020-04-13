<?php
class Lotus_PaymentFilter_Model_Rule_Condition_Customer extends Mage_Rule_Model_Condition_Abstract {

    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }


    public function loadAttributeOptions()
    {

        $helper = Mage::helper("lotus_paymentfilter");
        $attributes = array(
            'customer_group' => $helper->__('Customer Groups'),
            'customer_ids' => $helper->__('Customers')
        );
        $this->setAttributeOption($attributes);
        return $this;
    }

    public function getValueAfterElementHtml()
    {
        $html = '';

        switch ($this->getAttribute()) {
            case 'customer_group': case 'customer_ids':
            $image = Mage::getDesign()->getSkinUrl('images/rule_chooser_trigger.gif');
            break;
        }

        if (!empty($image)) {
            $html = '<a href="javascript:void(0)" class="rule-chooser-trigger"><img src="' . $image . '" alt="" class="v-middle rule-chooser-trigger" title="' . Mage::helper('rule')->__('Open Chooser') . '" /></a>';
        }
        return $html;
    }

    public function getValueElementChooserUrl()
    {
        $url = false;
        switch ($this->getAttribute()) {
            case 'customer_group': case 'customer_ids':
            $url = 'adminhtml/lotus_customer_chooser/index'
                .'/attribute/'.$this->getAttribute();
            if ($this->getJsFormObject()) {
                $url .= '/form/'.$this->getJsFormObject();
            }
            break;
        }
        return $url!==false ? Mage::helper('adminhtml')->getUrl($url) : '';
    }

    public function getExplicitApply()
    {
        switch ($this->getAttribute()) {
            case 'customer_group': case 'customer_ids':
            return true;
        }
        if (is_object($this->getAttributeObject())) {
            switch ($this->getAttributeObject()->getFrontendInput()) {
                case 'date':
                    return true;
            }
        }
        return false;
    }



}