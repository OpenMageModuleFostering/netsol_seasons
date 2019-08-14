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
class Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("seasonbannerGrid");
				$this->setDefaultSort("seasonbannerid");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("seasons/seasonbanner")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("seasonbannerid", array(
				"header" => Mage::helper("seasons")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "seasonbannerid",
				));
                
                $this->addColumn('banner_image',array(
				  'header'    => Mage::helper('seasons')->__('Banner Image'),
				  'align'     =>'center',
				  'index'     => 'image',
				  'filter'    => false,
				  'sortable'  => false,
				  'width'	  =>'150',
				  'renderer'  => 'Netsol_Seasons_Block_Adminhtml_Renderer_Seasonbanner'	  
			  )); 
			  
			 $this->addColumn('hemisphere', array(
				'header' => Mage::helper('seasons')->__('Hemisphere'),
				'align'     =>'center',
				'width'	  =>'150',
				'index' => 'hemisphere',
				'type' => 'options',
				'options'=>Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid::getOptionArray2(),			
			 ));
                
			 $this->addColumn('season', array(
				'header' => Mage::helper('seasons')->__('Season'),
				'align'     =>'center',
				'width'	  =>'150',
				'index' => 'season',
				'type' => 'options',
				'options'=>Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid::getOptionArray1(),			
			 ));
					
			$this->addColumn('sort_order', array(
			  'header'    => Mage::helper('seasons')->__('Sort Order'),
			  'align'     =>'left',
			  'width'     => '80px',
			  'index'     => 'sort_order',
		    ));


		    $this->addColumn('status', array(
			  'header'    => Mage::helper('seasons')->__('Status'),
			  'align'     => 'left',
			  'width'     => '80px',
			  'index'     => 'status',
			  'type'      => 'options',
			  'options'   => array(
				  1 => 'Enabled',
				  0 => 'Disabled',
			  ),
		    ));	
		    $this->addColumn('action',
				array(
					'header'    =>  Mage::helper('seasons')->__('Action'),
					'width'     => '100',
					'type'      => 'action',
					'getter'    => 'getId',
					'actions'   => array(
						array(
							'caption'   => Mage::helper('seasons')->__('Edit'),
							'url'       => array('base'=> '*/*/edit'),
							'field'     => 'id'
						)
					),
					'filter'    => false,
					'sortable'  => false,
					'index'     => 'stores',
					'is_system' => true,
				)
        );
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

			return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('seasonbannerid');
			$this->getMassactionBlock()->setFormFieldName('seasonbannerid');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_seasonbanner', array(
					 'label'=> Mage::helper('seasons')->__('Remove Seasonbanner'),
					 'url'  => $this->getUrl('*/adminhtml_seasonbanner/massRemove'),
					 'confirm' => Mage::helper('seasons')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray1()
		{
            $data_array=array();
            $data_array['']='Please select'; 
			$data_array['Winter']='Winter';
			$data_array['Summer']='Summer';
			$data_array['Autumn']='Autumn';
			$data_array['Spring']='Spring';
			$data_array['Wet']='Wet (Winter)';
			$data_array['Dry']='Dry (Summer)';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray2()
		{
            $data_array=array();
            $data_array['']='Please select'; 
			$data_array['Northern']='Northern';
			$data_array['Southern']='Southern';
			$data_array['Equator-Cancer']='Equator-Cancer';
			$data_array['Equator-Capricorn']='Equator-Capricorn';
            return($data_array);
		}
		static public function getValueArray2()
		{
            $data_array=array();
			foreach(Netsol_Seasons_Block_Adminhtml_Seasonbanner_Grid::getOptionArray2() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		

}
