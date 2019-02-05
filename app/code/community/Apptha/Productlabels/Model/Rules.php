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

class Apptha_Productlabels_Model_Rules extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('productlabels/rules');
    }
    
    /**
     * Get attribute frontend label
     * 
     * @return frontend label
     */
    public function getAttributeLabel(){
    	
    	$attr = Mage::getModel('eav/entity_attribute')->load($this->getAttribute(),'attribute_code');    	    	
    	umask(0);  
    	Mage::app('admin');    	
    	$changeName=$attr->getFrontendLabel()?:$this->getAttribute();
     	if($changeName=='entity_id') {    		
     		$changeName='Name';
     		return $changeName;
     	}
     	 else if($changeName=='category_id') {
     		$changeName='Category';
     		return $changeName;
     	}	    	
    	return uc_words(($attr->getFrontendLabel())?:$this->getAttribute());
    }
    /**
     * This function used to getting option value by using option id(displaying rule edit mode)
     * @param unknown $value
     * @return unknown
     */
    public function getValues($value)
    {	$attr = Mage::getModel('eav/entity_attribute')->load($this->getAttribute(),'attribute_code');
    	$product = Mage::getModel('catalog/product');
    	$attribute=$attr->getFrontendLabel()?:$this->getAttribute();
        	
    	if($attribute=='entity_id')
    	{
    		$_product = $product->load($value);
    		return $_product->getName();
    	}
    	
    	else if($attribute=='category_id')
    	{    		
    		$_category = Mage::getModel('catalog/category')->load($value);    		    		
    		return $_category->getName();    		
    	}
    	
    	else if(is_numeric($value))
    	{    		
    		$attributeDetails = Mage::getSingleton("eav/config")
    		->getAttribute("catalog_product", $attribute);
    		$option_id=$attributeDetails->getSource()->getOptionId($value);
			$optionValue = $attributeDetails->getSource()->
    		getOptionText($option_id);
						
			if(!empty($optionValue))
			{
				if(is_numeric($optionValue))
					return $value;
				else					
					return $optionValue;
			}
			else
				return $value;
    	}    	
    	return $value;    	
    }
    	
    public function getConditions($condition)
    {
    	$condition_list=array("is"=>"Is",
						  "eq"=>"Is",
						  "lteq"=>"Less than equal to",
						  "gteq" => "Greater than equal to",
						  "gt"=>"Greater than",
						  "lt"=>"Lesser than",
						  "neq"=>"Is not"    	
    					);
    	return $condition_list[$condition];
    	
    }
    
}