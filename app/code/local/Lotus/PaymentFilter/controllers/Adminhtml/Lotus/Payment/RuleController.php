<?php
class Lotus_PaymentFilter_Adminhtml_Lotus_Payment_RuleController
    extends Mage_Adminhtml_Controller_Action {

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/lotus_paymentfilter');
    }

    public function _init(){
        $this->_setActiveMenu("sales");
        $this->_title($this->__('Payment Filter'))
            ->_title($this->__('Manage Rules'));
    }

    public function indexAction(){
        //echo $this->getFullActionName();

        $this->loadLayout();
        $this->_init();
        $this->renderLayout();
    }

    public function newAction(){
        $this->_forward('edit');
    }

    public function editAction(){

        $id = $this->getRequest()->getParam('id', false);
        if($id){
            $rule = Mage::getModel("lotus_paymentfilter/rule")->load($id);
            Mage::register('paymentfilter_rule', $rule);
        }
        $this->loadLayout();
        $this->_init();
        $this->renderLayout();
    }

    public function saveAction(){

        $data = $this->getRequest()->getParam('rule');
        $helper = Mage::helper("lotus_paymentfilter");
        if($data['store_id']){
            $data['store_id'] = implode(",", $data['store_id']);
        }

        if(!empty($data['payment_method'])){
            $data['payment_method'] = implode(",", $data['payment_method']);
        }
        if(!empty($data['shipping_method'])){
            $data['shipping_method'] = implode(",", $data['shipping_method']);
        }

        /*
        if(!empty($data['store_id'])){
            $data['store_id'] = implode(",", $data['store_id']);
        }
        */

        try{
            if($id = $this->getRequest()->getParam('id')){
                $rule = Mage::getModel('lotus_paymentfilter/rule')->load($id);
            }else{
                $rule = Mage::getModel('lotus_paymentfilter/rule');
            }
            //print_r($data);exit;
            $rule->addData($data)
                //->setConditions($data['conditions'])
                ->loadPost($data)
                ->save();
            Mage::getSingleton("core/session")->addSuccess($helper->__("Rule is saved successfully"));


        }catch (Exception $ex){
            Mage::getSingleton("core/session")->addError($helper->__("Rule is saved failure"));
        }
        if($this->getRequest()->getParam('back') == 'edit'){
            $this->_redirect("*/*/edit", array('_current' => true, 'id' => $rule->getId()));
        }else{
            $this->_redirect("*/*/index");
        }
    }

    public function deleteAction(){
        $helper = Mage::helper("lotus_paymentfilter");
        if($id = $this->getRequest()->getParam('id')){
            $rule = Mage::getModel("lotus_paymentfilter/rule")->load($id);
            if($rule){
                $rule->delete();
                Mage::getSingleton("core/session")->addSuccess($helper->__("Rule is deleted successfully"));
            }else{
                Mage::getSingleton("core/session")->addError($helper->__("Rule is not found"));
            }

        }
        $this->_redirect("*/*/index");

    }

}