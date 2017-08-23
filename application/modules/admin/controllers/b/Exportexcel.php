<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Exportexcel extends CI_Controller {
 
    function __construct()
    {
        parent::__construct();
        	//load our new PHPExcel library
		$this->load->library('Excel');
        // Load the Model
	   $this->load->model('Apijson_model');
	   $this->load->model('Adminna_log_activity_model');
    }

	public function index(){
		$this->download_to_excel();
	}
	
	public function download_to_excel()
	{
			//////////////////	
			$startdate = $this->input->get_post('startdate',TRUE);
			$enddate = $this->input->get_post('enddate',TRUE);
			$total_rows= $this->Adminna_log_activity_model->totallogactivity($startdate,$enddate);
			if($startdate=='' && $enddate=='' ){$limit = 100;}else{$limit = $total_rows;}
			$segment = 3;
			$pageSize=$limit;
			$start=1;
		 	//////////////////	
        		$this->load->library("PHPExcel");
        		$phpExcel = new PHPExcel();
        		$prestasi = $phpExcel->setActiveSheetIndex(0);
        				//merger
        		$phpExcel->getActiveSheet()->mergeCells('A1:C1');
        				//manage row hight
        		$phpExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(25);
        				//style alignment
        		$styleArray = array(
        			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			),
        		);
        		$phpExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);					
        		$phpExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
        				//border
        		$styleArray1 = array(
        		 'borders' => array(
        			'allborders' => array(
        			 'style' => PHPExcel_Style_Border::BORDER_THIN
        			)
        		 )
        		);
        				//background
        		$styleArray12 = array(
        			'fill' => array(
        				'type' => PHPExcel_Style_Fill::FILL_SOLID,
        				'startcolor' => array(
        					'rgb' => 'FFEC8B',
        				),
        			),
        		);
		 
		//freeepane
		$phpExcel->getActiveSheet()->freezePane('A4');
		//coloum width
		$phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4.1);
		$phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$prestasi->setCellValue('A1', 'Studens Report');
		$phpExcel->getActiveSheet()->getStyle('A2:C2')->applyFromArray($styleArray);
		$prestasi->setCellValue('A2', 'No');				
		$prestasi->setCellValue('B2', 'Name');
		$prestasi->setCellValue('C2', 'Address');
		if($startdate!==''){
				$data = $this->Adminna_log_activity_model->view_log($pageIndex, $limit,$startdate,$enddate);
			}else{
				$data = $this->Adminna_log_activity_model->view_log($pageIndex, $limit);
			}
 Debug($data); die();
		$no=0;
		$rowexcel = 2;
				foreach($data->result() as $row)
		{
			$no++;
			$rowexcel++;
			$phpExcel->getActiveSheet()->getStyle('A'.$rowexcel':C'.$rowexcel)->applyFromArray($styleArray);
			$phpExcel->getActiveSheet()->getStyle('A'.$rowexcel':C'.$rowexcel)->applyFromArray($styleArray1);
			$prestasi->setCellValue('A'.$rowexcel, $no);						
			$prestasi->setCellValue('B'.$rowexcel, $row->name);
			$prestasi->setCellValue('C'.$rowexcel, $row->address);
				}
			$prestasi->setTitle('Students Report');
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"Students Report.xls\"");
		header("Cache-Control: max-age=0");
		$objWriter = PHPExcel_IOFactory::createWriter($phpExcel, "Excel5");
		$objWriter->save("php://output");		
    }
    
    
	public function readexcel(){
		$file = './files/test.xlsx';
		//load the excel library
		$this->load->library('excel');
		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
		    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    //header will/should be in row 1 only. of course this can be modified to suit your need.
		    if ($row == 1) {
		        $header[$row][$column] = $data_value;
		    } else {
		        $arr_data[$row][$column] = $data_value;
		    }
		}
		//send the data in an array format
		$data['header'] = $header;
		$data['values'] = $arr_data;
		 
    }
    
    
    

}












