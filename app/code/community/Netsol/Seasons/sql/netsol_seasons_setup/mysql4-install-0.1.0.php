<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Netsol
 * @package     Netsol_Seasons
 * @copyright   Copyright (c) 2016 Netsolutions India (http://www.netsolutions.in)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$installer = $this;
$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute('catalog_product', 'seasons');

  $installer->addAttribute('catalog_product', 'seasons', array(
	  'attribute_set_name' => 'Default',
	  'group' => 'Personalytics', 
	  'type'              => 'text',
	  'backend'           => 'eav/entity_attribute_backend_array',
	  'frontend'          => '',
	  'label'             => 'Choose Season(s)',
	  'input'             => 'multiselect',
	  'class'             => '',
	  'source'			 => 'seasons/config_source_seasons',
	  'visible'           => true,
	  'required'          => false,
	  'user_defined'      => false,
	  'searchable'        => false,
	  'filterable'        => false,
	  'comparable'        => false,
	  'visible_on_front'  => false,
	  'unique'            => false,
	  'note' => 'Please select season for this product to recommend product.',
	  'is_configurable'=>'1',
	  'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
  )); 
  

  $installer->run("
		DROP TABLE IF EXISTS {$this->getTable('netsol_pa_season')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('netsol_pa_season')} (
		`seasonid`int(11) NOT NULL auto_increment,
		`hemisphere` varchar(50) NOT NULL,
		`season` varchar(50) NOT NULL,
		`start_date` date NULL,
		`end_date` date NULL,
		PRIMARY KEY  (`seasonid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	"); 
	
 $year = date('Y');
 $installer->run("
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Northern','Summer','$year-06-01','$year-08-31');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Northern','Winter','$year-12-01','$year-02-28');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Northern','Autumn','$year-09-01','$year-11-30');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Northern','Spring','$year-03-01','$year-05-31');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Southern','Summer','$year-12-01','$year-02-28');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Southern','Winter','$year-06-01','$year-08-31');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Southern','Autumn','$year-03-01','$year-05-31');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Southern','Spring','$year-09-01','$year-11-30');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Equator-Cancer','Wet','$year-10-01','$year-05-31');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Equator-Cancer','Dry','$year-06-01','$year-09-30');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Equator-Capricorn','Dry','$year-10-01','$year-05-31');
	insert into netsol_pa_season (`hemisphere`,`season`,`start_date`,`end_date`) values('Equator-Capricorn','Wet','$year-06-01','$year-09-30');
"); 

 $installer->run("
		DROP TABLE IF EXISTS {$this->getTable('netsol_pa_season_banner')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('netsol_pa_season_banner')} (
		`seasonbannerid`int(11) NOT NULL auto_increment,
		`banner_image` varchar(255) NULL,
		`hemisphere` varchar(50) NULL,
		`season` varchar(50) NULL,
		`status` smallint(6) NOT NULL default '0',
		`weblink` varchar(255) NULL,
		`created_time` datetime NULL,
		`update_time` datetime NULL,
		`sort_order` smallint(6) NOT NULL default '0',
		PRIMARY KEY  (`seasonbannerid`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
	"); 
 $installer->endSetup();
