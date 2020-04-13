<?php
class Lotus_PaymentFilter_Block_Adminhtml_Payment_Rule_Edit extends
Mage_Adminhtml_Block_Widget_Form_Container {
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'lotus_paymentfilter';
        $this->_controller = 'adminhtml_payment_rule';


        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "


            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }


        ";
    }

    public function getHeaderText(){
        $model = Mage::registry('paymentfilter_rule');
        if(!$model){
            return $this->__("Add New Filter Rule");
        }
        else{
            return $this->__("Edit Filter Rule [%s: %s]", $model->getId(), $model->getName());
        }

    }

}