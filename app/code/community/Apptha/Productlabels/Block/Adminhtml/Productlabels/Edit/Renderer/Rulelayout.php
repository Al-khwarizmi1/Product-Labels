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

class Apptha_Productlabels_Block_Adminhtml_Productlabels_Edit_Renderer_Rulelayout extends Mage_Adminhtml_Block_Widget
implements Varien_Data_Form_Element_Renderer_Interface 
{

    /**
     * Initialize block
     */
    public function __construct()
    {
        $this->setTemplate('productlabels/rulelayout.phtml');
    }

    /**
     * Render HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }
    /**
     * get the rules collection
     * @return array
     */
    public function getRulesCollection() {
    	
    	if($productLableData = Mage::registry('productlabels_data')){
    		return $productLableData->getRules(); 
    	}
    	
    }
    
    /**
     * get the product collection
     * 
     * @return array
     */
    public function getProductColleciton(){
    	$collection = Mage::getModel('catalog/product')->getCollection()
    				->addAttributeToSelect(array('name','sku'))
    				->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
    	foreach ($collection as $product)
    	{
    		$_getSku[] = $product->getSku();
    		$_productName[] = $this->htmlEscape($product->getName());
    		$_productid[] = $product->getId();
    			
    	}
    	return array('id'=>$_productid,'sku'=>$_getSku,'name'=>$_productName);
    }
    /**
     * get the active category
     * 
     * @return array
     */
    public function getCategory()
    {
    	$_categoryName = array();
    	$_categoryId =array();
    	$category=Mage::getModel('catalog/category')->getCollection();
    	foreach ($category as $categorylist)
    	{
    	$_category = Mage::getModel('catalog/category')->load($categorylist->getId()); 
        $_subcategories = $_category->getChildrenCategories();
        if (count($_subcategories) > 0)                  
        foreach($_subcategories as $_subcategory)
        {
                  	if ($_subcategory->getIsActive()) 
                  	{
                   		$_categoryName[] = $_subcategory->getName();
                  		$_categoryId[] =$_subcategory->getId();
                    }
         }
    	}                           
         return array('name'=>$_categoryName,'id'=>$_categoryId);
    
    }
//     public function getCategory(){
//     	$_categoryName = array();
//     	$categories = Mage::getModel('catalog/category')->getCollection()
//     				->addAttributeToSelect('name')
//     				->addAttributeToSelect('is_active');
//     	foreach ($categories as $category) {
    		
//     	}    	
    	
//     	return array('name'=>$_categoryName,'id'=>$_categoryId);

//     }
	
    /**
     * get all the atribute collection
     * 
     * @return array
     */
    public function getAttributeCollection(){
    	
    	$exclude_attributes = $this->getExcludedAttributes();
    	
    	$setId =  Mage::getModel('catalog/product')->getDefaultAttributeSetId();
    	$attributes = Mage::getModel('eav/entity_attribute_group')->getResourceCollection()
    				->setAttributeSetFilter($setId)
    				->setSortOrder()
    				->load();
    	$attribute = array();
    	foreach ($attributes as $_attribute) {
    		$nodeChildren = Mage::getResourceModel('catalog/product_attribute_collection')
     		->addVisibleFilter()
     		->load();
    		
    		if ($nodeChildren->getSize() > 0) {
    			foreach ($nodeChildren->getItems() as $child) {
    				if(!in_array($child->getAttributeCode(), $exclude_attributes)){ 
    					$attribute[$child->getAttributeCode()]=$child->getFrontendLabel() ;
    				}
    			}
    		}
    	}
    	
    	return array_merge($this->getAdditionalAttributes(),$attribute);
    }
    
    /**
     * get the additonal attributes  
     *  
     * @return array
     */
    protected function getAdditionalAttributes(){
    	return  array(
    			""=>"--select--",
    			"category_id"=>"Category",
    			"entity_id"=>"Name"
    			    			
    	);
    }
    /**
     * get the excluded attribute
     * 
     * @return array
     */
    protected function getExcludedAttributes(){
    	
    	return array(	
    			"visibility",
    			"status",
    			"url_key", 		
    			"name",
    			"sku",
    			"msrp",
    			"description",
    			"short_description",
    			"msrp_display_actual_price_type",
    			"meta_title",
    			"meta_keyword",
    			"meta_description",
    			"image",
    			"small_image",    			
    			"media_gallery",
    			"gallery",
    			"is_recurring",
    			"recurring_profile",
    			"msrp_enabled",
    			"gift_message_available",
    			"custom_design_from",
    			"custom_design_to",
    			"custom_design",
    			"custom_design_update",
    			"custom_layout_update",
    			"page_layout",
    			"thumbnail",
    			"options_container",
    			"tax_class_id",
    			"country_of_manufacture",
    			"news_from_date",
    			"news_to_date",
    			"special_to_date",
    			"special_from_date",
    			"open_amount_min",
    			"open_amount_max",
    			"group_price",
    			"tier_price",
    			"price_view",
    			"gift_wrapping_available",
    			"gift_wrapping_price",
    			"cost",
    	);
    	
    }
   

}