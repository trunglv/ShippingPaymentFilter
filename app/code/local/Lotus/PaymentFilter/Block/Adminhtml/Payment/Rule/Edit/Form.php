<?php
class Lotus_PaymentFilter_Block_Adminhtml_Payment_Rule_Edit_Form extends
    Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm(){

        $form = new Varien_Data_Form (array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array(
                'id' => $this->getRequest()->getParam('id')
            )),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('paymentfiler_rule_form', array(
            'legend' => $this->__('General')
        ));

        $fieldset->addField('name', 'text', array(
            'label' => $this->__('Rule Name'),
            'required' => true,
            'name' => 'rule[name]'
        ));
        $fieldset->addField('store_id', 'multiselect', array(
            'name' => 'rule[store_id]',
            'required' => true,
            'class' => 'validate-select validate-select-store',
            'label' => $this->__('Store'),
            'title' => $this->__('Store'),
            'values' => Mage::getSingleton('adminhtml/system_store')
                ->getStoreValuesForForm(false, true),
        ))
        ;
        $stores = Mage::app()->getStores();
        $methods = array();

        foreach ($stores as $storeId => $storeItem){
            //$methods = array_merge($methods, );
            $methodsOfStore = Mage::helper('lotus_paymentfilter')->getStorePaymentMethods($storeId);
            foreach ($methodsOfStore as $mt){
                if (!array_key_exists($mt->getCode(), $methods)){
                    $methods[$mt->getCode()] = $mt;
                }
            }
        }

        $methodValues = array();
        foreach ($methods as $method){
            $methodValues[] = array(
                'label' => $method->getTitle(),
                'value' => $method->getCode(),
            );
        }

        $fieldset->addField('payment_method','checkboxes',
            array (
                'label' => $this->__ ( 'The Filter Rule will disable these Payment methods' ),
                'required' => true,
                'name' => 'rule[payment_method][]',
                'values' => $methodValues
            ));
        $helper = Mage::helper('lotus_paymentfilter');
        $shippingMethodOptions = $helper->getAllShippingOptions();


        //$fieldset->addType('shipping_method','Lotus_PaymentFilter_Block_Adminhtml_Form_Shipping_Method');

        $fieldset->addField('shipping_method','checkboxes',
            array (
                'label' => $helper->__ ( 'The Filter Rule will disable these Shipping methods' ),
                'name' => 'rule[shipping_method][]',
                'values' => $shippingMethodOptions,
                'after_element_html' => "<style> .checkboxes{padding: 5px ;max-height: 150px; overflow-y: scroll; border : 1px solid #c0c0c0}</style>"
            ));


        $fieldset->addField('status', 'select', array(
            'name' => 'rule[status]',
            'label' => $this->__('Status'),
            'title' => $this->__('Status'),
            'values' => array(0 => $this->__('Disabled'), 1=> $this->__('Enabled')),
            'value' => 1
        ));

        $fieldset->addField('stop_rules_processing', 'select', array(
            'name' => 'rule[stop_rules_processing]',
            'label' => $this->__('Stop Further Rule Processing'),
            'title' => $this->__('Status'),
            'values' => array(0 => $this->__('No'), 1=> $this->__('Yes')),
            'value' => 1
        ));
        $fieldset->addField('priority', 'text', array(
            'label' => $this->__('Priority'),
            'required' => true,
            'name' => 'rule[priority]',
            'class' => 'validate-number'
        ));

        $model = Mage::registry('paymentfilter_rule');
        if(!$model)
            $model = Mage::getModel("lotus_paymentfilter/rule");

        $this->initFormCondition($form, $model);
        $form->setUseContainer(true);
        $formData = $model->getData();
        $formData['payment_method'] = $model->getPaymentMethods();
        $formData['shipping_method'] = $model->getShippingMethods();
        $formData['store_id'] = explode(',', $model->getStoreId());
        $form->setValues($formData);

        $this->setForm($form);
        return parent::_prepareForm();
        //return $this;
    }

    public function initFormCondition($form, $model)
    {

        $model->getConditions()->setJsFormObject('lb_conditions_fieldset');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('adminhtml/promo_quote/newConditionHtml/form/lb_conditions_fieldset'));

        $fieldset = $form->addFieldset('lb_conditions_fieldset', array(
            'legend'=>Mage::helper('lotus_paymentfilter')->
            __("Filter Conditions")
        ))->setRenderer($renderer);



        $element = $fieldset->addField('conditions', 'text', array(
            'name' => 'rule[conditions]',
            'label' => Mage::helper('lotus_paymentfilter')->__('Apply To'),
            'title' => Mage::helper('lotus_paymentfilter')->__('Apply To'),
            'required' => true,

        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        //Mage::dispatchEvent('adminhtml_block_salesrule_actions_prepareform', array('form' => $form));

    }
}