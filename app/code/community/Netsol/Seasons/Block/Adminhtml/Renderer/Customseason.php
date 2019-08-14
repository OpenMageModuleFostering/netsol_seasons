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
class Netsol_Seasons_Block_Adminhtml_Renderer_Customseason extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{	
	/**
	 * Custom Filter For Grid season
	 * @var  Netsol_Seasons_Helper_Data 
	 * */
	public function render(Varien_Object $row)
	{
		$season =  $row->getData($this->getColumn()->getIndex());
		if($season == 'Dry') {
			$season = 'Dry (Summer)';
		}
		if($season == 'Wet') {
			$season = 'Wet (Winter)';
		}
	
		return $season;
	}
}
