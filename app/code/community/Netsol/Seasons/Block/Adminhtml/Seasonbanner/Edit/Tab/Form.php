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
class Netsol_Seasons_Block_Adminhtml_Seasonbanner_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("seasons_form", array("legend"=>Mage::helper("seasons")->__("Season Banner information")));

								
				$fieldset->addField('banner_image', 'image', array(
					'label' => Mage::helper('seasons')->__('Banner Image'),
					'name' => 'banner_image',
					'note' => '(*.jpg, *.png, *.gif,*.jpeg)',
				));				
				$fieldset->addField('hemisphere', 'select', array(
					'label'     => Mage::helper('seasons')->__('Choose Hemisphere'),
					'class'     => 'validate-select',
					'required'  => true,
					'values'   => Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid::getValueArray2(),
					'name' => 'hemisphere',
				));
				$fieldset->addField('season', 'select', array(
					'label'     => Mage::helper('seasons')->__('Choose Season'),
					'class'     => 'validate-select',
					'required'  => true,
					'values'   => Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid::getValueArray1(),
					'name' => 'season',
				));
				$fieldset->addField('status', 'select', array(
				  'label'     => Mage::helper('seasons')->__('Banner Image Status'),
				  'name'      => 'status',
				  'values'    => array(
					  array(
						  'value'     => 1,
						  'label'     => Mage::helper('seasons')->__('Enabled'),
					  ),

					  array(
						  'value'     => 2,
						  'label'     => Mage::helper('seasons')->__('Disabled'),
					  ),
				  ),
			  ));
			  $fieldset->addField('weblink', 'text', array(
				'label'     => Mage::helper('seasons')->__('Image Url'),
				'class'     => 'validate-url',
				'after_element_html' => "<small>Image URL</small>",
				'name'      => 'weblink',
			 ));
			 
			 $fieldset->addField('sort_order', 'text', array(
				'name'		=> 'sort_order',
				'label'		=> $this->__('Sort Order'),
				'title'		=> $this->__('Sort Order'),
				'class'		=> 'validate-digits',
				//'required'  => true,
			));
		
			if (Mage::getSingleton("adminhtml/session")->getSeasonbannerData())
			{
				$form->setValues(Mage::getSingleton("adminhtml/session")->getSeasonbannerData());
				Mage::getSingleton("adminhtml/session")->setSeasonbannerData(null);
			} 
			elseif(Mage::registry("seasonbanner_data")) {
				$form->setValues(Mage::registry("seasonbanner_data")->getData());
			}
			return parent::_prepareForm();
		}
}
