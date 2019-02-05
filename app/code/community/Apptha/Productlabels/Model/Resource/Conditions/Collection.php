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

class Apptha_Productlabels_Model_Resource_Conditions_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productlabels/conditions');
    }
    
    /**
     * Delete all the condition with the given label id
     *
     * @param int $label_id
     * @return Apptha_Productlabels_Model_Resource_Conditions_Collection
     */
    public function deleteOldConditions($label_id,$productIds){
    	 
    	$this
	    	->addFieldToFilter('product_id',array('in'=>$productIds))
	    	->addFieldToFilter('label_id',$label_id)
	    	->delete();
			
    }
    
    /**
     * Delete all the entities in the collection
     *
     * @todo make batch delete directly from collection
     */
    
    public function deleteOldLabels($label_id){
    
    	$this    	
    	->addFieldToFilter('label_id',$label_id)
    	->delete();
    		
    }
    
    
    public function delete()
    {
    	foreach ($this->getItems() as $k=>$item) {
    		$item->delete();
    		unset($this->_items[$k]);
    	}
    	return $this;
    }
}