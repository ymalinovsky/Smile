<?php
/**
 * Warehouse Index Controller
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @copyright   Copyright (c) 2016 Yan Malinovsky
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * YanMalinovsky_Warehouse_IndexController
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @author      Yan Malinovsky <yan.malinovsky@gmail.com>
 */

class YanMalinovsky_Warehouse_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Index Action
     */
    public function indexAction()
    {
        echo $this->getLayout()->createBlock('yanmalinovsky_warehouse/warehouse')->toHtml();
    }

    /**
     * Upload Action
     */
    public function uploadAction(){
        $fileTmp = $_FILES['filename']['tmp_name'];
        if ($fileTmp){
            Mage::getBlockSingleton('yanmalinovsky_warehouse/warehouse')->saveData($fileTmp);
        }
    }
}
