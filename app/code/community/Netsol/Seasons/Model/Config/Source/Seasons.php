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
class Netsol_Seasons_Model_Config_Source_Seasons extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{

	/**
	 * * @var $options
	 * 
	 * @return array collections $options
	 * */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(

                array(
                    'label' => Mage::helper('seasons')->__('Winter'),
                    'value' =>  'Winter'
                ),
                array(
                    'label' => Mage::helper('seasons')->__('Summer'),
                    'value' =>  'Summer'
                ),
                array(
                    'label' => Mage::helper('seasons')->__('Autumn'),
                    'value' =>  'Autumn'
                ),
                array(
                    'label' => Mage::helper('seasons')->__('Spring'),
                    'value' =>  'Spring'
                ),
                array(
                    'label' => Mage::helper('seasons')->__('Wet (Winter)'),
                    'value' =>  'Wet'
                ),
                array(
                    'label' => Mage::helper('seasons')->__('Dry (Summer)'),
                    'value' =>  'Dry'
                ),
            );
        }
        return $this->_options;
    }
 
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
 

}
