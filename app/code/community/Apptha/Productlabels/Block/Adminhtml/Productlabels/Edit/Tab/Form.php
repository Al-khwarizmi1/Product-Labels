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
class Apptha_Productlabels_Block_Adminhtml_Productlabels_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	/**
	 * Prepare label for tab
	 *
	 * @return string
	 */
	
	public function getTabLabel()
	{
		return Mage::helper('productlabels')->__('Conditions');
	}
	
	/**	
	 * Prepare title for tab
	 *
	 * @return string
	 */
	public function getTabTitle()
	{
		return Mage::helper('productlabels')->__('Conditions');
	}
	
	/**
	 * Returns status flag about this tab can be showen or not
	 *
	 * @return true
	 */
	public function canShowTab()
	{
		return true;
	}
	
	/**
	 * Returns status flag about this tab hidden or not
	 *
	 * @return true
	 */
	public function isHidden()
	{
		return false;
	}
		
	/**
	 * This function used to prepare edit layout 
	 * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
	 */
	protected function _prepareForm()
  	{
      	$form = new Varien_Data_Form();
    	$this->setForm($form);
    	$fieldset = $form->addFieldset('productlabels_form', array('legend'=>Mage::helper('productlabels')->__('Rules information')));   
   	  	 
	      $fieldset->addField('rulename', 'text', array(
	          'label'     => Mage::helper('productlabels')->__('Rule Name'),
	      	  'after_element_html' => '<small>Provide name for the rule</small>',     		
	          'class'     => 'required-entry',
	          'required'  => true,
	          'name'      => 'rulename',
	      ));
	      $fieldset->addField('rulestatus', 'select', array(
	          'label'     => Mage::helper('productlabels')->__('Rule Status'),
	          'name'      => 'rulestatus',      	 		
	          'values'    => array(
	         				     array(
	                  					'value'     => 1,
	                  					'label'     => Mage::helper('productlabels')->__('Enabled'),
	             				 	   ),
	              					array(
	                  					 'value'     => 2,
	                  			    	 'label'     => Mage::helper('productlabels')->__('Disabled'),
	              				  		 ),
	             				  ),
	      ));
	      
	      $fieldset->addField('filename', 'image', array(
	      		'name' 		=> 'filename',
	      		'class'     => 'required-entry required-file',
	      		'label'     => Mage::helper('productlabels')->__('Label Image'),
	      		'title' 	=> Mage::helper('productlabels')->__('Label Image'),
	      		'after_element_html' => '<small><br>Provide image for the label</br></small>',
	      		'required'	=> true	      		
	      		
	      ));
	    	      	      
	      $fieldset->addField('fromdate', 'date', array(
	      		'name'               => 'fromdate',
	      		'label'              => Mage::helper('productlabels')->__('From Date'),
	      		'after_element_html' => '<small>Label active from date</small>',
	      		'tabindex'           => 1,
	      		'image'              => $this->getSkinUrl('images/grid-cal.gif'),
	      		'required'			 => true,
	      		'class'				 => 'required-entry',
	      		'format'             => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) ,	      		
	      ));
	       
	      $fieldset->addField('todate', 'date', array(
	      		'name'               => 'todate',
	      		'label'              => Mage::helper('productlabels')->__('To Date'),
	      		'after_element_html' => '<small>Label active to date</small>',
	      		'tabindex'           => 1,
	      		'image'              => $this->getSkinUrl('images/grid-cal.gif'),
	      		'required'	         => true,
	      		'class'		         => 'required-entry',
	      		'format'             => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) ,	      		
	      ));           
	      $fieldset = $form->addFieldset('labelposition_form', array('legend'=>Mage::helper('productlabels')->__('Label Position')));      
	      $fieldset->addField('labelposition', 'text', array(
	      		'label'     => Mage::helper('productlabels')->__('Label Position'), 
	      		'name'      => 'labelposition',
	      ));
	      $labelposition=$form->getElement('labelposition');
	      $labelposition->setRenderer(
	      		$this->getLayout()->createBlock('productlabels/adminhtml_productlabels_edit_renderer_position')
	      );             
	      $fieldset = $form->addFieldset('rkstorelocator_form', array('legend'=>Mage::helper('productlabels')->__('Add Rules')));
	      $fieldset->addField('condition', 'select', array(
	      		'label'     => Mage::helper('productlabels')->__('Condition'),
	      		'name'      => 'condition',
	      		'required'	=> true,
	      		'class'		=> 'required-entry',
	      		'values'    => array(
	      				array(
	      						'value'     => 0,
	      						'label'     => Mage::helper('productlabels')->__('If All Conditions Satisfied'),
	      				),
	      				array(
	      						'value'     => 1,
	      						'label'     => Mage::helper('productlabels')->__('If One Of The Condition Satisfied'),
	      				),
	      		),
	      ));
	      
	      $fieldset->addField('rules', 'text', array(
	      		'label'     => Mage::helper('productlabels')->__('Label Rule'),
	      		'name'      => 'rules',
	      		'required'	=> true,
	      		'class'		=> 'required-entry',
	      ));
	      
	      $rules_list = $form->getElement('rules');
	      	      
	      $rules_list->setRenderer(
	      		$this->getLayout()->createBlock('productlabels/adminhtml_productlabels_edit_renderer_rulelayout')
	      );     
	      if ( Mage::getSingleton('adminhtml/session')->getFormData() )
	      {
	          $form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
	          
	      } 
	      elseif ( Mage::registry('productlabels_data') ) 
	      {
	      	  $data = Mage::registry('productlabels_data')->getData();	      	
	          $form->setValues($data);
	      } 
	      Mage::getSingleton('adminhtml/session')->getFormData(null);
	      return parent::_prepareForm();
  }
}

