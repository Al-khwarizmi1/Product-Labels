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
class Apptha_Productlabels_IndexController extends Mage_Core_Controller_Front_Action
{
	/*
	 * Action for loading and rendering layout
	 */
	public function indexAction()
    {    				
		
    	
    	$labelCollection =  $this->_getLabelsModel()->getCollection();
    	
    	$this->_getProductAppliedByRule($labelCollection);
    	
    	
    }
    
    protected function _getProductAppliedByRule($labelCollection){
    	
    	$productCollection = Mage::getModel('catalog/product')->getCollection();
    	
    	foreach ($labelCollection as $_label){
    		
    		$ruleCollection = $_label->getRules();  
    		$cloneproductCollection = clone $productCollection;	
    		
    		$OrCondition = array();
    		$isOR = (bool)$_label->getCondition();
    		foreach ($ruleCollection as $_rule){
    			
    			$condition = $_rule->getCondition();//$this->_getCode($_rule->getCondition());
    			$attribute = $_rule->getAttribute();
    			$value = $_rule->getValue();
    			
    			if($isOR){
    				array_push($OrCondition, array('attribute'=>$attribute,array($condition=>$value)));		 
    			}else{
    				$cloneproductCollection->addAttributeToFilter($attribute,array($condition=>$value));
    			}
    			
    		}
    		if(!empty($OrCondition)){
    			$cloneproductCollection->addAttributeToFilter($OrCondition);
    		}
    		
    		$cloneproductCollection->load()->getAllIds();
    		
    		 
    		
    	}
    		
    	
    }
    
    
    protected function _getCode($text='eq'){
    	
    	$codeSymbol = array(
    		'eq'=>' = ',
    		'nteq'=>' != ',
    		'lteq'=>' <= ',
    		'gteq'=>' >= ',
    		'lt'=>' < ',
    		'gt'=>' > ',
    	);
    	
    	return $codeSymbol[$text];
    	
    }
    
    protected function _getLabelsModel(){
    	
    	return Mage::getModel('productlabels/productlabels');
    	
    }
    protected function _getRulesModel(){
    	
    	return Mage::getModel('productlabels/rules');
    }
    
    
}			
    
