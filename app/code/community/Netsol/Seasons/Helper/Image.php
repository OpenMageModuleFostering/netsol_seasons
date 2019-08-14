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
class Netsol_Seasons_Helper_Image extends Varien_Data_Form_Element_Image
{
	 /**
	 * @description: url of seasons banner
	 * 
	 * @return  $url
	 * */
	protected function _getUrl(){
			$url = false;
			if ($this->getValue()) {
				$url = Mage::getBaseUrl('media').'netsol/seasons/paseason/'.$this->getValue();
			}
			return $url;
		}
}
