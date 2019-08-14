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
class Netsol_Seasons_Block_Paseasons extends Mage_Catalog_Block_Product_Abstract
{   

	/**
	 * General setting instance
	 *
	 * @var  Netsol_Seasons_Helper_Data 
	 */
    protected $getsetting = null;
    
    /**
	 * Season banner block
	 *
	 * @var  Season banner collection 
	 */
    protected $predictiveSeasonBannerBlock = null;
    
     /**
	 * Season Productid
	 *
	 * @var  Season Productid
	 */
    protected $seasonProducts = null;

     /**
	 * initiate instance
	 *
	 * @var  Netsol_Seasons_Helper_Data 
	 */
    public function __construct() {
		   
        $this->getsetting = Mage::helper('seasons/data');
    }  
    
    /**
     * @description Retrieve collection based on setting
     *
     * @param		
     * @param		
     * @return	productCollection
     */
    public function predictiveSeasonBannerBlock()
    {
		if ($this->getsetting->isEnabled ()) {
				$this->predictiveSeasonBannerBlock = $this->_predictiveSeasonBannerBlock();
				return $this->predictiveSeasonBannerBlock;
		}
	}
	 /**
     * @description Retrieve collection based on setting
     *
     * @param		
     * @param		
     * @return	productCollection
     */
    public function seasonsBlock()
    {
		if ($this->getsetting->isEnabled ()) {
				$this->seasonProducts = $this->_getSeasonProducts();
				return $this->seasonProducts;

		}
	}
	/**
	 * @description: Based on Seasons model products are recommend 
	 * 
	 * 
	 * @param Season api
	 * @return  $productIds
	 * */
	 protected function _getSeasonProducts()
	 {
		 try {
			$ip = $this->getsetting->getDefaultIpaddress();
			if($ip == '') {
				$ip  = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			}
			$seasonHelper = Mage::helper('seasons/seasonapi');
			$seasonsPids =$seasonHelper->getProductOfSeason($ip); 
			return $seasonsPids;
		 } catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	 }
	 /**
	 * @description: Get all banners
	 * according to seasons
	 * 
	 * @param order item
	 * @return  $productCollection
	 * */
	 protected function _predictiveSeasonBannerBlock()
	 {
			$bannerCollection = array();

			
			$ip = $this->getsetting->getDefaultIpaddress();
			if($ip == '') {
				$ip  = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			}
			//$bannerCollection = Mage::getModel('predictiveanalytics/paseason')->getCollection();
			$bannerCollection = Mage::helper('seasons/seasonbanner')->getBannerOfSeason($ip);
			
			return $bannerCollection;
	 }

}
