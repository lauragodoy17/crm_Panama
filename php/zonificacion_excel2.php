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


require_once("aut.php");
include("../conexion/bdd.php");

$objSpreadsheet = new Spreadsheet();
$objSpreadsheet->getProperties()->setCreator("Ing. Alejandro Rangel");
$objSpreadsheet->getProperties()->setTitle("Reporte de zonificación");
$objSpreadsheet->createSheet(0);
$objSpreadsheet->setActiveSheetIndex(0);
$objSpreadsheet->getActiveSheet()->setTitle("Reporte de zonificación");
$objSpreadsheet->getActiveSheet()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_LETTER);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objSpreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);



$sql = "SELECT nombres, apellidos, cod_zona, id_pais, tipo FROM usuarios WHERE id='".$_SESSION["id"]."'";
$req = $bdd->prepare($sql);
$req->execute();
$usuario = $req->fetch();
$nombre_completo=$usuario["nombres"]." ".$usuario["apellidos"];
$sql_zona="SELECT zona FROM zonas WHERE codigo='".$usuario["cod_zona"]."'";
$req_zona = $bdd->prepare($sql_zona);
$req_zona->execute();
	$zona = $req_zona->fetch();



$fecha=date("Y-m-d");



//~ Ingreo de datos en la hojda de excel



list($empresa,$n_zona) = explode("/", $zona["zona"]);
$objSpreadsheet->getActiveSheet()->SetCellValue("A1", "Zona");
$objSpreadsheet->getActiveSheet()->SetCellValue("A2", "$zona[zona]");
$objSpreadsheet->getActiveSheet()->SetCellValue("B1", "Asesor");
$objSpreadsheet->getActiveSheet()->SetCellValue("B2", "$nombre_completo");


$objSpreadsheet->getActiveSheet()->SetCellValue("C1", "Fecha Reporte");
$objSpreadsheet->getActiveSheet()->SetCellValue("C2", "$fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("A4", "Código interno");
$objSpreadsheet->getActiveSheet()->SetCellValue("B4", "Colegio");

if ($usuario["tipo"]==3 || $usuario["tipo"]==10) {
	$objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Empresa");
}else{
	$objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Zona / Asesor");
}

$objSpreadsheet->getActiveSheet()->SetCellValue("D4", "Provincia");
$objSpreadsheet->getActiveSheet()->SetCellValue("E4", "Ciudad");
$objSpreadsheet->getActiveSheet()->SetCellValue("F4", "Barrio");
$objSpreadsheet->getActiveSheet()->SetCellValue("G4", "Dirección");
$objSpreadsheet->getActiveSheet()->SetCellValue("H4", "Teléfono");
$objSpreadsheet->getActiveSheet()->SetCellValue("I4", "Status");
$objSpreadsheet->getActiveSheet()->SetCellValue("J4", "Propuesta comercial");
$objSpreadsheet->getActiveSheet()->getStyle("A1:J1")->getFont()->getColor()->applyFromArray(
	array(
	'rgb' => '#251919'
	)
);

$objSpreadsheet->getActiveSheet()->getStyle('A4:J4')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF84'
        ]
    ]
]);

$sql = "SELECT c.id, c.codigo, UPPER(c.colegio) as colegio, c.barrio,c.ciudad, c.departamento, c.direccion,c.telefono, c.sub_zona, z.zona, c.responsable, sc.status FROM colegios c JOIN zonas z ON c.cod_zona=z.codigo LEFT JOIN colegios_status cs ON c.id=cs.id_colegio AND cs.id_periodo = '".$_POST["periodo"]."' LEFT JOIN status_cubrimiento sc ON sc.id=cs.id_status WHERE z.codigo='".$usuario["cod_zona"]."' GROUP BY c.id";

$req = $bdd->prepare($sql);
$req->execute();
$coles = $req->fetchAll();

// ── Pre-fetch para eliminar N+1 queries ──────────────────────────
$cole_ids = array_column($coles, 'id');
$dep_map = []; $adj_map = []; $sz_map = [];

$req_all_dep = $bdd->query("SELECT id, departamento FROM departamentos");
foreach ($req_all_dep->fetchAll(PDO::FETCH_ASSOC) as $row)
    $dep_map[$row['id']] = $row['departamento'];

if (!empty($cole_ids)) {
    $ph = implode(',', array_fill(0, count($cole_ids), '?'));
    $req_adj_all = $bdd->prepare("SELECT id_colegio FROM adjuntos WHERE id_colegio IN ($ph) AND id_periodo = ? GROUP BY id_colegio");
    $req_adj_all->execute(array_merge($cole_ids, [$_POST["periodo"]]));
    foreach ($req_adj_all->fetchAll(PDO::FETCH_ASSOC) as $row)
        $adj_map[$row['id_colegio']] = true;
}
$all_sz_ids = array_values(array_filter(array_unique(array_column($coles, 'sub_zona'))));
if (!empty($all_sz_ids)) {
    $ph_sz = implode(',', array_fill(0, count($all_sz_ids), '?'));
    $req_sz_all = $bdd->prepare("SELECT id, sub_zona FROM sub_zonas WHERE id IN ($ph_sz)");
    $req_sz_all->execute($all_sz_ids);
    foreach ($req_sz_all->fetchAll(PDO::FETCH_ASSOC) as $row)
        $sz_map[$row['id']] = $row['sub_zona'];
}
// ── Fin pre-fetch ─────────────────────────────────────────────────

$conta=5;
foreach($coles as $cole) {

	$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$cole[codigo]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$cole[colegio]");

	if ($usuario["tipo"]==3 || $usuario["tipo"]==10) {
		$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$empresa");
	}else{
		$sznombre = $sz_map[$cole["sub_zona"]] ?? '';
		$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$sznombre / $cole[responsable]");
	}

	$dep_nombre = $dep_map[$cole['departamento']] ?? '';
	$count_p    = isset($adj_map[$cole['id']]) ? 1 : 0;

	$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", $dep_nombre);
	$objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$cole[ciudad]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$cole[barrio]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$cole[direccion]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$cole[telefono]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$cole[status]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", $count_p < 1 ? "No" : "Si");

$conta++;
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

header('Content-Disposition: attachment; filename="Zonificación_'.$nombre_completo.'.xlsx"');


header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
$objWriter->save('php://output');
exit;
?>