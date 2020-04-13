<?php
$installer = $this;

$installer->startSetup();
$installer->run("
    CREATE TABLE IF NOT EXISTS {$this->getTable('lotus_paymentfilter/rule')}
    (
      `rule_id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `store_id` VARCHAR(64) NULL,
      `status` int(1) DEFAULT 0,
      `conditions_serialized` TEXT NULL,
      `payment_method` VARCHAR(1024),
      `shipping_method` VARCHAR(1024) NULL,
      `priority` INT(4) DEFAULT 0,
      `stop_rules_processing` INT(1) DEFAULT 0,
      PRIMARY KEY (`rule_id`)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->removeAttribute('catalog_product','lb_paymentfilter_rule');
$installer->removeAttribute('catalog_category','lb_paymentfilter_rule');

$installer->removeAttribute('customer','lb_paymentfilter_rule');

$installer->endSetup();
