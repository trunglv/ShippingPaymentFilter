<?php
class Lotus_PaymentFilter_Block_Adminhtml_Payment_Rule_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel("lotus_paymentfilter/rule_collection");

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        $this->addColumn("rule_id", array(
            'header' => "#",
            'index' => 'rule_id'
        ));

        $this->addColumn("name", array(
            'header' => $this->__("Name"),
            'index' => 'name'
        ));
        $this->addColumn("status", array(
            'header' => $this->__("Status"),
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                0 => $this->__('Disabled'),
                1 => $this->__('Enabled')
            )
        ));


    }

    public function getRowUrl($item){
        return $this->getUrl("*/*/edit", array('id' => $item->getId()));
    }

}