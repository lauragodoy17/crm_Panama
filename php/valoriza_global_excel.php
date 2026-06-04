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
ini_set('memory_limit', '200000M');
$objSpreadsheet = new Spreadsheet();
$objSpreadsheet->getProperties()->setCreator("Ing. Alejandro Rangel");
$objSpreadsheet->getProperties()->setTitle("valorización global");
$objSpreadsheet->createSheet(0);
$objSpreadsheet->setActiveSheetIndex(0);
$objSpreadsheet->getActiveSheet()->setTitle("valorización global");
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


$objSpreadsheet->getActiveSheet()->mergeCells('E2:G2');
$objSpreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('H2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('H2')->applyFromArray($estilo_centrar);

$objSpreadsheet->getActiveSheet()->SetCellValue("E2", "REPORTE de valorización global");

$sql_periodo="SELECT periodo FROM periodos WHERE id='".$_POST["periodo"]."'";

$req_periodo = $bdd->prepare($sql_periodo);
$req_periodo->execute();
$gp_periodo = $req_periodo->fetch();
$fecha=date("Y-m-d");

$objSpreadsheet->getActiveSheet()->SetCellValue("H2", "Periodo $gp_periodo[periodo]");

$objSpreadsheet->getActiveSheet()->getStyle('C4')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D4')->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("E4", "Fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("F4", "$fecha");

$objSpreadsheet->getActiveSheet()->SetCellValue("A6", "#");
$objSpreadsheet->getActiveSheet()->SetCellValue("B6", "Empresa");
$objSpreadsheet->getActiveSheet()->SetCellValue("C6", "Zona");
$objSpreadsheet->getActiveSheet()->SetCellValue("D6", "Asesor");
$objSpreadsheet->getActiveSheet()->SetCellValue("E6", "Dane");
$objSpreadsheet->getActiveSheet()->SetCellValue("F6", "Colegio");
$objSpreadsheet->getActiveSheet()->SetCellValue("G6", "Departamento");
$objSpreadsheet->getActiveSheet()->SetCellValue("H6", "Ciudad");
$objSpreadsheet->getActiveSheet()->SetCellValue("I6", "Población total");
$objSpreadsheet->getActiveSheet()->SetCellValue("J6", "Cantidad Presupuestada");
$objSpreadsheet->getActiveSheet()->SetCellValue("K6", "Valor Presupuestado");
$objSpreadsheet->getActiveSheet()->SetCellValue("L6", "Compradores activos");
$objSpreadsheet->getActiveSheet()->SetCellValue("M6", "Valor adopciones");
$objSpreadsheet->getActiveSheet()->SetCellValue("N6", "Venta real");
$objSpreadsheet->getActiveSheet()->SetCellValue("O6", "Valor atenciones entregadas");


$objSpreadsheet->getActiveSheet()->getStyle('A6:O6')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '00FF84'
        ]
    ]
]);

if ($_SESSION['tipo']==1 || $_SESSION['tipo']==2 || $_SESSION['tipo']==7) {



    
    if ($_POST['promotor']!=0) {


     $sql = "SELECT c.id, c.dane, p.sub_zona, p.conse, p.year, c.responsable, c.departamento,c.ciudad, c.colegio, c.direccion, c.barrio,c.telefono, CONCAT(u.nombres, ' ',u.apellidos) as promotor, u.tipo as tipouser, u.id as uid, z.zona FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio JOIN zonas z ON p.cod_zona=z.codigo JOIN usuarios u on u.id=p.id_usuario WHERE p.id_usuario='".$_POST['promotor']."' AND (p.pre_definido=1 OR p.definido=1) AND p.id_periodo='".$_POST['periodo']."' GROUP BY c.id ORDER BY p.conse DESC";
                                  

 

    }else{

         $sql = "SELECT c.id, c.dane, p.sub_zona, p.conse, p.year, c.responsable, c.departamento,c.ciudad, c.colegio, c.direccion, c.barrio,c.telefono, CONCAT(u.nombres, ' ',u.apellidos) as promotor, u.tipo as tipouser, u.id as uid, z.zona FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio JOIN zonas z ON p.cod_zona=z.codigo JOIN usuarios u on u.id=p.id_usuario WHERE (p.pre_definido=1 OR p.definido=1) AND p.id_periodo='".$_POST['periodo']."' GROUP BY c.id ORDER BY p.conse DESC";
     
    }

}elseif($_SESSION['tipo']==3){

    $sql = "SELECT c.id, c.dane, p.sub_zona, p.conse, p.year, c.responsable, c.departamento,c.ciudad, c.colegio, c.direccion, c.barrio,c.telefono, CONCAT(u.nombres, ' ',u.apellidos) as promotor, u.tipo as tipouser, u.id as uid, z.zona FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio JOIN zonas z ON p.cod_zona=z.codigo JOIN usuarios u on u.id=p.id_usuario WHERE (p.pre_definido=1 OR p.definido=1) AND p.id_periodo='".$_POST['periodo']."' AND p.id_usuario='".$_SESSION['id']."' GROUP BY c.id ORDER BY p.conse DESC";

  
}else{

     $sql = "SELECT c.id, c.dane, p.sub_zona, p.conse, p.year, c.responsable, c.departamento,c.ciudad, c.colegio, c.direccion, c.barrio,c.telefono, CONCAT(u.nombres, ' ',u.apellidos) as promotor, u.tipo as tipouser, u.id as uid, z.zona FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio JOIN zonas z ON p.cod_zona=z.codigo JOIN usuarios u on u.id=p.id_usuario WHERE (p.pre_definido=1 OR p.definido=1) AND p.id_periodo='".$_POST['periodo']."' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY c.id ORDER BY p.conse DESC";
}





/*$sql = "SELECT e.estado, s.id,s.fecha, s.solicitante, s.cargo, s.fecha_entrega FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado WHERE s.id_colegio='".$colegio["id"]."' AND s.id_periodo='".$gp_periodo['id']."' ORDER BY s.id DESC";*/

$req = $bdd->prepare($sql);
$req->execute();
$colegios = $req->fetchAll();


$conta=7;
//$conse=1;
foreach ($colegios as $colegio) {


    /*$sql = "UPDATE presupuestos SET conse='".$conse."' WHERE id_colegio='".$colegio["id"]."' AND id_periodo='".$_POST['periodo']."'";


    $req = $bdd->prepare($sql);
    $req->execute();*/
  

    $sql = "SELECT p.tasa_compra,p.tasa_compra_d,p.descuento ,p.descuento_d ,p.precio, p.pre_definido, p.definido, p.cod_area, p.uni_vr, l.id as idlibro, l.id_grado FROM presupuestos p JOIN libros l ON p.id_libro=l.id WHERE p.id_colegio='".$colegio["id"]."' AND (p.pre_definido=1 OR p.definido=1) AND p.id_periodo='".$_POST['periodo']."' AND p.id_usuario='".$colegio["uid"]."'";


    $req = $bdd->prepare($sql);
    $req->execute();
    $adopciones = $req->fetchAll();


    $sql = "SELECT venta_real FROM recursos WHERE id_colegio='".$colegio["id"]."' AND id_periodo='".$_POST['periodo']."'";


    $req = $bdd->prepare($sql);
    $req->execute();
    $v_real = $req->fetch();


    foreach ($adopciones as $adopcion) {
       
        if ($adopcion["id_grado"] != 17  && $adopcion["cod_area"]=="") {

            $sq_gp = "SELECT  SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$colegio["id"]."' AND id_grado='".$adopcion["id_grado"]."' AND id_periodo='".$_POST['periodo']."' AND alumnos > 0";

        }else{

       
            $sql_go = "SELECT id_grado_otro FROM areas_objetivas WHERE id_colegio='".$colegio['id']."' AND id_libro_eureka='".$adopcion["idlibro"]."' AND id_periodo='".$_POST['periodo']."' AND codigo='".$adopcion["cod_area"]."'";
                
            $req_go = $bdd->prepare($sql_go);
            $req_go->execute();
            $grado_o = $req_go->fetch();

            $id_grado_o = $grado_o['id_grado_otro'] ?? 0;

            $sq_gp = "SELECT  SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$colegio["id"]."' AND id_grado='".$id_grado_o."' AND id_periodo='".$_POST['periodo']."' AND alumnos > 0";
        }

        $req_gp = $bdd->prepare($sq_gp);
        $req_gp->execute();
        $gp = $req_gp->fetch();

        if ($adopcion["pre_definido"] ==1) {

            $alumnos_tasa= floor($gp["alumnos"] * $adopcion["tasa_compra"]);
            $precio_neto=$adopcion["precio"] - ($adopcion["precio"] * $adopcion["descuento"]);

            $valor_presup[$colegio["id"]][]=$precio_neto * $alumnos_tasa;

            $cant_presup[$colegio["id"]][]=$alumnos_tasa;

           
        }

        if ($adopcion["definido"] !=0) {

            if ($adopcion["tasa_compra_d"] == 0.00) {
                $alumnos_tasa_d= floor($gp["alumnos"] * $adopcion["tasa_compra"]);
                $precio_neto_d=$adopcion["precio"] - ($adopcion["precio"] * $adopcion["descuento"]);
           
            }else{
                $alumnos_tasa_d= floor($gp["alumnos"] * $adopcion["tasa_compra_d"]);
                $precio_neto_d=$adopcion["precio"] - ($adopcion["precio"] * $adopcion["descuento_d"]);

            }

            if (!is_array($v_real) || !isset($v_real['venta_real']) || $v_real['venta_real'] < 1) {

                $venta_real[$colegio["id"]][]= $precio_neto_d * $adopcion["uni_vr"];
            }else{

            }

            $valor_adopcion[$colegio["id"]][]=$precio_neto_d * $alumnos_tasa_d;

            $castigo[$colegio["id"]][]=$alumnos_tasa_d;

        }

        
    
        
        

        $alms[$colegio["id"]][]=$gp["alumnos"];

    }

    $t_cant_presup[$colegio["id"]]=array_sum($cant_presup[$colegio["id"]]);
    $t_valor_presup[$colegio["id"]]=array_sum($valor_presup[$colegio["id"]]);

  
    $t_castigo[$colegio["id"]]       = array_sum($castigo[$colegio["id"]] ?? []);
    $t_valor_adopcion[$colegio["id"]] = array_sum($valor_adopcion[$colegio["id"]] ?? []);

    
    $t_alms[$colegio["id"]]=array_sum($alms[$colegio["id"]]);
    
   

    


    $sql = "SELECT SUM(r.legaliza) as total FROM solicitudes_recursos s JOIN recursos_solicitados r ON s.id=r.id_solicitud WHERE s.id_colegio='".$colegio["id"]."' AND s.id_periodo='".$_POST['periodo']."' AND s.estado='4'";

    $req = $bdd->prepare($sql);
    $req->execute();
    $total = $req->fetch();

    $conse=$colegio["year"]."-".$colegio["conse"];
    $objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$conse");
    if ($colegio["tipouser"]!=6) {
        list($empresa,$n_zona) = explode("/", $colegio["zona"]);
        $objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$empresa");
        $objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$n_zona");
        $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "$colegio[promotor]");
    }else{

        $sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$colegio["sub_zona"]."'";
        $req_sz = $bdd->prepare($sql_sz);
        $req_sz->execute();
        $sub_zona = $req_sz->fetch();

        $objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$colegio[promotor]");
        $objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$sub_zona[sub_zona]");
        $objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "$colegio[responsable]");
    }
    
    $objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$colegio[dane]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$colegio[colegio]");

    $sql_dep="SELECT departamento FROM departamentos WHERE id='".$colegio['departamento']."' ";
    $req_dep = $bdd->prepare($sql_dep);
    $req_dep->execute();
    $dep = $req_dep->fetch();

    $objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$dep[departamento]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$colegio[ciudad]");
    $objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "".$t_alms[$colegio["id"]]."");
    $objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "".$t_cant_presup[$colegio["id"]]."");
    $objSpreadsheet->getActiveSheet()->SetCellValue("K$conta", "".$t_valor_presup[$colegio["id"]]."");
    $objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "".$t_castigo[$colegio["id"]]."");
    if ($t_valor_adopcion[$colegio["id"]]==0) {
        if ($colegio["conse"] > 0) {
            $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "Anulada");
        }else{
            $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "".$t_valor_adopcion[$colegio["id"]]."");
        }
    }else{
        $objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "".$t_valor_adopcion[$colegio["id"]]."");
    }
    

    if (is_array($v_real) && isset($v_real['venta_real']) && $v_real['venta_real'] > 0) {
        $t_venta_real= $v_real["venta_real"];
        $objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "$t_venta_real");
    }else{
        if (empty($venta_real[$colegio["id"]])) {
            $t_venta_real[$colegio["id"]]=0;
        }else{
            $t_venta_real[$colegio["id"]]=array_sum($venta_real[$colegio["id"]]);
        }
        
        $objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "".$t_venta_real[$colegio["id"]]."");
    }

    
   
    $objSpreadsheet->getActiveSheet()->SetCellValue("O$conta", "$total[total]");
   

    $conta++;
  


}   


$objSpreadsheet->getActiveSheet()->getStyle("K7:K$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

$objSpreadsheet->getActiveSheet()->getStyle("L7:L$conta")
          ->getNumberFormat()
          ->setFormatCode(
          '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
        );

$objSpreadsheet->getActiveSheet()->getStyle("M7:M$conta")
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

header('Content-Disposition: attachment; filename="Valorización_global.xlsx"');


header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
$objWriter->save('php://output');