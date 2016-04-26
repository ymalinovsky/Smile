<?php
/**
 * Warehouse related module
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @copyright   Copyright (c) 2016 Yan Malinovsky
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();

$table = 'yanmalinovsky_warehouse';

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable($table)};
	CREATE TABLE {$this->getTable($table)} (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
	  `product_name` varchar(255) NOT NULL,
	  `qty` int NOT NULL,
	  `warehouse` varchar(255) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$table = 'yanmalinovsky_warehouse_products';

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable($table)};
	CREATE TABLE {$this->getTable($table)} (
	  `id` int(10) NOT NULL AUTO_INCREMENT,
      `warehouse` varchar(255) NOT NULL,
	  `product_name` varchar(255) NOT NULL,
	  `qty` int NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();