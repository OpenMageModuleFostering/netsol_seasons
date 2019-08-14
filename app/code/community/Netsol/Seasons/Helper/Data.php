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
class Netsol_Seasons_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Path to store config front-end output is enabled or disabled is stored
	 *
	 * @var  string 
	 */
    const XML_PATH_STATUS = 'pa_seasonssetting/pa_seasons/enable';   
    
    /**
     * Path to store config of max product count options is stored
	 *
	 * @var  string 
	 */
    const XML_PATH_MAX_PRODUCT_COUNT = 'pa_seasonssetting/pa_seasons/max_product_count';
    
     /**
     * Path to store time period to enable jquery
	 *
	 * @var  string 
	 */
    const XML_PATH_ENABLE_JQUERY = 'pa_seasonssetting/pa_seasons/enabled_jquery';
    
     /**
     * Path to store Seasons Heading
	 *
	 * @var  string 
	 */
    const XML_PATH_HEADING = 'pa_seasonssetting/pa_seasons/pa_seasons_heading';
    
     /**
     * Default ip addres for checking season
	 * @var  string 
	 */
    const XML_PATH_PREDICTIVE_SEASON_API = 'pa_seasonssetting/pa_seasons/season_check_ip';

     /**
     * To Enable season banner
	 * @var  string 
	 */
    const XML_PATH_SEASON_SLIDER_ENABLED = 'pa_seasonssetting/pa_seasons/season_slider_enabled';
    
     /**
     * To get csv file path
	 * @var  string 
	 */
    const XML_PATH_FILE_CSV_PATH = 'pa_seasonssetting/pa_seasons/upload';
    
   	 /**
	 * enable/disable 
	 *
	 * @var  boolean 
	 */
    protected $isEnabled = null;

     /**
     * Max product to display
     *
     * @var  number 
     */
    protected $maxProductCount = null;
    
	 /**
     * Enable Jquery
     *
     * @var  number 
     */
    protected $enableJquery = null;
    
	 /**
     * Heading
     *
     * @var  number 
     */
    protected $heading = null;
    
	 /**
     * season default Ip
     *
     * @var  number 
     */
    protected $seasonDefaultIpaddress = null;
    
	 /**
     * season slider enabled
     *
     * @var  number 
     */
    protected $seasonSliderEnabled = null;

	/**
     * csv file path
     *
     * @var  number 
     */
    protected $csvFilePath = null;
    
    public function __construct()
    {
        if(($this->isEnabled = $this->_isEnabled())) {
			$this->maxProductCount = $this->_getmaxProductCount();
			$this->enableJquery = $this->_getEnableJquery();
			$this->heading = $this->_getHeading();
			$this->seasonDefaultIpaddress = $this->_getDefaultIpaddress();
			$this->seasonSliderEnabled = $this->_getSeasonSliderEnabled();
			$this->csvFilePath = $this->_getStoreFilecsvConfig();
         }
    }

    /**
     * @description Check whether is enable or not
     *
     * @param		no
     * @return		boolean
     */
    public function isEnabled()
    {
        return (bool) $this->isEnabled;
    }
    

    /**
     * @description Retrieve max product to display
     *
     * @param		no
     * @return		string
     */
    public function getMaxProductCount()
    {
        return $this->maxProductCount;
    }

    /**
     * @description Enable Jquery
     *
     * @param		no
     * @return		string
     */
    public function getEnableJquery()
    {
        return $this->enableJquery;
    }
    
    /**
     * @description Heading
     *
     * @param		no
     * @return		string
     */
    public function getHeading()
    {
        return $this->heading;
    }

	/**
     * @description Default Ip address for season model
     *
     * @param		no
     * @return		string
     */
    public function getDefaultIpaddress()
    {
        return $this->seasonDefaultIpaddress;
    }
    
	/**
     * @description to ebavle season slider
     *
     * @param		no
     * @return		string
     */
    public function getSeasonSliderEnabled()
    {
        return $this->seasonSliderEnabled;
    }
 
 	/**
     * @description: get csv file path
     * @param		no
     * @return		csv file path $filepath
     */
    public function getStoreFilecsvConfig($uploadDownload)
    {
		$filepath = Mage::getBaseDir('base').'/var/netsol/seasons/csv/uploads/'.$this->csvFilePath;
        return $filepath;
    }

    /**
     * @description retrieve options
     *
     * @param		no
     * @return		string
     */
     
    protected function _isEnabled()
    {
        return $this->_getStoreConfig(self::XML_PATH_STATUS);
    }
	protected function _getMaxProductCount()
	{
		return $this->_getStoreConfig(self::XML_PATH_MAX_PRODUCT_COUNT);
	}
	
	protected function _getEnableJquery()
	{
		return $this->_getStoreConfig(self::XML_PATH_ENABLE_JQUERY);
	}
	protected function _getHeading()
	{
		return $this->_getStoreConfig(self::XML_PATH_HEADING);
	}
	protected function _getDefaultIpaddress()
    {
        return $this->_getStoreConfig(self::XML_PATH_PREDICTIVE_SEASON_API);
    }
	protected function _getSeasonSliderEnabled()
    {
        return $this->_getStoreConfig(self::XML_PATH_SEASON_SLIDER_ENABLED);
    }
    protected function _getStoreConfig($xmlPath)
    {
        return Mage::getStoreConfig($xmlPath, Mage::app()->getStore()->getId());
    }
    protected function _getStoreFilecsvConfig()
    {
        return Mage::getStoreConfig(self::XML_PATH_FILE_CSV_PATH);
    }

}
	 
