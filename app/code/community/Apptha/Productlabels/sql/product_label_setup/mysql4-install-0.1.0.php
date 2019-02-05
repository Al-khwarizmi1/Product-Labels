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
$installer = $this;


$installer->startSetup();


$installer->run("DROP TABLE IF EXISTS {$this->getTable('apptha_product_labels')}");

$table = $installer->getConnection()
	
	->newTable($installer->getTable('apptha_product_labels'))

	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'primary'   => true,
			'auto_increment' => true,
			'nullable'  => false,
	), 'Label Id')
	
	->addColumn('rulename', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable'  => false,
	), 'Rule Name')
	
	->addColumn('rulestatus', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false,
	), 'Ruelstatus')

	->addColumn('filename', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false,
	), 'filename')

	->addColumn('storeview', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false,
	), 'storeview')
	
	->addColumn('labelposition', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
		'nullable'  => false,
	), 'labelposition')
	

	->addColumn('condition', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
			'nullable'  => false,
	), 'condition')
	
	->addColumn('fromdate', Varien_Db_Ddl_Table::TYPE_DATE, 255, array(
		'nullable'  => false,
	), 'fromdate')

	->addColumn('todate', Varien_Db_Ddl_Table::TYPE_DATE, 255, array(
		'nullable'  => false,
	), 'todate');
$installer->getConnection()->createTable($table);

$installer->run("DROP TABLE IF EXISTS {$this->getTable('apptha_product_labelapplied')}");

$table = $installer->getConnection()
	
	->newTable($installer->getTable('apptha_product_labelapplied'))
	
	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'primary'   => true,
			'auto_increment' => true,
			'nullable'  => false,
	), 'Applyid')
	
	
	->addColumn('label_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'nullable'  => false,
	), 'label_id')
	
	->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'nullable'  => false,
	), 'product_id')
	
	->addColumn('fromdate', Varien_Db_Ddl_Table::TYPE_DATE, 255, array(
			'nullable'  => false,
	), 'fromdate')
	
	->addColumn('todate', Varien_Db_Ddl_Table::TYPE_DATE, 255, array(
			'nullable'  => false,
	), 'todate')
	
	->addColumn('rulestatus', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
			'nullable'  => false,
	), 'Ruelstatus');
	

$installer->getConnection()->createTable($table);

$installer->run("DROP TABLE IF EXISTS {$this->getTable('apptha_product_rules')}");


$table = $installer->getConnection()

	->newTable($installer->getTable('apptha_product_rules'))

	->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
			'primary'   => true,
			'auto_increment' => true,
			'nullable'  => false,
	), 'Ruleid')
	
	->addColumn('label_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(				
		'nullable'  => false,
	), 'label_id')

	->addColumn('attribute', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable'  => false,
	), 'attribute')
	
	->addColumn('condition', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable'  => false,
	), 'condition')
	
	->addColumn('value', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
		'nullable'  => false,
	), 'value');

$installer->getConnection()->createTable($table);
$installer->endSetup();



