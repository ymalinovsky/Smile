<?php
/**
 * Warehouse Main Block
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @copyright   Copyright (c) 2016 Yan Malinovsky
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * YanMalinovsky_Warehouse_Block_Warehouse
 *
 * @category    YanMalinovsky
 * @package     YanMalinovsky_Warehouse
 * @author      Yan Malinovsky <yan.malinovsky@gmail.com>
 */

class YanMalinovsky_Warehouse_Block_Warehouse extends Mage_Core_Block_Template
{
    /**
     * Constructor
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('yanmalinovsky_warehouse/warehouse.phtml');
    }

    /**
     * Get All Collection
     *
     * @return YanMalinovsky_Warehouse_Model_Resource_Warehouse_Collection
     */
    public function getAllCollection(){
        return Mage::getResourceModel('yanmalinovsky_warehouse/warehouse_collection');
    }

    /**
     * Parse And Save Data
     *
     * @param string $file
     * @throws Exception
     */
    public function saveData($file){
        $warehouseModel = Mage::getModel('yanmalinovsky_warehouse/warehouse');
        $productsModel = Mage::getModel('yanmalinovsky_warehouse/products');
        $csv = new Varien_File_Csv();
        $fileData = $csv->getData($file);
        $isSetFirstWarehouseData = false;
        for ($i = 1; $i < count($fileData); $i++){
            $warehouseCollection = $warehouseModel->getCollection()->addFieldToFilter('product_name', array('eq' => $fileData[$i][0]));
            if ($warehouseCollection->getData()){
                $isSetFirstWarehouseData = true;
                $isCsvRowSave = false;
                foreach ($warehouseCollection as $warehouses){
                    $warehouseArray = explode(',', $warehouses->getWarehouse());
                    foreach ($warehouseArray as $warehouse){
                        $products = $productsModel->getCollection()
                            ->addFieldToFilter('warehouse', array('eq' => $warehouse))
                            ->addFieldToFilter('product_name', array('eq' => $warehouses->getProductName()))
                            ->getFirstItem();
                        if ($products->getData() && $products->getWarehouse() == $fileData[$i][2]){
                            $isCsvRowSave = true;
                            if ($fileData[$i][1] + $products->getQty() > 0){
                                $data = array(
                                    'id' => $products->getId(),
                                    'product_name' => $products->getProductName(),
                                    'qty' => $fileData[$i][1] + $products->getQty(),
                                    'warehouse' => $products->getWarehouse()
                                );
                                $productsModel->setData($data)->save();
                            } else {
                                $productsModel->setId($products->getId())->delete();
                            }
                        }
                    }
                }
                if (!$isCsvRowSave){
                    $products = $productsModel->getCollection()
                        ->addFieldToFilter('warehouse', array('eq' => $fileData[$i][2]))
                        ->addFieldToFilter('product_name', array('eq' => $fileData[$i][0]))
                        ->getFirstItem();
                    if ($products->getData()){
                        if ($fileData[$i][1] + $products->getQty() > 0){
                            $data = array(
                                'id' => $products->getId(),
                                'product_name' => $products->getProductName(),
                                'qty' => $fileData[$i][1] + $products->getQty(),
                                'warehouse' => $products->getWarehouse()
                            );
                            $productsModel->setData($data)->save();
                        } else {
                            $productsModel->setId($products->getId())->delete();
                        }
                    } else {
                        $data = array(
                            'product_name' => $fileData[$i][0],
                            'qty' => $fileData[$i][1],
                            'warehouse' => $fileData[$i][2]
                        );
                        $productsModel->setData($data)->save();
                    }
                }
            } else {
                $data = array(
                    'product_name' => $fileData[$i][0],
                    'qty' => $fileData[$i][1],
                    'warehouse' => $fileData[$i][2]
                );
                $warehouseModel->setData($data)->save();
                $productsModel->setData($data)->save();
            }
        }
        if ($isSetFirstWarehouseData){
            $warehouseCollection = $warehouseModel->getCollection();
            foreach ($warehouseCollection as $warehouse){
                $productsCollection = $productsModel->getCollection()
                    ->addFieldToFilter('product_name', array('eq' => $warehouse->getProductName()));
                $dataQty = 0;
                $dataWarehouses = '';
                foreach ($productsCollection as $product){
                    $dataQty += $product->getQty();
                    $dataWarehouses .= "{$product->getWarehouse()}, ";
                }
                $data = array(
                    'id' => $warehouse->getId(),
                    'product_name' => $warehouse->getProductName(),
                    'qty' => $dataQty,
                    'warehouse' => substr($dataWarehouses, 0, -2)
                );
                $warehouseModel->setData($data)->save();
            }
        }
        Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getBaseUrl().'warehouse');
    }
}