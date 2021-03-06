<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


// Set document properties
$objPHPExcel->getProperties()->setCreator("Rifa")
							 ->setLastModifiedBy("Rifa")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Creacion para Office 2007 XLSX, generado via web.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Resultado");



// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', '#')
            ->setCellValue('C2', 'ID Voucher')
            ->setCellValue('D2', 'Fecha Transacción')
            ->setCellValue('E2', 'Cantidad Boletos')
            ->setCellValue('F2', 'Monto')
            ->setCellValue('G2', 'Usuario');            
            
            
            /*->setCellValue('I2', 'Estado');*/

// Miscellaneous glyphs, UTF-8
$number = 3;
$count = 1;
$mes = array(1 => 'Ene', 2 => 'Feb', 3=> 'Mar', 4 =>'Abr', 5 => 'May', 6 => 'Jun',
                                            7 => 'Jul',8 => 'Ago',9 => 'Sep',10 => 'Oct',11 => 'Nov',12 => 'Dic');

foreach ($transaction as $row){  

    //$dia = $row->day;
    //$m = $row->month;
    //$year =$row->year;    
    $mail = $this->crud_model->get_correo_by_id_transaccion($row->idtransaccion);
    $idvoucher = strval($row->idtransaccion);
    while (strlen($idvoucher) < 10){
        $idvoucher = "0".$idvoucher;
    }
    $idvoucher = "#".$idvoucher;
    $str_num = number_format( $row->monto, 0, ',', '.' ); 
    $str_num = "$ ".$str_num;
    $objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('B'.$number, $count++)
        ->setCellValue('C'.$number, $idvoucher)
        ->setCellValue('D'.$number, $row->fecha_transaccion)
        ->setCellValue('E'.$number, $row->cantidad_numeros)
        ->setCellValue('F'.$number, $str_num)
        ->setCellValue('G'.$number, $mail);
        
       
    $number = $number + 1;
}
    

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ventas Registrados');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Ventas.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
