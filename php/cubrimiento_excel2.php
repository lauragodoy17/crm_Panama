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
$objSpreadsheet->getProperties()->setTitle("Reporte de cubrimiento");
$objSpreadsheet->createSheet(0);
$objSpreadsheet->setActiveSheetIndex(0);
$objSpreadsheet->getActiveSheet()->setTitle("Reporte de cubrimiento");
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

$sql_periodo="SELECT id, periodo FROM periodos WHERE id='".$_POST["periodo"]."'";
$req_periodo = $bdd->prepare($sql_periodo);
$req_periodo->execute();
$gp_periodo = $req_periodo->fetch();

//~ Ingreso de datos en la hoja de excel

if ($usuario["tipo"]==3 || $usuario["tipo"]==10) {
  $zona_partes = explode("/", $zona["zona"]);
  $empresa = trim($zona_partes[0]);
  $objSpreadsheet->getActiveSheet()->SetCellValue("A1", "Zona");
  $objSpreadsheet->getActiveSheet()->SetCellValue("A2", "$zona[zona]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("B1", "Asesor");
  $objSpreadsheet->getActiveSheet()->SetCellValue("B2", "$nombre_completo");
}else{
  $objSpreadsheet->getActiveSheet()->SetCellValue("A1", "Empresa");
  $objSpreadsheet->getActiveSheet()->SetCellValue("A2", "$zona[zona]");
}


$objSpreadsheet->getActiveSheet()->SetCellValue("C1", "Fecha Reporte");
$objSpreadsheet->getActiveSheet()->SetCellValue("C2", "$fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("D1", "Periodo");
$objSpreadsheet->getActiveSheet()->SetCellValue("D2", "$gp_periodo[periodo]");

$objSpreadsheet->getActiveSheet()->SetCellValue("A4", "Código interno");
$objSpreadsheet->getActiveSheet()->SetCellValue("B4", "Colegio");

if ($usuario["tipo"]==3 || $usuario["tipo"]==10) {
  $objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Empresa");
}else{
  $objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Zona / Responsable");
}

$objSpreadsheet->getActiveSheet()->SetCellValue("D4", "Departamento");
$objSpreadsheet->getActiveSheet()->SetCellValue("E4", "Ciudad");
$objSpreadsheet->getActiveSheet()->SetCellValue("F4", "Ubicación");
$objSpreadsheet->getActiveSheet()->SetCellValue("G4", "Teléfono");
$objSpreadsheet->getActiveSheet()->SetCellValue("H4", "Paralelos preescolar");
$objSpreadsheet->getActiveSheet()->SetCellValue("I4", "Paralelos primaria");
$objSpreadsheet->getActiveSheet()->SetCellValue("J4", "Paralelos bachillerato");
$objSpreadsheet->getActiveSheet()->SetCellValue("K4", "Paralelos global");
$objSpreadsheet->getActiveSheet()->SetCellValue("L4", "Alumnos preescolar");
$objSpreadsheet->getActiveSheet()->SetCellValue("M4", "Alumnos primaria");
$objSpreadsheet->getActiveSheet()->SetCellValue("N4", "Alumnos bachillerato");
$objSpreadsheet->getActiveSheet()->SetCellValue("O4", "Alumnos global");
$objSpreadsheet->getActiveSheet()->SetCellValue("P4", "Status");
$objSpreadsheet->getActiveSheet()->SetCellValue("Q4", "Propuesta comercial");
$objSpreadsheet->getActiveSheet()->SetCellValue("R4", "Segmento");
$objSpreadsheet->getActiveSheet()->SetCellValue("S4", "Estado del cliente");
$objSpreadsheet->getActiveSheet()->SetCellValue("T4", "Fecha de último contacto");
$objSpreadsheet->getActiveSheet()->getStyle("A1:T1")->getFont()->getColor()->applyFromArray(
  array(
  'rgb' => '#251919'
  )
);

$objSpreadsheet->getActiveSheet()->getStyle('A4:T4')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF84'
        ]
    ]
]);

$sql="SELECT c.id, c.codigo, UPPER(c.colegio) as colegio, c.ciudad, c.direccion, c.telefono, c.sub_zona, c.responsable, d.departamento, s.segmento FROM colegios c JOIN zonas z ON c.cod_zona=z.codigo JOIN departamentos d ON d.id=c.departamento LEFT JOIN segmentos s ON c.id_segmento=s.id WHERE z.codigo='".$usuario["cod_zona"]."' ORDER BY c.codigo";

$req = $bdd->prepare($sql);
$req->execute();
$coles = $req->fetchAll();

// ── Pre-fetch para eliminar N+1 queries ──────────────────────────
$cole_ids = array_column($coles, 'id');
$adj_map = []; $uc_map = []; $est_map = [];

if (!empty($cole_ids)) {
    $ph = implode(',', array_fill(0, count($cole_ids), '?'));

    $req_adj_all = $bdd->prepare("SELECT id_colegio FROM adjuntos WHERE id_colegio IN ($ph) AND id_periodo = ? GROUP BY id_colegio");
    $req_adj_all->execute(array_merge($cole_ids, [$_POST["periodo"]]));
    foreach ($req_adj_all->fetchAll(PDO::FETCH_ASSOC) as $row)
        $adj_map[$row['id_colegio']] = true;

    $req_uc_all = $bdd->prepare("SELECT p.id_colegio, MAX(v.fecha) as ultimo_contacto FROM plan_trabajo p JOIN visitas v ON p.id = v.id_plan_trabajo WHERE p.id_colegio IN ($ph) AND p.resultado = 1 GROUP BY p.id_colegio");
    $req_uc_all->execute($cole_ids);
    foreach ($req_uc_all->fetchAll(PDO::FETCH_ASSOC) as $row)
        $uc_map[$row['id_colegio']] = $row['ultimo_contacto'];

    $req_est_all = $bdd->prepare("SELECT ce.id_colegio, e.estado FROM estados_cliente e JOIN colegios_estados_clientes ce ON e.id = ce.id_estado_cliente WHERE ce.id_colegio IN ($ph) AND ce.id_periodo = ?");
    $req_est_all->execute(array_merge($cole_ids, [$_POST["periodo"]]));
    foreach ($req_est_all->fetchAll(PDO::FETCH_ASSOC) as $row)
        $est_map[$row['id_colegio']] = $row['estado'];
}
// ── Fin pre-fetch ─────────────────────────────────────────────────

$conta=5;
foreach($coles as $cole) {

  // Panamá: preescolar = grados 1-3, primaria = 4-9, bachillerato = 10-14 y 18 (ver ajax/tab_poblacion.php)
  $sql_pre = "SELECT COUNT(alumnos) as paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$cole['id']."' AND id_periodo='".$gp_periodo["id"]."' AND alumnos!='0' AND id_grado BETWEEN 1 AND 3";
  $req_pre = $bdd->prepare($sql_pre);
  $req_pre->execute();
  $gp_pre = $req_pre->fetch();

  $sql_pri = "SELECT COUNT(alumnos) as paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$cole['id']."' AND id_periodo='".$gp_periodo["id"]."' AND alumnos!='0' AND id_grado BETWEEN 4 AND 9";
  $req_pri = $bdd->prepare($sql_pri);
  $req_pri->execute();
  $gp_pri = $req_pri->fetch();

  $sql_bach = "SELECT COUNT(alumnos) as paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$cole['id']."' AND id_periodo='".$gp_periodo["id"]."' AND alumnos!='0' AND (id_grado BETWEEN 10 AND 14 OR id_grado=18)";
  $req_bach = $bdd->prepare($sql_bach);
  $req_bach->execute();
  $gp_bach = $req_bach->fetch();

  $paralelos_global = $gp_pre["paralelos"] + $gp_pri["paralelos"] + $gp_bach["paralelos"];
  $alumnos_global = $gp_pre["alumnos"] + $gp_pri["alumnos"] + $gp_bach["alumnos"];

  // Status: prioridad por FIELD, si no hay en el periodo actual se toma el último status conocido, si no "Por definir"
  $sql_st = "SELECT status FROM colegios_status cs JOIN status_cubrimiento s ON cs.id_status=s.id WHERE cs.id_colegio='".$cole["id"]."' AND cs.id_periodo='".$gp_periodo["id"]."' AND s.id != 4 ORDER BY FIELD(cs.id_status,'6','5','1','2','3','4')";
  $req_st = $bdd->prepare($sql_st);
  $req_st->execute();
  $status = $req_st->fetch();

  $status2 = null;
  if (empty($status)) {
    $sql_st2 = "SELECT status FROM colegios_status cs JOIN status_cubrimiento s ON cs.id_status=s.id WHERE cs.id_colegio='".$cole["id"]."' AND s.id != 4 ORDER BY cs.id_periodo DESC";
    $req_st2 = $bdd->prepare($sql_st2);
    $req_st2->execute();
    $status2 = $req_st2->fetch();
  }

  $objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$cole[codigo]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$cole[colegio]");

  if ($usuario["tipo"]==3 || $usuario["tipo"]==10) {
    $objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$empresa");
  }else{
    $sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$cole["sub_zona"]."'";
    $req_sz = $bdd->prepare($sql_sz);
    $req_sz->execute();
    $sub_zona = $req_sz->fetch();
    $objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$sub_zona[sub_zona] / $cole[responsable]");
  }

  $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "$cole[departamento]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$cole[ciudad]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$cole[direccion]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$cole[telefono]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$gp_pre[paralelos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$gp_pri[paralelos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "$gp_bach[paralelos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("K$conta", "$paralelos_global");
  $objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "$gp_pre[alumnos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "$gp_pri[alumnos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "$gp_bach[alumnos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("O$conta", "$alumnos_global");

  if (!empty($status)) {
    $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "$status[status]");
  }elseif(!empty($status2)){
    $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "$status2[status]");
  }else{
    $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "Por definir");
  }

  $count_p         = isset($adj_map[$cole['id']]) ? 1 : 0;
  $ultimo_contacto = $uc_map[$cole['id']] ?? '';
  $estado_cli      = $est_map[$cole['id']] ?? '';

  $objSpreadsheet->getActiveSheet()->SetCellValue("Q$conta", $count_p < 1 ? "No" : "Si");
  $objSpreadsheet->getActiveSheet()->SetCellValue("R$conta", "$cole[segmento]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("S$conta", $estado_cli);
  $objSpreadsheet->getActiveSheet()->SetCellValue("T$conta", $ultimo_contacto);


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
header('Content-Disposition: attachment; filename="Cubrimiento_'.$nombre_completo.'.xlsx"');

header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
$objWriter->save('php://output');
exit;
?>
