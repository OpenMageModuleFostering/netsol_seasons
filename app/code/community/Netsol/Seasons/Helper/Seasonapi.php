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
class Netsol_Seasons_Helper_Seasonapi extends Netsol_Seasons_Helper_Data
{

	 /**
	 * @description: Resource iterator technique allows 
	 * you to get each item one by one through the function callback
	 *  using the walk function in core/resource_iterator
	 * 
	 * @param 
	 * @return  
	 * */
	 protected function getLatAddress($ip)
	 {
		try {

			$ch = curl_init();
			//curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
			$url = "http://freegeoip.net/json/$ip";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$ip_data_in = curl_exec($ch); // string
			curl_close($ch);

			/*$ip_data = json_decode($ip_data_in,true);
			$ip_data = str_replace('&quot;', '"', $ip_data);

			return $ip_data['geoplugin_latitude']; */
			if ($ip_data_in) {
				$location = json_decode($ip_data_in);
				$latitude = $location->latitude;
				$longitude = $location->longitude;
			}

			return $latitude;
		}
		catch(Exception $e) {
			echo $e->getMessage(); die;
		}
		
		//echo $latitude; die;
		
	}
	

	 /**
	 * @description: Resource iterator technique allows 
	 * you to get each item one by one through the function callback
	 *  using the walk function in core/resource_iterator
	 * 
	 * @param 
	 * @return  
	 * */
	 public function getProductOfSeason($ip)
	 {
		try {
			$seasonProductIds = array();
			$latitudeOfUser = $this->getLatAddress($ip);
			$helper = Mage::helper('seasons/data');
			$noOfDisplayProduct = $helper::getMaxProductCount(); 
			$guestMaxProductCount = $helper:: getMaxProductCount();
			$websiteId = Mage::app()->getWebsite()->getId();
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
				if($season[0]['season'] != '') {
					$_productCollection = Mage::getResourceModel('catalog/product_collection')
										->addAttributeToSelect('entity_id')
										->addWebsiteFilter($websiteId)
										->addAttributeToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
										->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
										->addAttributeToFilter('seasons',
												array('finset' => $season[0]['season'])
										)
										->setOrder('entity_id', 'DESC');
					if(Mage::getSingleton('customer/session')->isLoggedIn())
					{
						$_productCollection->setPageSize($noOfDisplayProduct);
						
					}else{
						$_productCollection->setPageSize($guestMaxProductCount);
					}
					Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($_productCollection);
					foreach($_productCollection as $product) {
						$seasonProductIds[] = $product->getEntityId();
					}
				}
			
				
			}
			elseif($latitudeOfUser < -23.5 && $latitudeOfUser > -90) {
				
				$hemisphereOfUser = 'Southern'; 
				$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
				$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
				$season = $paSeasonModel->getData();
				if($season[0]['season'] != '') {
					$_productCollection = Mage::getResourceModel('catalog/product_collection')
										->addAttributeToSelect('entity_id')
										->addWebsiteFilter($websiteId)
										->addAttributeToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
										->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
										->addAttributeToFilter('seasons',
												array('finset'=> $season[0]['season'])
										)
										->setOrder('entity_id', 'DESC');
					if(Mage::getSingleton('customer/session')->isLoggedIn())
					{
						$_productCollection->setPageSize($noOfDisplayProduct);
					}else{
						$_productCollection->setPageSize($guestMaxProductCount);
					}
					Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($_productCollection);
					foreach($_productCollection as $product) {
						$seasonProductIds[] = $product->getEntityId();
					}
				}

			}elseif($latitudeOfUser < 0 && $latitudeOfUser > -23.5) {
				$hemisphereOfUser = 'Equator-Capricorn';
				$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
				$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
				$season = $paSeasonModel->getData();
				if($season[0]['season'] != '') {
					$_productCollection = Mage::getResourceModel('catalog/product_collection')
										->addAttributeToSelect('entity_id')
										->addWebsiteFilter($websiteId)
										->addAttributeToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
										->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
										->addAttributeToFilter('seasons',
												array('finset' => $season[0]['season'])
										)
										->setOrder('entity_id', 'DESC');
					if(Mage::getSingleton('customer/session')->isLoggedIn())
					{
						$_productCollection->setPageSize($noOfDisplayProduct);
					}else{
						$_productCollection->setPageSize($guestMaxProductCount);
					}
					Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($_productCollection);
					foreach($_productCollection as $product) {
						$seasonProductIds[] = $product->getEntityId();
					}
					
				}

			}elseif($latitudeOfUser < 23.5 && $latitudeOfUser > 0) {
				$hemisphereOfUser = 'Equator-Cancer';
				$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
				$paSeasonModel->getSelect()->where("(DATE_FORMAT(`start_date`,'%m-%d') <= '$currentDate') AND (((((DATE_FORMAT(`end_date`,'%m-%d') >= '$currentDate') OR (`end_date` IS NULL))) OR (`end_date` = 'left')))");
				$season = $paSeasonModel->getData();
				if($season[0]['season'] != '') {
					$_productCollection = Mage::getResourceModel('catalog/product_collection')
										->addAttributeToSelect('entity_id')
										->addWebsiteFilter($websiteId)
										->addAttributeToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
										->addAttributeToFilter('status', array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED))
										->addAttributeToFilter('seasons',
												array('finset' => $season[0]['season'])
										)
										->setOrder('entity_id', 'DESC');
					if(Mage::getSingleton('customer/session')->isLoggedIn())
					{
						$_productCollection->setPageSize($noOfDisplayProduct);
						
					}else{
						$_productCollection->setPageSize($guestMaxProductCount);
					}
					foreach($_productCollection as $product) {
						$seasonProductIds[] = $product->getEntityId();
					}
			  }
			} else {
				
			}
		
			return $seasonProductIds;
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
		
	
	 }
}
