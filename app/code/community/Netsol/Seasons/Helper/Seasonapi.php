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
	 * @description: Get Latitude of user from its ip address 
	 * using IP2LOCATION-LITE-DB5.csv or file upload from admin
	 * 
	 * @param $file,$chunk_size,$callback
	 * @return $latitude
	 * */
	protected function file_get_contents_chunked($file,$chunk_size,$callback,$ipnumber)
	{
		try
		{ 
			$handle = fopen($file, "r");
			$i = 0;
			while (!feof($handle))
			{
				
				call_user_func_array($callback,array($data = fgetcsv($handle, ","),&$handle,$i));

				if($ipnumber <= $data[1] && $ipnumber >= $data[0]) {

						$latitude = $data[2]; 
				}
				$i++;
			}
			
			fclose($handle);

		}
		catch(Exception $e)
		{ 
			 trigger_error("file_get_contents_chunked::" . $e->getMessage(),E_USER_NOTICE);
			 return false;
		}
		
		return $latitude;
	}
	 /**
	 * @description: Get Latitude of user fromits ip address 
	 * using freegeoip api
	 * 
	 * @param $ip
	 * @return  $latitude
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
			/**
			 * Alternate method when seasonal api doesnot work
			 * DB csv file of ip address range with its
			 * corresponding latitude
			 * Reading this csv file and providing the latitude
			 * of user with its ip address
			 * */
			if($latitude == '') {
				
				list($a,$b,$c,$d)= explode('.', $ip);

				$ipnumber = 16777216*$a + 65536*$b + 256*$c + $d;
				$filepath = Mage::helper('seasons/data')->getStoreFilecsvConfig(); 
				
				$latitude = $this->file_get_contents_chunked($filepath,4096,function($chunk,&$handle,$iteration) {
   
					/* * Do what you will with the {&chunk} here
						* {$handle} is passed in case you want to seek
						** to different parts of the filefile_get_contents_chunked
						* {$iteration} is the section fo the file that has been read so
						* ($i * 4096) is your current offset within the file.
					*/
				
				},$ipnumber);


			}

			return $latitude;
		}
		catch(Exception $e) {
			echo $e->getMessage(); die;
		}

	}
	

	 /**
	 * @description: Based on User Ip , Latitiude is get from api 
	 * Products is display on frontend
	 * 
	 * @param $ip
	 * @return  $seasonProductIds
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
			$currentDate = Mage::getModel('core/date')->date('Y-m-d'); 
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
				$paSeasonModel->getSelect()->where("'$currentDate' BETWEEN `start_date` AND `end_date`");
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
			
				
			}elseif($latitudeOfUser < -23.5 && $latitudeOfUser > -90) {
				
				$hemisphereOfUser = 'Southern'; 
				$paSeasonModel->addFieldToFilter('hemisphere',array('eq'=>$hemisphereOfUser));
				$paSeasonModel->getSelect()->where("'$currentDate' BETWEEN `start_date` AND `end_date`");
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
				$paSeasonModel->getSelect()->where("$currentDate BETWEEN `start_date` AND `end_date`");
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
				$paSeasonModel->getSelect()->where("'$currentDate' BETWEEN `start_date` AND `end_date`");
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
