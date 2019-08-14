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
 * @copyright   Copyright (c) 2015 Netsolutions India (http://www.netsolutions.in)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Netsol_Seasons_Model_Observer
{
	/**
	 * General setting instance
	 *
	 * @var  Netsol_Predictiveanalytics_Helper_Data 
	 */
    protected $getsetting = null;
    
    /**
	 * initiate instance
	 */
    public function __construct() {   
        $this->getsetting = Mage::helper('seasons/data');
    }    

	/**
	 * @description Event after layout block generated
	 * Display block according to page selected at admin config
	 * Display block according to position selected at admin config 
	 * if left postion is enable, we'll unset right and content
	 * @param		$observer
	 * @return		no
	 */
	public function unsetSeasonslayoutBlocks($observer)
	{	
		/**If admin is not logged in**/
		if(!Mage::app()->getStore()->isAdmin()) {
			
			$seasonSliderEnabled	   = $this->getsetting->getSeasonSliderEnabled();
			$observerData         = $observer->getAction()->getLayout();
			$template	           = $observerData->getBlock('root')->getTemplate();
			$currentPageHandle     = Mage::app()->getFrontController()->getAction()->getFullActionName();
			/**if module is enabled**/
			if($this->getsetting->isEnabled()){
				if($currentPageHandle == 'cms_index_index') { //if homepage
					
				}
				
				 if($seasonSliderEnabled == 0) {
					 	$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('paseasons-banner');			
						}
				}
			}else{ // if module is disabled then unset whole blocks
				
				$blockContent = $observerData->getBlock('content');  
				if($blockContent) {
					$blockContent->unsetChild('paseasons-banner');	
					$blockContent->unsetChild('paseasons');	
				}
				if($currentPageHandle == 'cms_index_index')
				{ 
					$observerData->getBlock('head')->removeItem('skin_js', 'js/netsol/seasons/jquery-1.10.2.min.js');

				}
			}
			/**if module is enabled end**/
		}
		/**If admin is not logged in end**/
		
	}

	/**
	 * @description Event fire on adminhtml layout  
	 * @param		$observer
	 * @return		custom layout for our admin module
	 */
	public function addAdminCustomLayoutHandle($observer) {
		$controllerAction = $observer->getEvent()->getAction();
		$layout = $observer->getEvent()->getLayout();
		if ($controllerAction && $layout && $controllerAction instanceof Mage_Adminhtml_System_ConfigController) { // Can be checked in other ways of course
			
			if ($controllerAction->getRequest()->getParam('section') == 'pa_seasonssetting') { 
				$layout->getUpdate()->addHandle('seasons_adminhtml_system_config_edit');
			}
		}
		return $this;
	}
}
