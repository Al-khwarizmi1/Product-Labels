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
class Apptha_Productlabels_Model_Productlabels extends Mage_Core_Model_Abstract {
	public function _construct() {
		parent::_construct ();
		$this->_init ( 'productlabels/productlabels' );
	}
	
	
	public function getRules() {
		return $this->_getRulesModel ()->getCollection ()->addFieldToFilter ( 'label_id', array (
				'eq' => $this->getId () 
		) );
	}
	
	/**
	 * 
	 * @param unknown $productId
	 */
	public function fetchLabels($productId) {
		$labelsIds = $this->_getConditionModel ()->getLabels ( $productId );
		
		return $this->getCollection ()->addFieldToFilter ( 'id', array (
				'in' => $labelsIds 
		) );
	}
	/**
	 * This function return product id for label appled product labels 
	 * @param string $returnNow
	 * @param string $productId
	 * @param string $labelId
	 * @return Ambigous <multitype:unknown, Apptha_Productlabels_Model_Productlabels, multitype:unknown >
	 */
	
	public function getLabels($returnNow = false, $productId = null, $labelId = null) {
		$labelCollection = $this->getCollection ();
		
		if ($labelId) {
			$labelCollection->addFieldToFilter ( 'id', array (
					'eq' => $labelId 
			) );
		}
		
		return $this->_getProductAppliedByRule ( $labelCollection, $productId, $returnNow );
	}
	
	/**
	 * 
	 * This function used to filter label applied product id from product tables.
	 * @param unknown $labelCollection
	 * @param string $productId
	 * @param string $returnNow
	 * @return multitype:unknown |Apptha_Productlabels_Model_Productlabels
	 */		
	protected function _getProductAppliedByRule($labelCollection, $productId = null, $returnNow = false) 
	{
		foreach ( $labelCollection as $_label ) 
		{						
			$ruleCollection = $_label->getRules ();						
			$OrCondition = array ();
			$isOR = ( bool ) $_label->getCondition ();									
			$productCollection = $this->_getProductCollection ();			
			$category_exist=0;
			foreach ( $ruleCollection as $_rule ) 
			{												
				$condition = $_rule->getCondition (); 
				$attribute = $_rule->getAttribute ();
				$value = $_rule->getValue ();
				
				if ($attribute == 'category_id' && $category_exist==0) 
				{					
					$productCollection->joinField ( 'category_id', 'catalog/category_product', 'category_id', 'product_id=entity_id', null, 'left' );
					$category_exist++;																													
				}				
				if ($isOR) 
				{					
					array_push ( $OrCondition, array (
							'attribute' => $attribute,
							array (
									$condition => $value 
							) 
						) );	
				} else 
				{					
					$productCollection->addAttributeToFilter ( $attribute, array (
							$condition => $value 																					
					) )->getSelect()->group('entity_id');															
				}				
			}						
			
			if (! empty ( $OrCondition )) 
			{									
				$productCollection->addAttributeToFilter ( $OrCondition )->getSelect()->group('entity_id');								
			}	

			$duplicate = clone $productCollection;			
			$productIds = $productCollection->load ()->getAllIds ();			
			$collected_labels = array ();								
			if ($productId) {				
				if (in_array ( $productId, $productIds )) {
					$collected_labels [] = $_label;
				}
				$duplicate->addAttributeToFilter ( 'entity_id', array (
						'eq' => $productId 
				) );
				$productIds = $duplicate->load ()->getAllIds ();
			}			
			
			if (! $returnNow) {
				
				if (! empty ( $productIds )) {
					$this->_getConditionModel ()->addLabel ( $_label, $productIds );
				} else {

					if ($productId) {
						$this->_getConditionModel ()->getCollection ()->deleteOldConditions ( $_label->getId (), array (
								$productId 
						) );
					}
				}								
			}		
		}
		
		if ($returnNow) {
			return $collected_labels;
		}			
		
		return $this;
	}
	
	
	protected function _getCode($text = 'eq') {
		$codeSymbol = array (
				'eq' => ' = ',
				'nteq' => ' != ',
				'lteq' => ' <= ',
				'gteq' => ' >= ',
				'lt' => ' < ',
				'gt' => ' > ' 
		);
		
		return $codeSymbol [$text];
	}
	
	/**
	 * This function return product collection 
	 * @return Ambigous <object, boolean, Mage_Core_Model_Abstract, false, unknown>
	 */
	protected function _getProductCollection() {
		return Mage::getModel ( 'catalog/product' )->getCollection ();
	}
	
	/**
	 * This function return label applied proudct collection 
	 * @return Ambigous <Mage_Core_Model_Abstract, false, boolean, unknown>
	 */
	protected function _getConditionModel() {
		return Mage::getModel ( 'productlabels/conditions' );
	}
	/**
	 * This function return rules collection 
	 * @return Ambigous <Mage_Core_Model_Abstract, false, boolean, unknown>
	 */
	protected function _getRulesModel() {
		return Mage::getModel ( 'productlabels/rules' );
	}
}