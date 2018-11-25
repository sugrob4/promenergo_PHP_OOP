<?php
class Pricelist_Controller extends Base {

	protected $objPHPExcel;
	protected $catalog;
	
	protected function input() {
		parent::input();
	
	include(LIB."/PHPExcel.php");
	$this->objPHPExcel = new PHPExcel();
				
	$this->objPHPExcel->setActiveSheetIndex(0);
	$active_sheet = $this->objPHPExcel->getActiveSheet();
		
	
	//$this->objPHPExcel->createSheet();	
		
	$active_sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$active_sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		
	$active_sheet->getPageMargins()->setTop(0.5);
	$active_sheet->getPageMargins()->setRight(0.75);
	$active_sheet->getPageMargins()->setLeft(0.75);
	$active_sheet->getPageMargins()->setBottom(1);
	
	$active_sheet->getDefaultRowDimension()->setRowHeight(22);
		
	$title = $active_sheet->setTitle('“ПромСтройЭнерго” - Прайс лист');
		
	$active_sheet->getHeaderFooter()->setOddFooter('&L&B'.$active_sheet->getTitle().'&RСтраница &P из &N');
		
	$this->objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$this->objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 
		
	$active_sheet->getColumnDimension('A')->setWidth(30);
	$active_sheet->getColumnDimension('B')->setWidth(70);
	$active_sheet->getColumnDimension('C')->setWidth(10);
	
	///////////////////////////////////////////	
	$active_sheet->mergeCells('A1:C1');
	$active_sheet->getRowDimension('1')->setRowHeight(60);
	$active_sheet->setCellValue('A1', 'ПромСтройЭнерго');
	
	$style_header = array(
	
		'font' => array(
					'bold' => true,
					'name' => 'Times New Roman',
					'size' => 20,
					'color' => array('rgb'=>'ffffff')
					),
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					),
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'2e778f')
					)						
	
	);
	$active_sheet->getStyle('A1:C1')->applyFromArray($style_header);
	
	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Logo');
	$objDrawing->setPath('images/price_logo.png');
	
	$objDrawing->setWorksheet($this->objPHPExcel->getActiveSheet());
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(5);
	$objDrawing->setOffsetY(3);
	
	//////////////////////////////////////////////	
	$active_sheet->mergeCells('A2:C2');
	$active_sheet->setCellValue('A2', "Все виды сварного, трубного и другого оборудования");
	
	$style_slogan = array(
	
		'font' => array(
					'size' => 11,
					'color' => array('rgb'=>'ffffff'),
					'italic' => TRUE
					),
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
					),
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'2e778f')
					),
		'borders' => array(
					'bottom'=>array(
						'style'=>PHPExcel_Style_Border::BORDER_THICK		
								)	
						
					)									
	
	);
	$active_sheet->getStyle('A2:C2')->applyFromArray($style_slogan);
	
	//////////////////////////////////////	
		$active_sheet->mergeCells('A4:B4');
	$active_sheet->setCellValue('A4', 'Дата создания прасйслиста:');
	
	$style_tdate = array(
	
		
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
					
					),
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'CFCFCF')
					),
		'borders' => array(
					'right'=>array(
						'style'=>PHPExcel_Style_Border::BORDER_NONE		
								)	
						
					)									
	
	);
	
	$active_sheet->getStyle('A4:B4')->applyFromArray($style_tdate);
	///////////////////////////////////////////////////////////	
	$date = date("d-m-Y");
	$active_sheet->setCellValue('C4', $date);
	$active_sheet->getStyle('C4')
		            ->getNumberFormat()
		            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX14);
	
	$style_date = array(
	
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'CFCFCF')
					),
		'borders' => array(
					'left'=>array(
						'style'=>PHPExcel_Style_Border::BORDER_NONE		
								)	
						
					)									
	
	);
	
	$active_sheet->getStyle('C4')->applyFromArray($style_date);
	//////////////////////////////////////////////				
		
	$active_sheet->setCellValue('A6', 'Название');
	$active_sheet->setCellValue('B6', 'Описание');
	$active_sheet->setCellValue('C6', 'Цена');	
	
	
	$style_hprice = array(
	
		
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					
					),
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'2e778f')
					),
		'font' => array(
					'bold' => true,
					'italic' => true,
					'name' => 'Times New Roman',
					'size' => 10,
					'color' => array('rgb'=>'ffffff')
					)											
	
	);
	
	$active_sheet->getStyle('A6:C6')->applyFromArray($style_hprice);
	//////////////////////////////////////
	
	$style_parent = array(
	
		
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					
					),
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'CFCFCF')
					),
		'font' => array(
					'bold' => true,
					'italic' => false,
					'name' => 'Times New Roman',
					'size' => 14,
					'color' => array('rgb'=>'000000')
					)											
	
	);
	
	$style_category= array(
	
		
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					
					),
		'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'CFCFCF')
					),
		'font' => array(
					'bold' => true,
					'italic' => true,
					'name' => 'Times New Roman',
					'size' => 11,
					'color' => array('rgb'=>'432332')
					)											
	
	);
	
	
	$style_cell= array(
	
		
		'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
					'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'wrap' => true,
					
					),
		'font' => array(
					'color' => array('rgb'=>'432332')
					)											
	
	);
	
	$style_wrap = array(

		'borders' => array(
					'allborders'=>array(
						'style'=>PHPExcel_Style_Border::BORDER_THIN,
						'color'=>array('rgb'=>'696969')	
								),
					'outline'=>	array(
						'style'=>PHPExcel_Style_Border::BORDER_THICK)		
					)				
											
	
	);
	
	///////////////////////////////////////
	
	$this->catalog = $this->ob_m->get_pricelist();
	
	$row_start = 6;
	$curent_row = $row_start;
	foreach($this->catalog as $val) {
		
		if($val['sub']) {
			
			foreach($val as $parent=>$goods) {
				if($parent != 'sub') {
					$curent_row++;
					$active_sheet->mergeCells('A'.$curent_row.':C'.$curent_row);
					$active_sheet->setCellValue('A'.$curent_row,$parent);
					$active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_parent);
					
					if(count($goods) > 0) {
						foreach($goods as $tovar) {
							$curent_row++;
							$active_sheet->setCellValue('A'.$curent_row,$tovar['title']);
							$active_sheet->setCellValue('B'.$curent_row,$tovar['anons']);
							$active_sheet->setCellValue('C'.$curent_row,$tovar['price']);
							
							$active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_cell);
						}
					}
				}
				else {
					foreach($goods as $category=>$tovars) {
						$curent_row++;
						$active_sheet->mergeCells('A'.$curent_row.':C'.$curent_row);
						$active_sheet->setCellValue('A'.$curent_row,$category);
						$active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_category);
						
						foreach($tovars as $item) {
							$curent_row++;
							$active_sheet->setCellValue('A'.$curent_row,$item['title']);
							$active_sheet->setCellValue('B'.$curent_row,$item['anons']);
							$active_sheet->setCellValue('C'.$curent_row,$item['price']);
							$active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_cell);
						}
					}
					
					
				}
			}
		}
		else {
			foreach($val as $parent1=>$goods1) {
				$curent_row++;
				$active_sheet->mergeCells('A'.$curent_row.':C'.$curent_row);
				$active_sheet->setCellValue('A'.$curent_row,$parent1);
				$active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_parent);
				if(count($goods1) > 0) {
					foreach($goods1 as $tovar1) {
							$curent_row++;
							$active_sheet->setCellValue('A'.$curent_row,$tovar1['title']);
							$active_sheet->setCellValue('B'.$curent_row,$tovar1['anons']);
							$active_sheet->setCellValue('C'.$curent_row,$tovar1['price']);
							$active_sheet->getStyle('A'.$curent_row.':C'.$curent_row)->applyFromArray($style_cell);
						}
				}
			}
		}
	}
	$active_sheet->getStyle('A1:C'.$curent_row)->applyFromArray($style_wrap);
	}
	
	protected function output() {
		
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename='pricelist.xls'");
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel,'Excel5');
		$objWriter->save("php://output");
		
		exit();
	}
}
?>