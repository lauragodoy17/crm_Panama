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



//~ Ingreo de datos en la hojda de excel



list($empresa,$n_zona) = explode("/", $zona["zona"]);
$objSpreadsheet->getActiveSheet()->SetCellValue("A1", "Zona");
$objSpreadsheet->getActiveSheet()->SetCellValue("A2", "$zona[zona]");
$objSpreadsheet->getActiveSheet()->SetCellValue("B1", "Responsable");
$objSpreadsheet->getActiveSheet()->SetCellValue("B2", "$nombre_completo");


$objSpreadsheet->getActiveSheet()->SetCellValue("C1", "Fecha Reporte");
$objSpreadsheet->getActiveSheet()->SetCellValue("C2", "$fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("A4", "Dane");
$objSpreadsheet->getActiveSheet()->SetCellValue("B4", "Colegio");
$objSpreadsheet->getActiveSheet()->SetCellValue("C4", "Calendario");


$objSpreadsheet->getActiveSheet()->SetCellValue("D4", "Empresa");
  
$objSpreadsheet->getActiveSheet()->SetCellValue("E4", "Departamento");
$objSpreadsheet->getActiveSheet()->SetCellValue("F4", "Ciudad");
$objSpreadsheet->getActiveSheet()->SetCellValue("G4", "Barrio");
$objSpreadsheet->getActiveSheet()->SetCellValue("H4", "Paralelos prescolar");
$objSpreadsheet->getActiveSheet()->SetCellValue("I4", "Paralelos primaria");
$objSpreadsheet->getActiveSheet()->SetCellValue("J4", "Paralelos bachillerato");
$objSpreadsheet->getActiveSheet()->SetCellValue("K4", "Paralelos global");
$objSpreadsheet->getActiveSheet()->SetCellValue("L4", "Alumnos prescolar");
$objSpreadsheet->getActiveSheet()->SetCellValue("M4", "Alumnos primaria");
$objSpreadsheet->getActiveSheet()->SetCellValue("N4", "Alumnos bachillerato");
$objSpreadsheet->getActiveSheet()->SetCellValue("O4", "Alumnos global");
$objSpreadsheet->getActiveSheet()->SetCellValue("P4", "Status");
$objSpreadsheet->getActiveSheet()->SetCellValue("Q4", "Segmento");
$objSpreadsheet->getActiveSheet()->SetCellValue("R4", "Estado del cliente");
$objSpreadsheet->getActiveSheet()->SetCellValue("S4", "Fecha de ultimo contacto");
$objSpreadsheet->getActiveSheet()->getStyle("A1:S1")->getFont()->getColor()->applyFromArray(
  array(
  'rgb' => '#251919'
  )
);

$objSpreadsheet->getActiveSheet()->getStyle('A4:S4')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF84'
        ]
    ]
]);

$sql_periodo="SELECT id, id_calendario FROM periodos WHERE id='".$_POST["periodo"]."'";
$req_periodo = $bdd->prepare($sql_periodo);
$req_periodo->execute();
$gp_periodo = $req_periodo->fetch();


if ($_SESSION["tipo"] == 3) {


  if ($_POST["periodo"] < 7) {

  $sql="SELECT c.id, c.dane as codigo, UPPER(c.colegio) as colegio, c.barrio,c.ciudad, c.departamento, c.direccion,c.telefono, c.sub_zona, z.zona, c.responsable, ca.calendario, CONCAT (u.nombres, ' ',u.apellidos) as promotor, sc.status, s.segmento, d.departamento FROM colegios c JOIN zonas z ON c.cod_zona=z.codigo JOIN calendarios ca ON ca.id=c.id_calendario JOIN usuarios u ON u.cod_zona=z.codigo LEFT JOIN colegios_status cs ON c.id=cs.id_colegio AND cs.id_periodo = '".$_POST["periodo"]."' AND cs.id_status != 4 LEFT JOIN segmentos s ON c.id_segmento=s.id LEFT JOIN status_cubrimiento sc ON sc.id=cs.id_status JOIN departamentos d ON d.id=c.departamento WHERE z.codigo='".$usuario["cod_zona"]."' AND c.id_calendario='".$gp_periodo['id_calendario']."' GROUP BY c.id ORDER BY u.id";

  }else{
    $sql="SELECT c.id, c.dane as codigo, UPPER(c.colegio) as colegio, c.barrio,c.ciudad, c.departamento, c.direccion,c.telefono, c.sub_zona, z.zona, c.responsable, ca.calendario, CONCAT (u.nombres, ' ',u.apellidos) as promotor, sc.status, s.segmento, d.departamento FROM colegios c JOIN zonas z ON c.cod_zona=z.codigo JOIN calendarios ca ON ca.id=c.id_calendario JOIN usuarios u ON u.cod_zona=z.codigo LEFT JOIN colegios_status cs ON c.id=cs.id_colegio  AND cs.id_status != 4 LEFT JOIN segmentos s ON c.id_segmento=s.id LEFT JOIN status_cubrimiento sc ON sc.id=cs.id_status JOIN departamentos d ON d.id=c.departamento WHERE z.codigo='".$usuario["cod_zona"]."' AND c.id_calendario='".$gp_periodo['id_calendario']."' AND cs.id_periodo = '".$_POST["periodo"]."' GROUP BY c.id ORDER BY u.id";
  }

}else{

  if ($_POST["periodo"] < 7) {

  $sql="SELECT c.id, c.dane as codigo, UPPER(c.colegio) as colegio, c.barrio,c.ciudad, c.departamento, c.direccion,c.telefono, c.sub_zona, z.zona, c.responsable, ca.calendario, CONCAT (u.nombres, ' ',u.apellidos) as promotor, sc.status, s.segmento, d.departamento FROM colegios c JOIN zonas z ON c.cod_zona=z.codigo JOIN calendarios ca ON ca.id=c.id_calendario JOIN usuarios u ON u.cod_zona=z.codigo LEFT JOIN colegios_status cs ON c.id=cs.id_colegio AND cs.id_periodo = '".$_POST["periodo"]."' AND cs.id_status != 4 LEFT JOIN segmentos s ON c.id_segmento=s.id LEFT JOIN status_cubrimiento sc ON sc.id=cs.id_status JOIN departamentos d ON d.id=c.departamento WHERE (z.codigo='".$usuario["cod_zona"]."' OR c.zona_madre='".$usuario["cod_zona"]."') AND c.id_calendario='".$gp_periodo['id_calendario']."' GROUP BY c.id ORDER BY u.id";

  }else{
    $sql="SELECT c.id, c.dane as codigo, UPPER(c.colegio) as colegio, c.barrio,c.ciudad, c.departamento, c.direccion,c.telefono, c.sub_zona, z.zona, c.responsable, ca.calendario, CONCAT (u.nombres, ' ',u.apellidos) as promotor, sc.status, s.segmento, d.departamento FROM colegios c JOIN zonas z ON c.cod_zona=z.codigo JOIN calendarios ca ON ca.id=c.id_calendario JOIN usuarios u ON u.cod_zona=z.codigo LEFT JOIN colegios_status cs ON c.id=cs.id_colegio  AND cs.id_status != 4 LEFT JOIN segmentos s ON c.id_segmento=s.id LEFT JOIN status_cubrimiento sc ON sc.id=cs.id_status JOIN departamentos d ON d.id=c.departamento WHERE (z.codigo='".$usuario["cod_zona"]."' OR c.zona_madre='".$usuario["cod_zona"]."') AND c.id_calendario='".$gp_periodo['id_calendario']."' AND cs.id_periodo = '".$_POST["periodo"]."' GROUP BY c.id ORDER BY u.id";
  }

}



  
$req = $bdd->prepare($sql);
$req->execute();
$coles = $req->fetchAll();

$conta=5;
foreach($coles as $cole) {

  
  $sql_pre = "SELECT COUNT(alumnos) as paralelos,SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$cole['id']."' AND id_grado BETWEEN 1 AND 3 AND id_periodo='".$gp_periodo["id"]."' AND alumnos!='0'";
  $req_pre = $bdd->prepare($sql_pre);
  $req_pre->execute();
  $gp_pre = $req_pre->fetch();

  $sql_pri = "SELECT COUNT(alumnos) as paralelos,SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$cole['id']."' AND id_grado BETWEEN 4 AND 8  AND id_periodo='".$gp_periodo["id"]."' AND alumnos!='0'";
  $req_pri = $bdd->prepare($sql_pri);
  $req_pri->execute();
  $gp_pri = $req_pri->fetch();
 
  $sql_bach = "SELECT COUNT(alumnos) as paralelos,SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$cole['id']."' AND id_grado BETWEEN 9 AND 14  AND id_periodo='".$gp_periodo["id"]."' AND alumnos!='0'";
  $req_bach = $bdd->prepare($sql_bach);
  $req_bach->execute();
  $gp_bach = $req_bach->fetch();
  

  $paralelos_global= $gp_pre["paralelos"] + $gp_pri["paralelos"] + $gp_bach["paralelos"];
  $alumnos_global= $gp_pre["alumnos"] + $gp_pri["alumnos"] + $gp_bach["alumnos"];

  $sql_uc = "SELECT MAX(v.fecha) as ultimo_contacto FROM plan_trabajo p JOIN visitas v ON p.id=v.id_plan_trabajo WHERE p.id_colegio='".$cole["id"]."' AND p.resultado=1";
  $req_uc = $bdd->prepare($sql_uc);
  $req_uc->execute();
  $uc = $req_uc->fetch();

  $objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$cole[codigo]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$cole[colegio]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$cole[calendario]");
    
  $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "$empresa");
    
  $objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$cole[departamento]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$cole[ciudad]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$cole[barrio]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$gp_pre[paralelos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$gp_pri[paralelos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "$gp_bach[paralelos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("K$conta", "$paralelos_global");
  $objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "$gp_pre[alumnos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "$gp_pri[alumnos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "$gp_bach[alumnos]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("O$conta", "$alumnos_global");
  $objSpreadsheet->getActiveSheet()->SetCellValue("P$conta", "$cole[status]");
  $objSpreadsheet->getActiveSheet()->SetCellValue("Q$conta", "$cole[segmento]");

  if ($_POST["periodo"] < 7) {
    $objSpreadsheet->getActiveSheet()->SetCellValue("R$conta", "");
  }else{
    $sql_est = "SELECT e.estado FROM estados_cliente e JOIN colegios_estados_clientes ce ON e.id=ce.id_estado_cliente WHERE ce.id_colegio='".$cole["id"]."' AND ce.id_periodo='".$_POST["periodo"]."'";
    $req_est = $bdd->prepare($sql_est);
    $req_est->execute();
    $est = $req_est->fetch();

    if (empty($est["estado"])) {
      $objSpreadsheet->getActiveSheet()->SetCellValue("R$conta", "");
    }else{
      $objSpreadsheet->getActiveSheet()->SetCellValue("R$conta", "$est[estado]");
    }
    
  }
  

  if (empty($uc["ultimo_contacto"])) {
    $objSpreadsheet->getActiveSheet()->SetCellValue("S$conta", "");
  }else{
    $objSpreadsheet->getActiveSheet()->SetCellValue("S$conta", "$uc[ultimo_contacto]");
  }


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