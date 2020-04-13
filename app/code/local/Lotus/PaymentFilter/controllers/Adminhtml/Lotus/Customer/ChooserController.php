<?php
class Lotus_PaymentFilter_Adminhtml_Lotus_Customer_ChooserController
    extends Mage_Adminhtml_Controller_Action {

    protected function _isAllowed(){
        return true;
    }


    public function indexAction(){
        $request = $this->getRequest();

        switch ($request->getParam('attribute')) {
            case 'customer_group':
                $block = $this->getLayout()->createBlock(
                    'lotus_paymentfilter/adminhtml_widget_chooser_customer_group', 'widget_chooser_customer_group',
                    array('js_form_object' => $request->getParam('form'),
                    ));

                break;
            case 'customer_ids':
                $block = $this->getLayout()->createBlock(
                    'lotus_paymentfilter/adminhtml_widget_chooser_customer', 'widget_chooser_customer',
                    array('js_form_object' => $request->getParam('form'),
                    ));

                break;
        }
        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }

    }
}