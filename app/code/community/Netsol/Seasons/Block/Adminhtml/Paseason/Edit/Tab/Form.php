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
class Netsol_Seasons_Block_Adminhtml_Paseason_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("seasons_form", array("legend"=>Mage::helper("seasons")->__("Season Information")));
				 $fieldset->addType('image', 'Netsol_Seasons_Helper_Image');
					 $fieldset->addField('hemisphere', 'select', array(
						'label'     => Mage::helper('seasons')->__('Hemisphere'),
						'values'   => Netsol_Seasons_Block_Adminhtml_Paseason::toHemisphereArray(),
						'name' => 'hemisphere',
						'required' => true,
					));				
					 $fieldset->addField('season', 'select', array(
						'label'    => Mage::helper('seasons')->__('Season'),
						'values'   => Netsol_Seasons_Block_Adminhtml_Paseason::toSeasonArray(),
						'name' => 'season',
						'required' => true,
					));
					
					$dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
					$fieldset->addField('start_date', 'date', array(
						'label'    => Mage::helper('seasons')->__('Start Date'),
						'title'    => Mage::helper('seasons')->__('Start Date'),
						'name'     => 'start_date',
						'image'    => $this->getSkinUrl('images/grid-cal.gif'),
						'format'   => 'yyyy-MM-dd',
						'required' => true,
					));
					$fieldset->addField('end_date', 'date', array(
						'label'    => Mage::helper('seasons')->__('End Date'),
						'title'    => Mage::helper('seasons')->__('End Date'),
						'name'     => 'end_date',
						'image'    => $this->getSkinUrl('images/grid-cal.gif'),
						'format'   => 'yyyy-MM-dd',
						'required' => true,
					));
			
				if (Mage::getSingleton("adminhtml/session")->getPaseasonData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getPaseasonData());
					Mage::getSingleton("adminhtml/session")->setPaseasonData(null);
				} 
				elseif(Mage::registry("paseason_data")) {
				    $form->setValues(Mage::registry("paseason_data")->getData());
				}
				return parent::_prepareForm();
		}
}
