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
class Apptha_Productlabels_Block_Adminhtml_Productlabels_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	
	public function __construct() {

		
		
		parent::__construct ();		
		$this->setId ( 'tutsGrid' );
		$this->setDefaultSort ( 'label_id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
		
			
		
	}
	
	/*	
	 * Function for preparing collection for product labels model
	 */
	protected function _prepareCollection() {
		
		$collection = Mage::getModel('productlabels/productlabels')->getCollection();
		$this->setCollection($collection);
		
		return parent::_prepareCollection();
	}
	
	/*
	 * 
	 *This function used to prepare grid layout   
	 */	
	protected function _prepareColumns() {
		
		$this->addColumn ( 'id', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'ID' ),
				'align' => 'right',
				'width' => '100px',
				'index' => 'id' 
		));
		
		$this->addColumn ( 'rulename', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'Rule Name' ),
				'align' => 'left',
				'index' => 'rulename',
		));
		
		
		
		$this->addColumn ( 'filename', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'Label Image' ),
				'align' => 'left',
				'width' => '80px',
				'index' => 'filename' ,
				'filter'=> false,
				'frame_callback' => array($this, 'displayImage')
		));
			
		$this->addColumn ( 'labelposition', array (
				'header' => Mage::helper ('productlabels')->__ ('Label Position'),
				'align' => 'left',
				'width' => '80px',
				'index' => 'labelposition',
				'filter' => false,		
		));
		
		$this->addColumn ( 'fromdate', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'From Date' ),
				'align' => 'left',
				'width' => '80px',
				'index' => 'fromdate' 
		));
		
		$this->addColumn ( 'todate', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'To Date' ),
				'align' => 'left',
				'width' => '80px',
				'index' => 'todate' 
		));
		
		$this->addColumn ( 'rulestatus', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'Rule Status' ),
				'align' => 'left',
				'width' => '80px',
				'index' => 'rulestatus',
				'type'  => 'options',
				'options' => Mage::getSingleton('productlabels/status')->getOptionArray(),
		));
		
		$this->addColumn ( 'action', array (
				'header' => Mage::helper ( 'productlabels' )->__ ( 'Action' ),
				'width' => '100',
				'type' => 'action',
				'getter' => 'getId',
				'actions' => array (
						array (
								'caption' => Mage::helper ( 'productlabels' )->__ ( 'Edit' ),
								'url' => array (
										'base' => '*/*/edit' 
								),
								'field' => 'id' 
						) 
				),
				'filter' => false,
				'sortable' => false,
				'index' => 'stores',
				'is_system' => true 
		));		
		return parent::_prepareColumns ();
	}
	
	/**
	 * Function for mass action
	 */
	protected function _prepareMassaction() {

		$this->setMassactionIdField ( 'id' );
		$this->getMassactionBlock ()->setFormFieldName ( 'productlabels' );
		
		$this->getMassactionBlock ()->addItem ( 'delete', array (
				'label' => Mage::helper ( 'productlabels' )->__ ( 'Delete' ),
				'url' => $this->getUrl ( '*/*/massDelete' ),
				'confirm' => Mage::helper ( 'productlabels' )->__ ( 'Are you sure?' ) 
		) );
		
		$statuses = Mage::getSingleton ( 'productlabels/status' )->getOptionArray ();
		
		array_unshift ( $statuses, array (
				'label' => '',
				'value' => '' 
		) );
		$this->getMassactionBlock ()->addItem ( 'status', array (
				'label' => Mage::helper ( 'productlabels' )->__ ( 'Change rule status' ),
				'url' => $this->getUrl ( '*/*/massStatus', array (
						'_current' => true 
				) ),
				'additional' => array (
						'visibility' => array (
								'name' => 'status',
								'type' => 'select',
								'class' => 'required-entry',
								'label' => Mage::helper ( 'productlabels' )->__ ( 'Rule Status' ),
								'values' => $statuses 
						) 
				) 
		) );
		
		return $this;
	}
	
	/**
	 * Function for getting row url
	 * @param row
	 */
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array (
				'id' => $row->getId () 
		) );
	}
	
	/**
	 * Function for displaying the image in the back end admin grid
	 * @param $value
	 * @return image
	 */
	public function displayImage($value) {
		
		if($value)
			return "<img src='".Mage::getBaseUrl('media').$value."'/> ";
		else 
			return "<img src='".Mage::getBaseUrl('media').'apptha/productlabels/noimage.jpg'."'/> ";
	}
	
	/**
	 *Function for displaying store name
	 *@param $value
	 *@return store name
	 */
// 	public function displayStoreName($value){
		
// 		$store_ids = json_decode(html_entity_decode($value),true);

// 		$name = '<ul>';
// 		foreach ($store_ids as $_id){
// 			$name .= '<li>'.Mage::getSingleton('adminhtml/system_store')->getStoreName($_id).'</li>';
// 		}
// 		$name .= '</ul>';
// 		return $name;
// 	}
	
}