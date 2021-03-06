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
class Apptha_Productlabels_Block_Adminhtml_Productlabels_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'productlabels';
        $this->_controller = 'adminhtml_productlabels';
        
        $this->_updateButton('save', 'label', Mage::helper('productlabels')->__('Save Label'));
        $this->_updateButton('applyrules', 'label', Mage::helper('productlabels')->__('Applyrules'));
        $this->_updateButton('delete', 'label', Mage::helper('productlabels')->__('Delete Label'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productlabels_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productlabels_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productlabels_content');
                }
            }
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /*
     * Function to get header text
     */
    public function getHeaderText()
    {
        if( Mage::registry('productlabels_data') && Mage::registry('productlabels_data')->getId() ) {
            return Mage::helper('productlabels')->__("Edit Label", $this->htmlEscape(Mage::registry('productlabels_data')->getTitle()));
        } else {
            return Mage::helper('productlabels')->__('Add Label');
        }
    }
}