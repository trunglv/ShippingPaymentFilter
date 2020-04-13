<?php
class Lotus_PaymentFilter_Model_Shipping_Plugin_Abstract extends Varien_Object {

    protected $_configs = false;

    public function getConfigs(){
        return $this->_configs;
    }

    public function getAvailableMethods($isSelect){
        $shippingMethodOptions = array();
        try{
            if($this->_configs){
                foreach($this->_configs as $code => $config){

                    if(!$title = Mage::getStoreConfig("carriers/$code/title"))
                        $title = $code;
                    $rates = Mage::getResourceModel($config['collection']);
                    if(!$rates) continue;
                    $rates->getSelect()->group($config['name_field']);
                    $rates->getSelect()->columns(
                        array(
                            'pk' => new Zend_Db_Expr("GROUP_CONCAT(".$config['pk'].")")
                        )
                    );
                    if($isSelect){
                        $shippingMethodOptions[$code] = array(
                            'label'   => $title,
                            'value' => array(),
                        );
                    }
                    foreach($rates as $rate){
                        //$name = $rate->getData($config['name_field']);
                        $name = $this->getMethodName($rate, $config['name_field'], $code);
                        $methodCode = $config['method_code'];
                        $pks = explode(",", $rate->getData('pk'));
                        $rateCodes = array();
                        foreach($pks as $pk){
                            $rateCodes[] = $methodCode. '_' .$code .'_'. $pk;
                        }
                        if($isSelect){
                            $shippingMethodOptions[$code]['value'][implode("|", $rateCodes)] = array(
                                'value' =>implode("|", $rateCodes),
                                'label' => "{$name}"
                            );
                        }else{
                            $shippingMethodOptions[] = array(
                                'value' => implode("|", $rateCodes),
                                'label' => "[{$title}] - {$name}"
                            );
                        }
                    }
                }
            }

        }catch (Exception $ex){
            Mage::log($ex->getMessage(), null, "payment_filter.log", true);
        }
        return $shippingMethodOptions;
    }

    public function getMethodName($rate, $field, $carrierCode = null){
        return $rate->getData($field);
    }
}