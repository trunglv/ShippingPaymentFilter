<?php
class Lotus_PaymentFilter_Block_Adminhtml_Widget_Chooser_Customer
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct($arguments=array())
    {
        parent::__construct($arguments);

        if ($this->getRequest()->getParam('current_grid_id')) {
            $this->setId($this->getRequest()->getParam('current_grid_id'));
        } else {
            $this->setId('customersChooserGrid_'.$this->getId());
        }

        $form = $this->getJsFormObject();
        $this->setRowClickCallback("$form.chooserGridRowClick.bind($form)");
        $this->setCheckboxCheckCallback("$form.chooserGridCheckboxCheck.bind($form)");
        $this->setRowInitCallback("$form.chooserGridRowInit.bind($form)");
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('collapse')) {
            $this->setIsCollapsed(true);
        }
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection');
        $collection->addNameToSelect();
        $collection->addAttributeToSelect('group_id');
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
            'index'     => 'entity_id',
            'use_index' => true,
        ));

        $this->addColumn('name', array(
            'header' => $this->__("Name"),
            'index' => 'name'
        ));

        $this->addColumn('email', array(
            'header' => $this->__("Name"),
            'index' => 'email'
        ));

        $this->addColumn('email', array(
            'header' => $this->__("Name"),
            'index' => 'email'
        ));

        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        $this->addColumn('group', array(
            'header'    =>  Mage::helper('customer')->__('Group'),
            'width'     =>  '100',
            'index'     =>  'group_id',
            'type'      =>  'options',
            'options'   =>  $groups,
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('website_id', array(
                'header'    => Mage::helper('customer')->__('Website'),
                'align'     => 'center',
                'width'     => '80px',
                'type'      => 'options',
                'options'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteOptionHash(true),
                'index'     => 'website_id',
            ));
        }
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
        $products = $this->getRequest()->getPost('selected', array());
        return $products;
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
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$selected));
            } else {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$selected));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}