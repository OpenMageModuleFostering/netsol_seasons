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
class Netsol_Seasons_Helper_Seasonbanner extends Netsol_Seasons_Helper_Seasonapi
{
		
	 /**
	 * @description: Resource iterator technique allows 
	 * you to get each item one by one through the function callback
	 *  using the walk function in core/resource_iterator
	 * 
	 * @param 
	 * @return  
	 * */
	 public function getBannerOfSeason($ip)
	 {
		try { 
			
				$paSeasonBannerCollection = array();
				$seasonapiHelper = Mage::helper('seasons/seasonapi');
				$latitudeOfUser = $seasonapiHelper::getLatAddress($ip); 
				$currentDate = Mage::getModel('core/date')->date('m-d'); 
				$paSeasonModel = Mage::getModel('seasons/paseason')->getCollection();
				$paSeasonModel->addFieldToSelect('season');
				/**
				 * case 1: latitude of user is greater than zero and less
				 * than 90 degree then it falls in Nothern Hemisphere
				 * 
				 * case 2: latitude of user is less than zero and less
				 * than -90 degree then it falls in Southern Hemisphere
				 * 
				 * case 3: latitude of user is less than zero and less
				 * than -90 degree then it falls in Equator Hemisphere
				 * */
				if($latitudeOfUser > 23.5 && $latitudeOfUser < 90) {
					
					$hemisphereOfUser = 'Northern';		
					$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
					$season = $paSeasonModel->getData();
					/**Season Banner collection**/
					$paSeasonBannerCollection = Mage::getModel('seasons/seasonbanner')->getCollection();
					$paSeasonBannerCollection->addFieldToFilter('season',array('eq'=>$season[0]['season']));
					$paSeasonBannerCollection->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonBannerCollection->setOrder('sort_order', 'ASC');
			
				}elseif($latitudeOfUser < -23.5 && $latitudeOfUser > -90) {
			
					$hemisphereOfUser = 'Southern'; 
					$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
					$season = $paSeasonModel->getData();
					/**Season Banner collection**/
					$paSeasonBannerCollection = Mage::getModel('seasons/seasonbanner')->getCollection();
					$paSeasonBannerCollection->addFieldToFilter('season',array('eq'=>$season[0]['season']));
					$paSeasonBannerCollection->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonBannerCollection->setOrder('sort_order', 'ASC');
		
				}elseif($latitudeOfUser < 0 && $latitudeOfUser > -23.5) {
					$hemisphereOfUser = 'Equator-Capricorn';
					$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
					$season = $paSeasonModel->getData();
					/**Season Banner collection**/
					$paSeasonBannerCollection = Mage::getModel('seasons/seasonbanner')->getCollection();
					$paSeasonBannerCollection->addFieldToFilter('season',array('eq'=>$season[0]['season']));
					$paSeasonBannerCollection->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonBannerCollection->setOrder('sort_order', 'ASC');
					
				}elseif($latitudeOfUser < 23.5 && $latitudeOfUser > 0) {
					$hemisphereOfUser = 'Equator-Cancer'; 
					$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
					$season = $paSeasonModel->getData();
					/**Season Banner collection**/
					$paSeasonBannerCollection = Mage::getModel('seasons/seasonbanner')->getCollection();
					$paSeasonBannerCollection->addFieldToFilter('season',array('eq'=>$season[0]['season']));
					$paSeasonBannerCollection->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
					$paSeasonBannerCollection->setOrder('sort_order', 'ASC');

				}else {
					
				}
				return $paSeasonBannerCollection;
			} catch(Exception $e) {
				echo $e->getMessage(); die;
			}
		}
}
