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

class Apptha_Productlabels_Model_Observer {
	
        public function controllerActionLayoutLoadBefore(Varien_Event_Observer $observer)
        {
            /** @var $layout Mage_Core_Model_Layout */
            $layout = $observer->getEvent()->getLayout();
 			$action = $observer->getEvent()->getAction();
 			$storeId = Mage::app()->getStore()->getId();
 			
            $package = Mage::getSingleton('core/design_package');
            
            $layout_update_default = $package->getArea().'_default_default_'.$action->getFullActionName();
            
           	$layout_update = $package->getArea().'_'.$package->getPackageName().'_'.$package->getTheme('layout').'_'.$action->getFullActionName();
            
           	$layout_xml_array = $layout->getUpdate()->getFileLayoutUpdatesXml(
			            		$package->getArea(),
			            		$package->getPackageName(),
			            		$package->getTheme('layout'),
			            		$storeId
			            )->asArray();
           	
            if((array_key_exists($layout_update, $layout_xml_array))){
            	$layout->getUpdate()->addHandle(strtolower($layout_update));
            }else{
            	$layout->getUpdate()->addHandle(strtolower($layout_update_default));
            }
                        
        }
                        
        public function catalogProductSaveAfter($observer)
        {
        	$product = $observer->getProduct();
        	
        	$model = Mage::getModel('productlabels/productlabels');
        		
        	$model->getLabels(false,$product->getId(),null);
        	
        }
        
	
}