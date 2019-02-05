<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_ProductLabels
 * @version     1.0.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */

class Apptha_Productlabels_Model_Conditions extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productlabels/conditions');
    }
    
   	public function addLabel(Apptha_Productlabels_Model_Productlabels $_label,$productIds = array()) {
   		
   		$this->getCollection()->deleteOldConditions($_label->getId(),$productIds);
   		
   		foreach ($productIds as $_productId){
   			
   			$_labelId=$_label->getId(); 			
   			$data = $_label->getData();
   			unset($data['id']);
   			$data['label_id']=$_labelId;
   			$data['product_id'] = $_productId;   			
   			$this->setData($data);	 
   			$this->save();
   			$this->unsetData();
   		}
   		
   	}   	
   	public function getLabels($productId)
   	{
   		$today = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
   		$collection_condition =  $this->getCollection()
   			->addFieldToFilter('product_id',array('eq'=>$productId))   		   		
			->addFieldToFilter('fromdate',array('lteq'=>$today))
			->addFieldToFilter('todate',array('gteq'=>$today))
   			->addFieldToFilter('rulestatus',array('eq'=>1));
		
   		
   		$labels = array();	
   		foreach ($collection_condition as $condition){
   			$labels[] = $condition->getLabelId(); 
   		}
   		
   		return $labels;
   	}
    
}