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
class Netsol_Seasons_Block_Config_Backend_Downloadcsv  extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
	 * @description: button action to download
	 * sample csv to get latitude of user
	 * 
	 * @return: $button html
	 * */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {

		$button = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                'id'        => 'seasons_csv',
                'label'     => $this->helper('adminhtml')->__('Download Sample Csv'),
                'onclick'   => 'setLocation(\''.Mage::helper('adminhtml')->getUrl('seasons/adminhtml_system_config/downloadcsvfile') . '\')',
            ));

        return $button->toHtml();
    }
}
