<?php
	class Xlsx  {
		
		protected $CI;
		
        // We'll use a constructor, as you can't directly call a function
        // from a property definition.
        public function __construct()
        {
			// Assign the CodeIgniter super-object
			$this->CI =& get_instance();
		}
		
		public function convert($file,$first_row=1,$date_column = array()){
			$this->CI->load->library('excel');
			
			$inputFileName = $file;  
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);  
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);  
			$objReader->setReadDataOnly(true);  
			$objPHPExcel = $objReader->load($inputFileName);  
			
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
			
			foreach ($cell_collection as $cell) {
				
				$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
				$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
				$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();
				
				if ($row == $first_row) {
					$header[$row][$column] = $data_value;
				} 
				else if ($row > $first_row && $row != $first_row ) {
					
					if(in_array($column,$date_column)){
						
						$date_cell = $objPHPExcel->getActiveSheet()->getCell($column . $row);
						$InvDate= $date_cell->getFormattedValue();
						$InvDate = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($InvDate)); 
						$values[$row][$column] = $InvDate;
					}
					
					else{
						$values[$row][$column] = $data_value;
					}
					
				}
			}
			
			return array($header,$values);
		}
		
		
	}								