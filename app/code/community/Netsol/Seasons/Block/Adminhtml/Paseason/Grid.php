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
class Netsol_Seasons_Block_Adminhtml_Paseason_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("paseasonGrid");
				$this->setDefaultSort("seasonid");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("seasons/paseason")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("seasonid", array(
					"header" => Mage::helper("seasons")->__("ID"),
					"align" =>"right",
					"width" => "50px",
					"type" => "number",
					"index" => "seasonid",
				));
                
				$this->addColumn('hemisphere', array(
					'header' => Mage::helper('seasons')->__('Hemisphere'),
					'index' => 'hemisphere',			
				));
					
				$this->addColumn('season', array(
					'header' => Mage::helper('seasons')->__('Season'),
					'index' => 'season',				
					'renderer'  => 'Netsol_Seasons_Block_Adminhtml_Renderer_Customseason'
				));
				$this->addColumn('start_date', array(
					'header' => Mage::helper('seasons')->__('Start Date'),
					'type'      => 'date',
					'index' => 'start_date',
					'renderer'  => 'Netsol_Seasons_Block_Adminhtml_Renderer_Customdate'				
				));
				$this->addColumn('end_date', array(
					'header' => Mage::helper('seasons')->__('End Date'),
					'type'      => 'date',
					'index' => 'end_date',
					'renderer'  => 'Netsol_Seasons_Block_Adminhtml_Renderer_Customdate'				
				));
				

				$this->addColumn('action', array(
					'header'   => Mage::helper('seasons')->__('Action'),
					'width'    => 15,
					'sortable' => false,
					'filter'   => false,
					'type'     => 'action',
					'getter'    => 'getId',
					'actions'  => array(
						array(
							'url'     => array('base'=> '*/*/edit'),
							'field'     => 'id',
							'caption' => Mage::helper('seasons')->__('Edit'),
						),
					)
				));
				
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
	
		

}
