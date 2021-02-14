<?php
header('Content-Type: text/html; charset=UTF-8');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load( $_FILES['file']['tmp_name'] );
$sheetCounter=0;
$locale = 'hu';
$validLocale = \PhpOffice\PhpSpreadsheet\Settings::setLocale($locale);
while ($sheetCounter<$spreadsheet->getSheetCount()){
    $spreadsheet->setActiveSheetIndex($sheetCounter);
    $worksheet = $spreadsheet->getActiveSheet();
    $colRow = $worksheet->getHighestRowAndColumn();

    echo 
    '
    <div class="border border-dark">
    <p class="mr-sm-2">Munkafüzet neve: <b id="title'.$sheetCounter.'">'.$spreadsheet->getSheetNames()[$sheetCounter].'</b></p>
    <form id="form_'.$sheetCounter.'">
        <div class="form-row align-items-center">
          <div class="col-auto my-1">
            <label class="mr-sm-2" for="inlineFormCustomSelect">Adatbázis táblanév</label>
            <input class="mr-sm-2" type="text" id="DBtableName'.$sheetCounter.'">
           </div>
         <div class="col-auto my-1">
            <button type="button" class=" btn btn-primary" id="submit_'.$sheetCounter.'" onClick="toDB('.($sheetCounter).')">Importálás adatbázisba</button>
         </div>
         <div class="col-auto my-1">
            <button type="button" class=" btn btn-primary" onClick="visible('.($sheetCounter+0).')">Tábla ki/be</button>
         </div>
         <div class="col-auto">
            <p id="progress'.$sheetCounter.'"></p>
         </div>

       </div>
       <div class="form-row align-items-center">
       <table><tbody>';
            for ($i = 0; $i< \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($colRow['column']); $i++){
                echo '<tr><td>' .$worksheet->getCellByColumnAndRow($i+1,1)->getValue() . '</td><td>
                <select required> 
                    <option value=""></option>
                    <option value="VARCHAR">Szöveg</option>
                    <option value="INT">Egész Szám</option>
                    <option value="FLOAT">Tört szám</option>
                    <option value="DATE">Dátum</option>
                </select>
                </td></tr>';
            } 
       echo'
       </table>
       </div>
     </form>
     </div>
    ';
    echo '<table id='.$sheetCounter.' class="table table-hover table-striped table-bordered" hidden>' . PHP_EOL;
    echo '<tr><td>oszlop/sor</td>';
    for ($i = 0; $i< \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($colRow['column']); $i++){
        echo('<td>' .$worksheet->getCellByColumnAndRow($i+1,1)->getValue() . '</td>');
    }
    echo '</tr>';
    $i=0;
    foreach ($worksheet->getRowIterator() as $row) {
        if($i!=0){
        echo '<tr>' . PHP_EOL;
        echo ('<td>' . (($i)) . '</td>');
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE); 
        $j=0;
        foreach ($cellIterator as $cell) {
            $value = $cell->getFormattedValue();
            echo '<td>' .$value .'</td>' . PHP_EOL;
            $j++;
        }
        echo '</tr>' . PHP_EOL;
        }
        $i++;
    }
    $sheetCounter++;
    echo '</table><br>';
}

?>


