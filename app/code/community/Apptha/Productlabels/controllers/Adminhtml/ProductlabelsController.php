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
class Apptha_Productlabels_Adminhtml_ProductlabelsController extends Mage_Adminhtml_Controller_action
{
/*
 * Function for adding layout and label
 */
	protected function _initAction() 
	{
	
		$this->loadLayout()	
		->_setActiveMenu('productlabels/subitems')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		return $this;
	}   
	
	/**
	 * Function for rendering layout for admin grid
	 */
	public function indexAction()
	{		
		$this->_initAction()
		->renderLayout();
	}
	
	/**
	 * This function is used to edit the saved information and rules
	 * */ 
	public function editAction()
	 {
		$id = $this->getRequest()->getParam('id');
		if($id){
			$model  = Mage::getModel('productlabels/productlabels')->load($id);
			if ($model->hasData()) {				
				Mage::register('productlabels_data', $model);
			} else {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabels')->__('Item does not exist'));
				$this->_redirect('*/*/');
				return $this;
			}
		}													
		$this->loadLayout();
		$this->_setActiveMenu('productlabels/items');
		
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		$this->_addContent($this->getLayout()->createBlock('productlabels/adminhtml_productlabels_edit'))
			->_addLeft($this->getLayout()->createBlock('productlabels/adminhtml_productlabels_edit_tabs'));

		$this->renderLayout();
	}
   
	public function newAction() 
	{
		$this->_forward('edit');
	}
	 
	/**
	 * This function used to save the product label information and rules information
	 */	
	public function saveAction() {
		
		if ($data = $this->getRequest()->getPost()) {
			$label_id = $this->getRequest()->getParam('id');
			
				
			if(isset($label_id)){
				$this->removeRulesToLable($label_id);
			}
			
			$data['storeview']=json_encode($data['storeview']);			
			
			$model = Mage::getModel('productlabels/productlabels');
							
			$model->setData($data)
					->setId($label_id);			
	
			try {
				$this->_handleImageUpload($model,'filename');
				
				if ($model->getCreatedTime() || $model->getUpdateTime()) {
					$model->setCreatedTime(now())
							->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}
				$label_id = $model->save()->getId();
				Mage::getModel('productlabels/conditions')->getCollection()->deleteOldLabels($label_id);
				
				$rules = $data['rules'];
				
				$this->addRulesToLable($rules['value'],$label_id,$rules['delete']);
				$this->checkForProductRuleApplied($label_id);
							
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('productlabels')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);	
				
				if ($this->getRequest()->getParam('back')) 
				{
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				
				$this->_redirect('*/*/');	
				return;
			} 
			catch (Exception $e) 
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('productlabels')->__('Unable to find item to save'));
		$this->_redirect('*/*/');		
	}
	
	/**
	 * Upload an image and assign it to the model
	 *
	 * @param $model
	 * @param string $field = 'image'
	 */
	protected function _handleImageUpload($model, $field = 'image')
	{
		$data = $model->getData($field);
	
		if (isset($data['value'])) {
			$model->setData($field, $data['value']);
		}
	
		if (isset($data['delete']) && $data['delete'] == '1') {
			$model->setData($field, '');
		}
	
		if ($filename = Mage::helper('productlabels')->uploadImage($field)) {
			$model->setData($field, $filename);
		}else{	
			//throw new Exception($this->__('Image is required'));
		}
		
		
	}
	
	/**
	 * Function for removing rules
	 *  
	 * @param $label_id
	 */
	protected function removeRulesToLable($label_id){
		
		Mage::getResourceModel('productlabels/rules_collection')->deleteOldRules($label_id);
	
	}
	
	/**
	 * Function for adding rules to label
	 * 
	 * @param $rules,$label_id,$delete
	 */
	protected function addRulesToLable($rules,$label_id,$delete){
		
		$rulesModel = Mage::getModel('productlabels/rules');	
		$delete_rules = array();
		foreach ($delete as $_delete){
			if(!empty($_delete)){
				$delete_rules[] = $_delete;
			} 
		}
		foreach ($rules as $_rules){
			if(in_array($_rules['id'], $delete_rules) && isset($_rules['id'])){
				continue;
			}
			$value = array(
					'label_id'=>$label_id,
					'attribute'=>$_rules['dayindicator'],
					'condition'=>$_rules['openinghour'],
					'value'=>$_rules['closinghour']
			);
			$rulesModel->setData($value);
			$rulesModel->save();
			$rulesModel->unsetData();
		}
	}
	
	protected function checkForProductRuleApplied($label_id){
		
		$model = Mage::getModel('productlabels/productlabels');
			
		$model->getLabels(false,null,$label_id);
	}
	
	/**
	 * This function used to delete the product label information and rules information
	 */	
	public function deleteAction() 
	{
		if( $this->getRequest()->getParam('id') > 0 ) {
			try 
			{				
				$label_id = $this->getRequest()->getParam('id');	
				$model = Mage::getModel('productlabels/productlabels')
				 		->setId($label_id)
						->delete();

				$this->removeRulesToLable($label_id);
				 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
				
			} catch (Exception $e) {
				
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}	
	
	/**
	 * 
	 * This function used to delete multiple rows from label and rules table
	 *  
	 */
	public function massDeleteAction() {
		
	        $lableIds = $this->getRequest()->getParam('productlabels');	        
	        if(!is_array($lableIds)) 
	        {	        	
	        	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
	        
	        } else {
	          	try
	            {
	                foreach ($lableIds as $lableId)
	                {
	                    $lableModule = Mage::getModel('productlabels/productlabels')->load($lableId);	                    					
	                    $this->removeRulesToLable($lableId);	                    
	                  	$lableModule->delete();
	                }	                
	                $this->_redirect('*/*/');	                
	                Mage::getSingleton('adminhtml/session')
	                	->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($lableIds)));				                                    	            
	            } catch (Exception $e) {
	            	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		        }
	        }
	      
	 }
	 
	 /**
	  * This function is for changing multiple status  
	  * 
	  */
	 public function massStatusAction()
	 {
	        $lableIds = $this->getRequest()->getParam('productlabels');
	        
	      	       
	        if(!is_array($lableIds)) {

	        	Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
	        
	        } else {
	            try 
	            {
                	foreach ($lableIds as $lableId) {
                    	$lableModule = Mage::getModel('productlabels/productlabels')
                    	->load($lableId)
                    	->setRulestatus($this->getRequest()->getParam('status'))
                    	->setIsMassupdate(true)
                    	->save();                                                      	
                	
                    	$conditionApplied = Mage::getModel('productlabels/conditions')->getCollection();
                    	
                    	$conditionApplied->addFieldToFilter ( 'label_id', array (
                    			'eq' => $lableId
                    	));
                    	$ids=$conditionApplied->getData();
						for($i=0; $i<count($ids); $i++)
						{
                    		$conditionModule= Mage::getModel('productlabels/conditions')
                    		->load($ids[$i]['id'])                    		
                    		->setRulestatus($this->getRequest()->getParam('status'))
                    		->setIsMassupdate(true)
                    		->save();
                    	}                    	
                	}
                	
                	$this->_getSession()->addSuccess(
                    	$this->__('Total of %d record(s) were successfully updated', count($lableIds))
                	);
	            } catch (Exception $e) {
	                
	            	$this->_getSession()->addError($e->getMessage());
	            }
	      }
	      
	      $this->_redirect('*/*/index');
	}
	
	/**
	 * This function used to Generate the report in CSv format
	 */
	public function exportCsvAction()
	{
	    $fileName   = 'productlabels.csv';
	    $content    = $this->getLayout()->createBlock('productlabels/adminhtml_productlabels_grid')
	    				->getCsv();
	    $this->_sendUploadResponse($fileName, $content);
	}
	
	/**
	* This function used to Generate the report in Xml format
	*/	    
	public function exportXmlAction()
	{
	     $fileName   = 'productlabels.xml';
	     $content    = $this->getLayout()->createBlock('productlabels/adminhtml_productlabels_grid')
	     				->getXml();
	     $this->_sendUploadResponse($fileName, $content);
	}
	
	protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
	{
	        $response = $this->getResponse();
	        $response->setHeader('HTTP/1.1 200 OK','');
	        $response->setHeader('Pragma', 'public', true);
	        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
	        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
	        $response->setHeader('Last-Modified', date('r'));
	        $response->setHeader('Accept-Ranges', 'bytes');
	        $response->setHeader('Content-Length', strlen($content));
	        $response->setHeader('Content-type', $contentType);
	        $response->setBody($content);
	        $response->sendResponse();
	        die;
	}

}