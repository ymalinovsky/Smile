<?php
/**
 * Collection Model
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @copyright   Copyright (c) 2016 Yan Malinovsky
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * YanMalinovsky_Warehouse_Model_Resource_Products_Collection
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @author      Yan Malinovsky <yan.malinovsky@gmail.com>
 */

class YanMalinovsky_Warehouse_Model_Resource_Products_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize collection
     */
    protected function _construct()
    {
        $this->_init('yanmalinovsky_warehouse/products');
    }
}
