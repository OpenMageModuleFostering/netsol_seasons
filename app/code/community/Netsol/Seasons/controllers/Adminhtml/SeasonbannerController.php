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
class Netsol_Seasons_Adminhtml_SeasonbannerController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("seasons/seasonbanner")->_addBreadcrumb(Mage::helper("adminhtml")->__("Season Banner  Manager"),Mage::helper("adminhtml")->__("Season Banner Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("seasons"));
			    $this->_title($this->__("Manager Seasonbanner"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("seasons"));
				$this->_title($this->__("Season Banner"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("seasons/seasonbanner")->load($id);
				if ($model->getId()) {
					Mage::register("seasonbanner_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("seasons/seasonbanner");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Seasonbanner Manager"), Mage::helper("adminhtml")->__("Seasonbanner Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Seasonbanner Description"), Mage::helper("adminhtml")->__("Seasonbanner Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("seasons/adminhtml_seasonbanner_edit"))->_addLeft($this->getLayout()->createBlock("seasons/adminhtml_seasonbanner_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("seasons")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

			$this->_title($this->__("seasons"));
			$this->_title($this->__("Seasonbanner"));
			$this->_title($this->__("New Item"));

			$id   = $this->getRequest()->getParam("id");
			$model  = Mage::getModel("seasons/seasonbanner")->load($id);

			$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register("seasonbanner_data", $model);

			$this->loadLayout();
			$this->_setActiveMenu("seasons/seasonbanner");

			$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Seasonbanner Manager"), Mage::helper("adminhtml")->__("Seasonbanner Manager"));
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Seasonbanner Description"), Mage::helper("adminhtml")->__("Seasonbanner Description"));


			$this->_addContent($this->getLayout()->createBlock("seasons/adminhtml_seasonbanner_edit"))->_addLeft($this->getLayout()->createBlock("seasons/adminhtml_seasonbanner_edit_tabs"));

			$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						
				 //save image
							try{

									if((bool)$post_data['banner_image']['delete']==1) {

												$post_data['banner_image']='';

									}
									else {

										unset($post_data['banner_image']);

										if (isset($_FILES)){

											if ($_FILES['banner_image']['name']) {

												if($this->getRequest()->getParam("id")){
													$model = Mage::getModel("seasons/seasonbanner")->load($this->getRequest()->getParam("id"));
													if($model->getData('banner_image')){
															$io = new Varien_Io_File();
															$io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData('banner_image'))));	
													}
												}
													//$path = Mage::getBaseDir('media') . DS . 'predictiveanalytics' . DS .'seasonbanner'.DS;
													$path = Mage::getBaseDir('media') .DS. 'netsol'.DS. 'seasons'.DS. 'paseason'.DS ;
													$uploader = new Varien_File_Uploader('banner_image');
													$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png')); // or pdf or anything
													$uploader->setAllowRenameFiles(false);
													$uploader->setFilesDispersion(false);
													$destFile = $path.$_FILES['banner_image']['name'];
													$filename = $uploader->getNewFileName($destFile);
													$uploader->save($path, $filename);

													$post_data['banner_image']='netsol/seasons/paseason/'.$filename;
											}
										}
									}

							} catch (Exception $e) {
									Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
									$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
									return;
							}
						//save image


						$model = Mage::getModel("seasons/seasonbanner")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"));
						
						if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
							$model->setCreatedTime(now())
								->setUpdateTime(now());
						} else {
							$model->setUpdateTime(now());
						}
						$model->save();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Seasonbanner was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setSeasonbannerData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setSeasonbannerData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("seasons/seasonbanner");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('seasonbannerid', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("seasons/seasonbanner");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'seasonbanner.csv';
			$grid       = $this->getLayout()->createBlock('seasons/adminhtml_seasonbanner_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'seasonbanner.xml';
			$grid       = $this->getLayout()->createBlock('seasons/adminhtml_seasonbanner_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
