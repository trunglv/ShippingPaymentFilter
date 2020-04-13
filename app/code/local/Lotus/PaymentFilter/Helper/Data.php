<?php
class Lotus_PaymentFilter_Helper_Data extends Mage_Core_Helper_Data {
    const XML_PATH_PAYMENT_METHODS = 'payment';
    const XML_PATH_IS_ENABLED = 'lotus_paymentfilter/general/enabled';

    protected $_shippingOptions;

    public function isEnabled($storeId = null){
        return Mage::getStoreConfig(self::XML_PATH_IS_ENABLED, $storeId);
    }
    
    public function getStorePaymentMethods($store = null, $quote = null)
    {
        $res = array();

        foreach ($this->getPaymentMethods($store) as $code => $methodConfig) {
            try{
                $prefix = self::XML_PATH_PAYMENT_METHODS . '/' . $code . '/';

                if (!$model = Mage::getStoreConfig($prefix . 'model', $store)) {
                    continue;
                }

                $methodInstance = Mage::getModel($model);

                if (!Mage::getStoreConfigFlag('payment/'.$code.'/active', $store)) {
                    continue;
                }

                if (!$methodInstance) {
                    continue;
                }
                if ( !$methodInstance->isAvailable($quote) ) {
                    continue;
                }
                $methodInstance->setStore($store);
                if($quote){
                    if (!$methodInstance->isAvailable($quote)) {
                        /* if the payment method cannot be used at this time */
                        continue;
                    }
                }
                $sortOrder = (int)$methodInstance->getConfigData('sort_order', $store);
                $methodInstance->setSortOrder($sortOrder);
                $res[] = $methodInstance;
            }catch (Exception $ex){
                Mage::throwException($ex->getMessage());
            }

        }
        return $res;
    }

    public function getPaymentMethods($store = null)
    {
        if ($store == null) {
            $stores = Mage::app()->getStores();
            $methods = array();
            foreach ($stores as $storeId => $storeItem) {
                //if (in_array(Mage::getStoreConfig(self::XML_PATH_PAYMENT_METHODS, $storeId)))
                $methods = array_merge($methods, Mage::getStoreConfig(self::XML_PATH_PAYMENT_METHODS, $storeId));

            }

            return $methods;
        } else {
            return Mage::getStoreConfig(self::XML_PATH_PAYMENT_METHODS, $store);
        }

    }

    public function getAllShippingOptions($isSelect=false){

        if ($this->_shippingOptions == null){
            $shippingMethodOptions = array();
            $shippingMethods = Mage::getSingleton('shipping/config')->getAllCarriers();
            uasort ($shippingMethods, array($this, 'sortShippingMethods'));
            $plugins = Mage::getSingleton('lotus_paymentfilter/shipping_plugin_manager')->getPlugins();
            $pluginCarriers = array();
            foreach($plugins as $plugin){
                $pluginCarriers = array_merge($pluginCarriers, array_keys($plugin->getConfigs()));
                $shippingMethodOptions = array_merge($shippingMethodOptions, $plugin->getAvailableMethods($isSelect));
            }

            foreach($shippingMethods as $code => $shippingMethod){
                if(in_array($code, $pluginCarriers ))
                    continue;
                if(!$title = Mage::getStoreConfig("carriers/$code/title"))
                    $title = $code;
                try{
                    if($isSelect){
                        $shippingMethodOptions[$code] = array(
                            'label'   => $title,
                            'value' => array(),
                        );
                    }

                    if ($childMethods = $shippingMethod->getAllowedMethods()){
                        foreach ($childMethods as $childCode => $childMethod){
                            if($isSelect){
                                $shippingMethodOptions[$code]['value'][$code .'_'.$childCode] = array(
                                    'value' => $code .'_'.$childCode,
                                    'label' => $childMethod
                                );
                            }else{
                                $shippingMethodOptions[] = array(
                                    'value' => $code .'_'.$childCode,
                                    'label' => "[{$title}] - ".$childMethod
                                );
                            }
                        }
                    }else{

                        $value = $code;

                        $shippingMethodOptions[] = array(
                            'value' => $value,
                            'label' => $title
                        );
                    }
                }catch (Exception $ex){
                    Mage::log($ex->getMessage(), null, "payment_filter.log", true);
                }
            }
            $this->_shippingOptions = $shippingMethodOptions;
        }

        return $this->_shippingOptions;
    }
    public function sortShippingMethods($a , $b){
        $aCode = $a->getId();
        $bCode = $b->getId();
        $aAtice  = (int)Mage::getStoreConfig("carriers/{$aCode}/active");
        $bAtice  = (int)Mage::getStoreConfig("carriers/{$bCode}/active");
        return $aAtice <= $bAtice;
        //exit;
    }


}