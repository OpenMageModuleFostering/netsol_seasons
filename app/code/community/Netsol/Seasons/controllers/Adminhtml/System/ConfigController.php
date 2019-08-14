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
class Netsol_Seasons_Adminhtml_System_ConfigController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * @description: For download the sample csv
	 * for getting latitude
	 * from ip address
	 * */
    public function downloadcsvFileAction()
    {
		$filename  = 'IP2LOCATION-LITE-DB5-short.csv';
        $filepath = Mage::getBaseDir('base').'/var/netsol/seasons/csv/downloads/'.$filename;

		if ($filename) {
            try {
               $this->_prepareDownloadResponse($filename, array('type' => 'filename', 'value' => $filepath));

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($filepath . ' not found');
            $this->_redirect('adminhtml/cache');
            return;
        }
    }
}
