<?php
class Lotus_PaymentFilter_Block_Adminhtml_Widget_Chooser_Customer_Group
extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct($arguments=array())
    {
        parent::__construct($arguments);

        if ($this->getRequest()->getParam('current_grid_id')) {
            $this->setId($this->getRequest()->getParam('current_grid_id'));
        } else {
            $this->setId('customerGroupChooserGrid_'.$this->getId());
        }

        $form = $this->getJsFormObject();
        $this->setRowClickCallback("$form.chooserGridRowClick.bind($form)");
        $this->setCheckboxCheckCallback("$form.chooserGridCheckboxCheck.bind($form)");
        $this->setRowInitCallback("$form.chooserGridRowInit.bind($form)");
        $this->setDefaultSort('customer_group_id');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/group_collection');

        //print_r($collection->getData());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){

        $this->addColumn('in_selected', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_selected',
            'values'    => $this->_getSelectedItems(),
            'align'     => 'center',
            'index'     => 'customer_group_id',
            'use_index' => true,
        ));

        $this->addColumn('customer_group_code', array(
            'header' => 'Group',
            'index' => 'customer_group_code'
        ));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array(
            '_current'          => true,
            'current_grid_id'   => $this->getId(),
            'collapse'          => null
        ));
    }

    protected function _getSelectedItems()
    {
        $selecteds = $this->getRequest()->getPost('selected', array());

        return $selecteds;
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_selected') {
            $selected = $this->_getSelectedItems();
            if (empty($selected)) {
                $selected = '';
            }

            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('customer_group_id', array('in'=>$selected));
            } else {
                $this->getCollection()->addFieldToFilter('customer_group_id', array('nin'=>$selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}