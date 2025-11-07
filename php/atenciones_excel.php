<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
//include("../lib/ZipStream/src/ZipStream.php");
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
$objSpreadsheet->getProperties()->setTitle("Reporte de cubrimiento");
$objSpreadsheet->createSheet(0);
$objSpreadsheet->setActiveSheetIndex(0);
$objSpreadsheet->getActiveSheet()->setTitle("Reporte de cubrimiento");
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

$objSpreadsheet->getActiveSheet()->mergeCells('D2:F2');
$objSpreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->SetCellValue("D2", "REPORTE DE ATENCIONES A CLIENTES");

$sql_periodo="SELECT periodo FROM periodos WHERE id='".$_POST["periodo"]."'";

$req_periodo = $bdd->prepare($sql_periodo);
$req_periodo->execute();
$gp_periodo = $req_periodo->fetch();
$fecha=date("Y-m-d");

$objSpreadsheet->getActiveSheet()->getStyle('C4')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D4')->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("D4", "$fecha");

$objSpreadsheet->getActiveSheet()->SetCellValue("A6", "Consecutivo");
$objSpreadsheet->getActiveSheet()->SetCellValue("B6", "Usuario");
$objSpreadsheet->getActiveSheet()->SetCellValue("C6", "Colegio");
$objSpreadsheet->getActiveSheet()->SetCellValue("D6", "Promotor");
$objSpreadsheet->getActiveSheet()->SetCellValue("E6", "Fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("F6", "Recurso solicitado");
$objSpreadsheet->getActiveSheet()->SetCellValue("G6", "Tipo");
$objSpreadsheet->getActiveSheet()->SetCellValue("H6", "Categoría");
$objSpreadsheet->getActiveSheet()->SetCellValue("I6", "Valor");
$objSpreadsheet->getActiveSheet()->SetCellValue("J6", "Estado");
$objSpreadsheet->getActiveSheet()->SetCellValue("K6", "Acumulado");
$objSpreadsheet->getActiveSheet()->SetCellValue("L6", "Tipo recurso entregado");
$objSpreadsheet->getActiveSheet()->SetCellValue("M6", "Valor recurso entregado");
$objSpreadsheet->getActiveSheet()->SetCellValue("N6", "fecha recurso entregado");
$objSpreadsheet->getActiveSheet()->SetCellValue("O6", "Legalizado");
$objSpreadsheet->getActiveSheet()->SetCellValue("P6", "Contabilizado");


$objSpreadsheet->getActiveSheet()->getStyle('A6:P6')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF84'
        ]
    ]
]);

if ($_POST['promotor']==0) {

	$sql="SELECT s.id,e.estado,s.fecha, s.solicitante, s.estado as idestado, s.contab, s.conse, c.colegio, c.cod_zona, c.id as cid, r.recurso, t.tipo, cat.categoria, r.presupuesto, r.tipo_e, r.valor_e, r.fecha_e, r.legaliza, CONCAT(u.nombres,' ', u.apellidos) AS promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado JOIN colegios c ON c.id=s.id_colegio JOIN recursos_solicitados r ON r.id_solicitud=s.id JOIN tipos_recursos t ON t.id=r.tipo JOIN categoria_recursos cat ON cat.id=r.categoria JOIN usuarios u ON u.id=s.usuario WHERE s.id_periodo='".$_POST["periodo"]."' ORDER BY s.id DESC";

}else{

    $sql="SELECT s.id,e.estado,s.fecha, s.solicitante, s.estado as idestado, s.contab, s.conse, c.colegio, c.cod_zona, c.id as cid, r.recurso, t.tipo, cat.categoria, r.presupuesto, r.tipo_e, r.valor_e, r.fecha_e, r.legaliza, CONCAT(u.nombres,' ', u.apellidos) AS promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado JOIN colegios c ON c.id=s.id_colegio JOIN recursos_solicitados r ON r.id_solicitud=s.id JOIN tipos_recursos t ON t.id=r.tipo JOIN categoria_recursos cat ON cat.id=r.categoria JOIN usuarios u ON u.cod_zona=c.cod_zona WHERE s.id_periodo='".$_POST["periodo"]."' AND u.id='".$_POST["promotor"]."' ORDER BY `s`.`id` DESC";


}



/*$sql = "SELECT e.estado, s.id,s.fecha, s.solicitante, s.cargo, s.fecha_entrega FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado WHERE s.id_colegio='".$colegio["id"]."' AND s.id_periodo='".$gp_periodo['id']."' ORDER BY s.id DESC";*/

$req = $bdd->prepare($sql);
$req->execute();
$solicitudes = $req->fetchAll();


$conta=7;

foreach ($solicitudes as $solicitud) {

	$objSpreadsheet->getActiveSheet()->getStyle("I$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

    $objSpreadsheet->getActiveSheet()->getStyle("K$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

    $objSpreadsheet->getActiveSheet()->getStyle("O$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

    $sql="SELECT CONCAT(nombres,' ', apellidos) AS promotor FROM usuarios WHERE cod_zona='".$solicitud["cod_zona"]."'";

    $req = $bdd->prepare($sql);
    $req->execute();
    $promo_colegio = $req->fetch();

    $sql = "SELECT tipo FROM tipos_recursos WHERE id='".$solicitud["tipo_e"]."'";

    $req = $bdd->prepare($sql);
    $req->execute();
    $tipo_e = $req->fetch();

    $sql = "SELECT SUM(r.valor_e) as total_e FROM solicitudes_recursos s JOIN recursos_solicitados r ON s.id=r.id_solicitud WHERE s.id_colegio='".$solicitud["cid"]."' AND s.id_periodo='".$_POST["periodo"]."' AND s.estado='4';";

    $req = $bdd->prepare($sql);
    $req->execute();
    $total = $req->fetch();
	
    if ($solicitud["id"] < 221) {
        $objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$solicitud[id]");
    }else{
        $objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$solicitud[conse]");
    }
	
	$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$solicitud[promotor]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$solicitud[colegio]");
    if (!empty($promo_colegio["promotor"])) {
        $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "$promo_colegio[promotor]");
    }
	$objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$solicitud[fecha]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$solicitud[recurso]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$solicitud[tipo]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$solicitud[categoria]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$solicitud[presupuesto]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "$solicitud[estado]");
    
    $objSpreadsheet->getActiveSheet()->SetCellValue("K$conta", "$total[total_e]");
    
    
    if (empty($tipo_e["tipo"])) {
        $objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "");
    }else{
        $objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "$tipo_e[tipo]");
    }
    $objSpreadsheet->getActiveSheet()->getStyle("M$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

    $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "$solicitud[valor_e]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "$solicitud[fecha_e]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("O$conta", "$solicitud[legaliza]");

    if ($solicitud["contab"]==0) {
        $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "No");
    }else{
        $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "Si");
    }
    

	$conta++;

    $total_p[]=$solicitud["presupuesto"];

    if ($solicitud["idestado"]==2 || $solicitud["idestado"]==4) {
       $total_a[]=$solicitud["presupuesto"];
    }

    $total_l[]=$solicitud["legaliza"];

}	
$conta++;
if (isset($total_p)) {
    $total_p=array_sum($total_p);
}

if (isset($total_a)) {
    $total_a=array_sum($total_a);
}
if (isset($total_l)) {
    $total_l=array_sum($total_l);
}

$objSpreadsheet->getActiveSheet()->getStyle('H'.$conta)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('I'.$conta)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('N'.$conta)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "Total");

$objSpreadsheet->getActiveSheet()->getStyle("I$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

$objSpreadsheet->getActiveSheet()->getStyle("O$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

if (!empty($total_p)) {
    $objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$total_p");
}

if (!empty($total_l)) {
    $objSpreadsheet->getActiveSheet()->SetCellValue("O$conta", "$total_l");
}



$conta++;

if (isset($total_a)) {

    $objSpreadsheet->getActiveSheet()->getStyle("I$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

    $objSpreadsheet->getActiveSheet()->getStyle('G'.$conta)->applyFromArray($estilo_negrita);
    $objSpreadsheet->getActiveSheet()->getStyle('I'.$conta)->applyFromArray($estilo_negrita);

    $objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "Total aprobado");
    $objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$total_a");
}

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

header('Content-Disposition: attachment; filename="Atenciones.xlsx"');


header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
$objWriter->save('php://output');
exit;
?>