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
class Apptha_Productlabels_Adminhtml_AttributesController extends Mage_Adminhtml_Controller_action
{

	public function indexAction(){	
		$attribute_code = $this->getRequest()->getParam('code');
		
		$attribute_details = Mage::getSingleton("eav/config")->getAttribute("catalog_product",$attribute_code);		
		$input = '';		
		switch ($attribute_details->getData('frontend_input')){			
			case 'select':
			case 'multiselect':				
				$options = $attribute_details->getSource()->getAllOptions(true);
				$condition_box ='<select name="{{htmlName}}[value][option_{{id}}][openinghour]" id="mySelect-{{id}}">
						 <option value="eq">is</option>
					 <option value="neq">is not</option>						 </select>';
				$value_box = '<select name="{{htmlName}}[value][option_{{id}}][closinghour]" class="input-text required-entry" id="categorys-{{id}}">';
				
				foreach($options as $option){
					if(!empty($option["value"]))						
					$value_box .= '<option value="'.$option["value"].'"> '.$option["label"].' </option>';
				}				
				$value_box .= '</select>';				
				break;
			default:
				$condition_box ='<select name="{{htmlName}}[value][option_{{id}}][openinghour]" id="mySelect-{{id}}">
						 <option value="eq">is</option>
						 <option value="neq">is not</option>
						 <option value="lteq">Less than equal to</option>
					     <option value="gteq">Greater than equal to</option>
					     <option value="gt">Greater than</option>
						 <option value="lt">Lesser than</option>
						 </select>';
				$value_box ='<input type="text" id="categorys-{{id}}" value="" name="{{htmlName}}[value][option_{{id}}][closinghour]" class="input-text required-entry" />';
				break;
		}
		$jsonData=json_encode(array('inputText'=>$value_box,'selectBox'=>$condition_box));
		$this->getResponse()->setHeader('Content-type', 'application/json');
		$this->getResponse()->setBody($jsonData);
		return $this;		
	}

}
