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
class Netsol_Seasons_Adminhtml_PaseasonController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("seasons/paseason")->_addBreadcrumb(Mage::helper("adminhtml")->__("Season  Manager"),Mage::helper("adminhtml")->__("Season Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("seasons"));
			    $this->_title($this->__("Season Manager"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("seasons"));
				$this->_title($this->__("Paseason"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("seasons/paseason")->load($id);
				if ($model->getId()) {
					Mage::register("paseason_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("seasons/paseason");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Paseason Manager"), Mage::helper("adminhtml")->__("Paseason Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Paseason Description"), Mage::helper("adminhtml")->__("Paseason Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("seasons/adminhtml_paseason_edit"))->_addLeft($this->getLayout()->createBlock("seasons/adminhtml_paseason_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("seasons")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

			$this->_title($this->__("Predictiveanalytics"));
			$this->_title($this->__("Paseason"));
			$this->_title($this->__("New Item"));

			$id   = $this->getRequest()->getParam("id");
			$model  = Mage::getModel("seasons/paseason")->load($id);

			$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register("paseason_data", $model);

			$this->loadLayout();
			$this->_setActiveMenu("seasons/paseason");

			$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Paseason Manager"), Mage::helper("adminhtml")->__("Paseason Manager"));
			$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Paseason Description"), Mage::helper("adminhtml")->__("Paseason Description"));


			$this->_addContent($this->getLayout()->createBlock("seasons/adminhtml_paseason_edit"))->_addLeft($this->getLayout()->createBlock("seasons/adminhtml_paseason_edit_tabs"));

			$this->renderLayout();

		}
		
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();
			$start_date = $post_data['start_date'];
			$end_date = $post_data['end_date'];

			if ($post_data) {

				try {

						$model = Mage::getModel("seasons/paseason")
						->addData($post_data)
						->setHemisphere($post_data['hemisphere'])
						->setSeason($post_data['season'])
						->setStartDate($start_date)
						->setEndDate($end_date)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Paseason was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setPaseasonData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					 } catch (Exception $e) {
							Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
							Mage::getSingleton("adminhtml/session")->setPaseasonData($this->getRequest()->getPost());
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
						$model = Mage::getModel("seasons/paseason");
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
				$ids = $this->getRequest()->getPost('seasonids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("seasons/paseason");
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
			$fileName   = 'paseason.csv';
			$grid       = $this->getLayout()->createBlock('seasons/adminhtml_paseason_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'paseason.xml';
			$grid       = $this->getLayout()->createBlock('seasons/adminhtml_paseason_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
