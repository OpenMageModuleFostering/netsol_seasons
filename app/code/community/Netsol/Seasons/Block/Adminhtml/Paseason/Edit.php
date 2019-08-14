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
class Netsol_Seasons_Block_Adminhtml_Paseason_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "seasonid";
				$this->_blockGroup = "seasons";
				$this->_controller = "adminhtml_paseason";
				$this->_removeButton('delete');
				$this->_removeButton('reset');
				$this->_updateButton("save", "label", Mage::helper("seasons")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("seasons")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("seasons")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);

				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						"; 
		}

		public function getHeaderText()
		{
				if( Mage::registry("paseason_data") && Mage::registry("paseason_data")->getId() ){
					
					return Mage::helper("seasons")->__("Update Season Information");
				} 
				else{

				     return Mage::helper("seasons")->__("Add Item");

				}
		}
}
