<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$sheetNumber = $_POST['num'];
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['file']['tmp_name']);
$spreadsheet->setActiveSheetIndex($sheetNumber);
$spreadsheet->getActiveSheet()->setShowGridLines(false);
$styleArray = array(
    'borders' => array(
        'allBorders' => array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => array('argb' => '00000000'),
        ),
    ),
);
$spreadsheet->getActiveSheet()
    ->getStyle( $spreadsheet->getActiveSheet()->calculateWorksheetDimension() )
    ->applyFromArray($styleArray);
$spreadsheet->getDefaultStyle()->getFont()->setSize(15);
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Dompdf');

$writer->setSheetIndex($sheetNumber);
$writer->save('pdf\\'.getdate()[0].'.pdf');
echo "PDFbe importalva!"
?>


