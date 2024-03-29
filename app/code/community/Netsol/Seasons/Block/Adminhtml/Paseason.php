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

class Netsol_Seasons_Block_Adminhtml_Paseason extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

		$this->_controller = "adminhtml_paseason";
		$this->_blockGroup = "seasons";
		$this->_headerText = Mage::helper("seasons")->__("Season Manager");
		$this->_addButtonLabel = Mage::helper("seasons")->__("Add New Item");
		parent::__construct();
		$this->_removeButton('add');
	
	}
	
	 public function toHemisphereArray()
	 {

		$itemarray = array(
			 array(
				'value'=> '', 
				'label'=>'Please Select',
			 ),
			 array(

				'value'=>'Northern',
				'label'=>'Northern',
			
			 ),
			 array(
				'value'=>'Southern',
				'label'=>'Southern',
			 ),
			 array(
				'value'=>'Equator-Cancer',
				'label'=>'Equator-Cancer',
			
			 ),
			 array(
				'value'=>'Equator-Capricorn',
				'label'=>'Equator-Capricorn',
			
			 )
		);

		return $itemarray;
	}
	
	 public function toSeasonArray()
	 {

	
		$itemarray = array(
			 array(
				'value'=> 0, 
				'label'=>'Please Select',
			 ),
			 array(

				'value'=>'Winter',
				'label'=>'Winter',
			
			 ),
			 array(
				'value'=>'Summer',
				'label'=>'Summer',
			 ),
			 array(
				'value'=>'Autumn',
				'label'=>'Autumn',
			 ),
			 array(
				'value'=>'Spring',
				'label'=>'Spring',
			 ),
			 array(
				'value'=>'Wet',
				'label'=>'Wet (Winter)',
			 ),
			 array(
				'value'=>'Dry',
				'label'=>'Dry (Summer) ',
			 )
		);

		return $itemarray;
	}

}
