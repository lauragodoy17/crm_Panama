<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

include("../lib/autoload-phpspreadsheet.php");
require_once("../lib/ZipStream/src/Option/Archive.php");
require_once("../lib/MyCLabs/Enum/Enum.php");
require_once("../lib/ZipStream/src/Option/Method.php");
require_once("../lib/ZipStream/src/ZipStream.php");
require_once("../lib/ZipStream/src/Bigint.php");
require_once("../lib/ZipStream/src/Option/File.php");
require_once("../lib/ZipStream/src/File.php");
require_once("../lib/ZipStream/src/Option/Version.php");
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

require_once("../php/aut.php");
include("../conexion/bdd.php");
$objSpreadsheet = new Spreadsheet();
$objSpreadsheet->getProperties()->setCreator("Ing. Alejandro Rangel");
$objSpreadsheet->getProperties()->setTitle("Reporte OPD");
$objSpreadsheet->createSheet(0);
$objSpreadsheet->setActiveSheetIndex(0);
$objSpreadsheet->getActiveSheet()->setTitle("Reporte OPD");
$objSpreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);


$estilo_centrar = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ]
];

$estilo_negrita = array(
    'font' => array(
        'bold' => true
    )
);

$estilo_fuente = array(
    'font' => array(
        'size' => 8.5
    )
);

$estilo_borde = [
    'borders' => [
        'top' => ['style' => Border::BORDER_THIN],
        'right' => ['style' => Border::BORDER_THIN],
        'bottom' => ['style' => Border::BORDER_THIN],
        'left' => ['style' => Border::BORDER_THIN],
    ]

    
];

//poner imagen
$drawing = new Drawing();
$drawing->setName('test_img');
$drawing->setDescription('test_img');
$drawing->setPath('../vendors/images/logo_eureka.png'); // Ruta relativa o absoluta a la imagen
$drawing->setHeight(100); // Puedes ajustar el tamaño si deseas
$drawing->setCoordinates('A1'); // Posición en la hoja
$drawing->setWorksheet($objSpreadsheet->getActiveSheet());

$objSpreadsheet->getActiveSheet()->mergeCells('A1:B4');


$objSpreadsheet->getActiveSheet()->mergeCells('D2:F2');
$objSpreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->SetCellValue("D2", "REPORTE DE OPD");


$fecha=date("Y-m-d");

$objSpreadsheet->getActiveSheet()->getStyle('C4')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D4')->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Fecha reporte");
$objSpreadsheet->getActiveSheet()->SetCellValue("D4", "$fecha");

$objSpreadsheet->getActiveSheet()->SetCellValue("A6", "Consecutivo");
$objSpreadsheet->getActiveSheet()->SetCellValue("B6", "Fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("C6", "Usuario");
$objSpreadsheet->getActiveSheet()->SetCellValue("D6", "Estado");
$objSpreadsheet->getActiveSheet()->SetCellValue("E6", "Solicitante");
$objSpreadsheet->getActiveSheet()->SetCellValue("F6", "Cliente");
$objSpreadsheet->getActiveSheet()->SetCellValue("G6", "Fecha Entrega Solicitada");
$objSpreadsheet->getActiveSheet()->SetCellValue("H6", "Observaciones");
$objSpreadsheet->getActiveSheet()->SetCellValue("I6", "Titulo");
$objSpreadsheet->getActiveSheet()->SetCellValue("J6", "Cantidad");
$objSpreadsheet->getActiveSheet()->SetCellValue("K6", "Entrega 1");
$objSpreadsheet->getActiveSheet()->SetCellValue("L6", "Fecha / Observaciones E1");
$objSpreadsheet->getActiveSheet()->SetCellValue("M6", "Entrega 2");
$objSpreadsheet->getActiveSheet()->SetCellValue("N6", "Fecha / Observaciones E2");
$objSpreadsheet->getActiveSheet()->SetCellValue("O6", "Entrega 3");
$objSpreadsheet->getActiveSheet()->SetCellValue("P6", "OFecha / Observaciones E3");
$objSpreadsheet->getActiveSheet()->SetCellValue("Q6", "Total entregas");
$objSpreadsheet->getActiveSheet()->SetCellValue("R6", "Impresora");
$objSpreadsheet->getActiveSheet()->SetCellValue("S6", "Click");
$objSpreadsheet->getActiveSheet()->SetCellValue("T6", "Total clicks");
$objSpreadsheet->getActiveSheet()->SetCellValue("U6", "Valor");

$objSpreadsheet->getActiveSheet()->getStyle("A1:U1")->getFont()->getColor()->applyFromArray(
	array(
	'rgb' => '#251919'
	)
);
$objSpreadsheet->getActiveSheet()->getStyle("A6:U6")->getFont()->getColor()->applyFromArray(
	array(
	'rgb' => '#251919'
	)
);

$objSpreadsheet->getActiveSheet()->getStyle('A6:U6')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF84'
        ]
    ]
]);



$desde=$_POST["desde"]." "."00:00:00";
$hasta=$_POST["hasta"]." "."23:59:59";
$sql="SELECT o.id, o.fecha, o.estado, o.solicitante, o.observaciones, o.fecha_ent_s, o.conse, CONCAT(u.nombres,' ',u.apellidos) AS usuario, c.cliente, l.id as lid, l.libro, l.cantidad, l.click, l.impresora, l.valor_click  FROM ordenes_produccion o JOIN usuarios u ON u.id=o.usuario JOIN clientes c ON c.id=o.cliente JOIN libros_opd l ON l.opid=o.id WHERE o.fecha BETWEEN '".$desde."' AND '".$hasta."' ORDER BY id DESC";


$req = $bdd->prepare($sql);
$req->execute();
$opds = $req->fetchAll();


$conta=7;

foreach ($opds as $opd) {


	
  
    $objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$opd[id]");
    
	
	$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$opd[fecha]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$opd[usuario]");

    if ($opd["estado"] == 0) {
        $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "Pendiente");
    }else{
        $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "Cumplida");
    }
    
	$objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$opd[solicitante]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$opd[cliente]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$opd[fecha_ent_s]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$opd[observaciones]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$opd[libro]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "$opd[cantidad]");

    $sql="SELECT fecha, cant_entregada, cant_entregada, observacion_entrega FROM entregas_opd WHERE id_libro_opd='".$opd["lid"]."' ORDER BY id LIMIT 1 OFFSET 0";

    $req = $bdd->prepare($sql);
    $req->execute();
    $ent1 = $req->fetch();

    if (!empty($ent1)) {
        
        $entrega1= $ent1["fecha"]." / ".$ent1["observacion_entrega"];

        $objSpreadsheet->getActiveSheet()->SetCellValue("K$conta", "$ent1[cant_entregada]");
        $objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "$entrega1");

    }
    


    $sql="SELECT fecha, cant_entregada, cant_entregada, observacion_entrega FROM entregas_opd WHERE id_libro_opd='".$opd["lid"]."' ORDER BY id LIMIT 1 OFFSET 1";

    $req = $bdd->prepare($sql);
    $req->execute();
    $ent2 = $req->fetch();
    if (!empty($ent2)) {
         $entrega2= $ent2["fecha"]." / ".$ent2["observacion_entrega"];

        $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "$ent2[cant_entregada]");
        $objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "$entrega2");
    }
   

    $sql="SELECT fecha, cant_entregada, cant_entregada, observacion_entrega FROM entregas_opd WHERE id_libro_opd='".$opd["lid"]."' ORDER BY id LIMIT 1 OFFSET 2";

    $req = $bdd->prepare($sql);
    $req->execute();
    $ent3 = $req->fetch();
    if (!empty($ent3)) {
        $entrega3= $ent3["fecha"]." / ".$ent3["observacion_entrega"];

        $objSpreadsheet->getActiveSheet()->SetCellValue("O$conta", "$ent3[cant_entregada]");
        $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "$entrega3");
    }
    $total_entregas = 
        ($ent1["cant_entregada"] ?? 0) + 
        ($ent2["cant_entregada"] ?? 0) + 
        ($ent3["cant_entregada"] ?? 0);

    $objSpreadsheet->getActiveSheet()->SetCellValue("Q$conta", "$total_entregas");

    $sql="SELECT impresora FROM impresoras_taller WHERE id='".$opd["impresora"]."'";

    $req = $bdd->prepare($sql);
    $req->execute();
    $impresora = $req->fetch();
    if (!empty($impresora["impresora"])) {
        $objSpreadsheet->getActiveSheet()->SetCellValue("R$conta", "$impresora[impresora]");
    }
    
    $objSpreadsheet->getActiveSheet()->SetCellValue("S$conta", "$opd[click]");

    $total_clicks=$total_entregas * $opd["click"];

    $objSpreadsheet->getActiveSheet()->SetCellValue("T$conta", "$total_clicks");

    $valor=$total_clicks* $opd["valor_click"];

    $objSpreadsheet->getActiveSheet()->SetCellValue("U$conta", "$valor");
   
	$conta++;

  

}	

$objSpreadsheet->getActiveSheet()->getStyle("U7:U$conta")
    ->getNumberFormat()
    ->setFormatCode(
    '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
);

	
function excelColumnRange($start, $end) {
    $columns = [];
    $current = $start;
    while ($current !== $end) {
        $columns[] = $current;
        $current++;
    }
    $columns[] = $end;
    return $columns;
}

foreach (range('A', 'Z') as $columnID) {
  $objSpreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);  
}
foreach (excelColumnRange('AA', 'ZZ') as $columnID) {
  $objSpreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);  
}


$objWriter = new Xlsx($objSpreadsheet); //Escribir archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header('Content-Disposition: attachment; filename="OPD.xlsx"');


header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
$objWriter->save('php://output');
?>