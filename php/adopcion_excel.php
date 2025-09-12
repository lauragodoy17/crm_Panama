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
$objSpreadsheet->getProperties()->setTitle("Presupuesto en excel");
$objSpreadsheet->createSheet(0);
$objSpreadsheet->setActiveSheetIndex(0);
$objSpreadsheet->getActiveSheet()->setTitle("Presupuesto en excel");
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

$estilo_borde = [
    'borders' => [
        'top' => ['style' => Border::BORDER_THIN],
        'right' => ['style' => Border::BORDER_THIN],
        'bottom' => ['style' => Border::BORDER_THIN],
        'left' => ['style' => Border::BORDER_THIN],
    ]

    
];

$estilo_derecha = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_RIGHT,
    ],
];





//poner imagen
$drawing = new Drawing();
$drawing->setName('test_img');
$drawing->setDescription('test_img');
$drawing->setPath('../vendors/images/logo_eureka.png'); // Ruta relativa o absoluta a la imagen
$drawing->setHeight(100); // Puedes ajustar el tamaño si deseas
$drawing->setCoordinates('A1'); // Posición en la hoja
$drawing->setWorksheet($objSpreadsheet->getActiveSheet());

	$sql_cole="SELECT colegio, cod_zona, sub_zona, responsable FROM colegios WHERE id='".$_GET["cole"]."'";

	$req_cole = $bdd->prepare($sql_cole);
	$req_cole->execute();
	$cole = $req_cole->fetch();

	$sql_zona="SELECT zona FROM zonas WHERE codigo='".$cole["cod_zona"]."'";

	$req_zona = $bdd->prepare($sql_zona);
	$req_zona->execute();
	$zona = $req_zona->fetch();


	$sql = "SELECT nombres, apellidos, tipo FROM usuarios WHERE cod_zona='".$cole["cod_zona"]."'";

	$req = $bdd->prepare($sql);
	$req->execute();
	$usuario = $req->fetch();

	$nombre_completo=$usuario["nombres"]." ".$usuario["apellidos"];

	$sql_rec = "SELECT  fecha, observaciones, id_canal FROM recursos WHERE id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["cole"]."'";
                           
	$req_rec = $bdd->prepare($sql_rec);
	$req_rec->execute();
	$recurso = $req_rec->fetch();


	

//~ Ingreo de datos en la hojda de excel

$objSpreadsheet->getActiveSheet()->mergeCells('D2:F2');
$objSpreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D2')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->SetCellValue("D2", "REPORTE DE ADOPCION");
$objSpreadsheet->getActiveSheet()->getStyle('G2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('G2')->applyFromArray($estilo_centrar);
if (!empty($recurso["fecha"])) {
	$objSpreadsheet->getActiveSheet()->SetCellValue("G2", "$recurso[fecha]");
}


$objSpreadsheet->getActiveSheet()->getStyle('B5')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('B5')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('A5')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('A6')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('A5')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('A6')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('E4')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('E4')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('E5')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('E5')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('E6')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('E6')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('A10:L10')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('A9:L9')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('A10:L10')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('F11:J11')->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('F11:J11')->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("C5", "          ");
$objSpreadsheet->getActiveSheet()->SetCellValue("E6", "          ");
$objSpreadsheet->getActiveSheet()->SetCellValue("F4", "                 ");
$objSpreadsheet->getActiveSheet()->SetCellValue("K4", "                 ");
$objSpreadsheet->getActiveSheet()->SetCellValue("L4", "                 ");


$sql_periodo="SELECT id, periodo FROM periodos ORDER BY id DESC";

$req_periodo = $bdd->prepare($sql_periodo);
$req_periodo->execute();
$gp_periodo = $req_periodo->fetch();

if ($usuario['tipo']!=6) {
	list($empresa,$n_zona) = explode("/", $zona["zona"]);
	$objSpreadsheet->getActiveSheet()->SetCellValue("D4", "Empresa:");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E4", "$empresa");
	$objSpreadsheet->getActiveSheet()->SetCellValue("D5", "Zona:");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E5", "$n_zona");
	$objSpreadsheet->getActiveSheet()->SetCellValue("D6", "Periodo:");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E6", "$gp_periodo[periodo]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("A6", "Responsable: $nombre_completo");
	//$objSpreadsheet->getActiveSheet()->SetCellValue("B6", "$nombre_completo");
}else{

	$sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$cole["sub_zona"]."'";
    $req_sz = $bdd->prepare($sql_sz);
    $req_sz->execute();
    $sub_zona = $req_sz->fetch();

    $objSpreadsheet->getActiveSheet()->SetCellValue("D4", "Empresa:");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E4", "$zona[zona]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("D5", "Zona:");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E5", "$sub_zona[sub_zona]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("D5", "Periodo:");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E5", "$gp_periodo[periodo]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("A6", "Responsable: $cole[responsable]");
	//$objSpreadsheet->getActiveSheet()->SetCellValue("B6", "$cole[responsable]");

}

//$objSpreadsheet->getActiveSheet()->mergeCells('B5:C5');
//$objSpreadsheet->getActiveSheet()->mergeCells('B6:C6');
$objSpreadsheet->getActiveSheet()->SetCellValue("A5", "$cole[colegio]");
//$objSpreadsheet->getActiveSheet()->SetCellValue("B5", "$cole[colegio]");

$objSpreadsheet->getActiveSheet()->mergeCells('G5:H5');
$objSpreadsheet->getActiveSheet()->mergeCells('G6:H6');
$objSpreadsheet->getActiveSheet()->mergeCells('G7:H7');
$objSpreadsheet->getActiveSheet()->mergeCells('G8:H8');




$sql_descuento =  "SELECT avg(descuento_d) as descuento_pactado FROM presupuestos p WHERE p.id_colegio='".$_GET["cole"]."' AND p.id_periodo='".$_GET["periodo"]."' AND p.definido='1' ";

$req_descuento = $bdd->prepare($sql_descuento);
$req_descuento->execute();
$descuento = $req_descuento->fetch();

if ($descuento["descuento_pactado"] == 0.0000) {
	
	$sql_descuento =  "SELECT avg(descuento) as descuento_pactado FROM presupuestos p WHERE p.id_colegio='".$_GET["cole"]."' AND p.id_periodo='".$_GET["periodo"]."' AND p.definido='1' ";

	$req_descuento = $bdd->prepare($sql_descuento);
	$req_descuento->execute();
	$descuento = $req_descuento->fetch();
}

$descuento_pactado= round($descuento["descuento_pactado"] * 100);


$objSpreadsheet->getActiveSheet()->mergeCells('A10:A11');
$objSpreadsheet->getActiveSheet()->mergeCells('B10:B11');
$objSpreadsheet->getActiveSheet()->mergeCells('C10:C11');
$objSpreadsheet->getActiveSheet()->mergeCells('D10:D11');
$objSpreadsheet->getActiveSheet()->mergeCells('E10:E11');
$objSpreadsheet->getActiveSheet()->mergeCells('F10:J10');
$objSpreadsheet->getActiveSheet()->mergeCells('K10:K11');
$objSpreadsheet->getActiveSheet()->mergeCells('L10:L11');

$objSpreadsheet->getActiveSheet()->getStyle('E10')->applyFromArray($estilo_centrar);

$objSpreadsheet->getActiveSheet()->getStyle('A10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('B10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('C10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('D10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('E10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('F10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('G10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('H10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('I10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('J10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('K10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('L10')->applyFromArray($estilo_borde);
//$objSpreadsheet->getActiveSheet()->getStyle('N10')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('F11')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('G11')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('H11')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('I11')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('J11')->applyFromArray($estilo_borde);

$objSpreadsheet->getActiveSheet()->SetCellValue("A10", "TITULO");
$objSpreadsheet->getActiveSheet()->SetCellValue("B10", "GRADO");
$objSpreadsheet->getActiveSheet()->SetCellValue("C10", "ALUMNOS");
$objSpreadsheet->getActiveSheet()->SetCellValue("D10", "% COMP");
$objSpreadsheet->getActiveSheet()->SetCellValue("F10", "VENTA");
$objSpreadsheet->getActiveSheet()->SetCellValue("E10", "COM ACT.");

$objSpreadsheet->getActiveSheet()->SetCellValue("K10", "PRECIO VENTA PADRE");
$objSpreadsheet->getActiveSheet()->SetCellValue("L10", "VENTA REAL");
//$objSpreadsheet->getActiveSheet()->SetCellValue("N10", "DIFEREN \n CIA");


$sql_periodo="SELECT id FROM periodos ORDER BY id DESC";

$req_periodo = $bdd->prepare($sql_periodo);
$req_periodo->execute();
$gp_periodo = $req_periodo->fetch();



	$sql = "SELECT l.libro, l.id_grado, g.grado, m.materia, p.precio,p.tasa_compra,p.descuento, p.tasa_compra_d, p.descuento_d, p.precio_venta_final, p.id_libro, p.cod_area, p.uni_vr FROM libros l JOIN presupuestos p ON l.id=p.id_libro JOIN grados g ON l.id_grado=g.id JOIN materias m ON m.id=l.id_materia WHERE p.id_periodo='".$_GET["periodo"]."' AND p.definido='1' AND p.id_colegio='".$_GET["cole"]."'";
	$req = $bdd->prepare($sql);
	$req->execute();
	$adopciones = $req->fetchAll();

	if (empty($adopciones) ) {
 		echo "<script>alert('Aun no hay adopciones en este colegio');window.location='../reporte_adopcion.php'</script>";
 	}else {

$conta=12;
 
foreach($adopciones as $adopcion) {

	
	$sql_go = "SELECT a.id_grado_otro FROM areas_objetivas a WHERE id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["cole"]."' AND codigo='".$adopcion["cod_area"]."'";
	$req_go = $bdd->prepare($sql_go);
	$req_go->execute();
	$go = $req_go->fetch();

	if ($go["id_grado_otro"] == 0) {

		$sq_gp = "SELECT  SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET["cole"]."' AND id_grado='".$adopcion["id_grado"]."' AND id_periodo='".$_GET["periodo"]."'";

	}else {

		$sq_gp = "SELECT  SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET["cole"]."' AND id_grado='".$go["id_grado_otro"]."' AND id_periodo='".$_GET["periodo"]."'";
	}

                           
    $req_gp = $bdd->prepare($sq_gp);
    $req_gp->execute();
    $gp = $req_gp->fetch();

   

    if ($adopcion["tasa_compra_d"] !=0.00) {
    	 $tasa_compra=$adopcion["tasa_compra_d"] * 100;
    	  $comp_activos=$gp["alumnos"] * $adopcion["tasa_compra_d"];
    }else{
    	 $tasa_compra=$adopcion["tasa_compra"] * 100;
    	  $comp_activos=$gp["alumnos"] * $adopcion["tasa_compra"];
    }
   
   
    $comp_activos=floor($comp_activos);
    if ($adopcion["descuento_d"] !=0.0000) {
    	$descuento=$adopcion["descuento_d"] * 100;
    	$precio_fact=$adopcion["precio"] - ($adopcion["precio"] * $adopcion["descuento_d"]);
    }else{
    	 $descuento=$adopcion["descuento"] * 100;
    	 $precio_fact=$adopcion["precio"] - ($adopcion["precio"] * $adopcion["descuento"]);
    }
   
    $venta_bruta=$adopcion["precio"] * $comp_activos;
  
    $venta_estimada=$precio_fact * $comp_activos;
    $venta_real= $precio_fact * $adopcion["uni_vr"];
    //$diferencia=$venta_real - $venta_estimada;

    $objSpreadsheet->getActiveSheet()->getStyle('A'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('C'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('D'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('E'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('F'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('G'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('H'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('I'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('J'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('K'.$conta)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('L'.$conta)->applyFromArray($estilo_borde);
	//$objSpreadsheet->getActiveSheet()->getStyle('N'.$conta)->applyFromArray($estilo_borde);

	$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "$adopcion[libro]");

	if ($go["id_grado_otro"] == 0) {

		$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$adopcion[grado]");
		if ($adopcion["id_grado"] < 4) {
			$p_pre[]=$tasa_compra;
		}elseif ($adopcion["id_grado"] > 3 && $adopcion["id_grado"] < 9) {
			$p_pri[]=$tasa_compra;
		}else {
			$p_sec[]=$tasa_compra;
		}

	}else{

		$sql_go1 = "SELECT grado FROM grados WHERE id='".$go["id_grado_otro"]."'";
		$req_go1 = $bdd->prepare($sql_go1);
		$req_go1->execute();
		$go1 = $req_go1->fetch();

		$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta", "$go1[grado]");
		if ($go["id_grado_otro"] < 4) {
			$p_pre[]=$tasa_compra;
		}elseif ($go["id_grado_otro"] > 3 && $go["id_grado_otro"] < 9) {
			$p_pri[]=$tasa_compra;
		}else {
			$p_sec[]=$tasa_compra;
		}
	}


	$objSpreadsheet->getActiveSheet()->getStyle("F$conta")
        ->getNumberFormat()
        ->setFormatCode(
        '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

    $objSpreadsheet->getActiveSheet()->getStyle("H$conta")
        ->getNumberFormat()
        ->setFormatCode(
        '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

    $objSpreadsheet->getActiveSheet()->getStyle("I$conta")
        ->getNumberFormat()
        ->setFormatCode(
        '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

    $objSpreadsheet->getActiveSheet()->getStyle("J$conta")
        ->getNumberFormat()
        ->setFormatCode(
        '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

    $objSpreadsheet->getActiveSheet()->getStyle("L$conta")
        ->getNumberFormat()
        ->setFormatCode(
        '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

    $objSpreadsheet->getActiveSheet()->getStyle("k$conta")
        ->getNumberFormat()
        ->setFormatCode(
        '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
    );

	
	$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$gp[alumnos]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta", "$tasa_compra");
	$objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$comp_activos");
	$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta", "$adopcion[precio]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("G$conta", "$descuento");
	$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$venta_bruta");
	$objSpreadsheet->getActiveSheet()->SetCellValue("I$conta", "$precio_fact");
	$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "$venta_estimada");
	$objSpreadsheet->getActiveSheet()->SetCellValue("K$conta", "$adopcion[precio_venta_final]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "$venta_real");
	//$objSpreadsheet->getActiveSheet()->SetCellValue("N$conta", "$$diferencia1");*/

	$objSpreadsheet->getActiveSheet()->getStyle('F'.$conta)->applyFromArray($estilo_derecha);
	$objSpreadsheet->getActiveSheet()->getStyle('H'.$conta)->applyFromArray($estilo_derecha);
	$objSpreadsheet->getActiveSheet()->getStyle('I'.$conta)->applyFromArray($estilo_derecha);
	$objSpreadsheet->getActiveSheet()->getStyle('J'.$conta)->applyFromArray($estilo_derecha);
	$objSpreadsheet->getActiveSheet()->getStyle('L'.$conta)->applyFromArray($estilo_derecha);


	$conta++;
	$t_alumnos[]=$gp["alumnos"];
	$t_compradores[]=$comp_activos;
	$t_venta_bruta[]=$venta_bruta;
	$t_venta_estimada[]=$venta_estimada;
	$t_venta_real[]=$venta_real;
	
}
if (isset($p_pre)) {
	$p_pre=array_sum($p_pre)/count($p_pre);
}else {
	$p_pre=0;
}
if (isset($p_pri)) {
	$p_pri=array_sum($p_pri)/count($p_pri);
}else{
	$p_pri=0;
}
if (isset($p_sec)) {
	$p_sec=array_sum($p_sec)/count($p_sec);
}else{
	$p_sec=0;
}

  
// Obtener la hoja activa
$sheet = $objSpreadsheet->getActiveSheet();

// Definir el rango de datos (por ejemplo, A1 hasta la última columna y fila con datos)
$highestColumn = $sheet->getHighestColumn();
$highestRow = $conta;

// Aplicar filtros a todas las columnas (A1 hasta la última columna y fila)
//$sheet->setAutoFilter('A10:' . $highestColumn . $highestRow);

// Extraer los datos para ordenarlos
$dataArray = [];

// Recorrer todas las filas desde la segunda fila (asumiendo que la primera fila son los encabezados)
for ($row = 12; $row <= $highestRow; $row++) {
    $rowData = [];
    for ($col = 'A'; $col <= $highestColumn; $col++) {
        $rowData[$col] = $sheet->getCell($col . $row)->getValue();
    }
    $dataArray[] = $rowData;
}

// Ordenar los datos por una columna (por ejemplo, la columna A)
usort($dataArray, function ($a, $b) {
    return strnatcmp((string) ($a['B'] ?? ''), (string) ($b['B'] ?? ''));
});

// Escribir los datos ordenados de vuelta en la hoja, desde la fila 2
$rowIndex = 11;
foreach ($dataArray as $rowData) {
    $colIndex = 'A';
    foreach ($rowData as $value) {
        $sheet->setCellValue($colIndex . $rowIndex, $value);
        $colIndex++;
    }
    $rowIndex++;
}


$objSpreadsheet->getActiveSheet()->SetCellValue("F11", "PVP");
$objSpreadsheet->getActiveSheet()->SetCellValue("G11", "DESC.%");
$objSpreadsheet->getActiveSheet()->SetCellValue("H11", "V. BRUTA");
$objSpreadsheet->getActiveSheet()->SetCellValue("I11", "P. FACT");
$objSpreadsheet->getActiveSheet()->SetCellValue("J11", "VENTA ESTIMADA");

$sql_conse =  "SELECT conse FROM presupuestos p WHERE p.id_colegio='".$_GET["cole"]."' AND p.id_periodo='".$_GET["periodo"]."' AND p.definido='1' ";

$req_conse = $bdd->prepare($sql_conse);
$req_conse->execute();
$conse = $req_conse->fetch();

$objSpreadsheet->getActiveSheet()->getStyle('C2')->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->SetCellValue("C2", "# $conse[conse]");
$objSpreadsheet->getActiveSheet()->SetCellValue("G5", "Potencial compra preescolar %");
$objSpreadsheet->getActiveSheet()->getStyle('I5')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->SetCellValue("I5", "$p_pre");
$objSpreadsheet->getActiveSheet()->SetCellValue("G6", "Potencial compra primaria %");
$objSpreadsheet->getActiveSheet()->getStyle('I6')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->SetCellValue("I6", "$p_pri");
$objSpreadsheet->getActiveSheet()->SetCellValue("G7", "Potencial venta bachillerato %");
$objSpreadsheet->getActiveSheet()->getStyle('I7')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->SetCellValue("I7", "$p_sec");
$objSpreadsheet->getActiveSheet()->SetCellValue("G8", "Promedio descuento %");
$objSpreadsheet->getActiveSheet()->getStyle('I8')->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->SetCellValue("I8", "$descuento_pactado");

$t_alumnos=array_sum($t_alumnos);
$t_compradores=array_sum($t_compradores);
$t_venta_bruta=array_sum($t_venta_bruta);
$t_venta_estimada=array_sum($t_venta_estimada);
$t_venta_real=array_sum($t_venta_real);


$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('C'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('D'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('E'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('F'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('G'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('H'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('I'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('J'.$conta)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('K'.$conta)->applyFromArray($estilo_borde);
//$objSpreadsheet->getActiveSheet()->getStyle('L'.$conta)->applyFromArray($estilo_borde);






$cumplimiento=round($t_venta_real / $t_venta_estimada,2);

$objSpreadsheet->getActiveSheet()->getStyle("H$conta")
    ->getNumberFormat()
    ->setFormatCode(
    '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
);

$objSpreadsheet->getActiveSheet()->getStyle("J$conta")
    ->getNumberFormat()
    ->setFormatCode(
    '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
);


$objSpreadsheet->getActiveSheet()->getStyle("L$conta")
    ->getNumberFormat()
    ->setFormatCode(
    '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
);



$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta", "TOTAL VENTA");
$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta", "$t_alumnos");
$objSpreadsheet->getActiveSheet()->SetCellValue("E$conta", "$t_compradores");
$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta", "$t_venta_bruta");
$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta", "$t_venta_estimada");
$objSpreadsheet->getActiveSheet()->SetCellValue("L$conta", "$t_venta_real");

$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta.':'.'L'.$conta)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('H'.$conta)->applyFromArray($estilo_derecha);
$objSpreadsheet->getActiveSheet()->getStyle('J'.$conta)->applyFromArray($estilo_derecha);
$objSpreadsheet->getActiveSheet()->getStyle('L'.$conta)->applyFromArray($estilo_derecha);
//$objSpreadsheet->getActiveSheet()->SetCellValue("M$conta", "$cumplimiento % de cumplimiento");


$conta2=$conta+3;

$objSpreadsheet->getActiveSheet()->SetCellValue("A".$conta2, "Atenciones entregadas");
$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta2)->applyFromArray($estilo_negrita);

$conta3=$conta2+2;

$objSpreadsheet->getActiveSheet()->SetCellValue("B".$conta3, "#");
$objSpreadsheet->getActiveSheet()->SetCellValue("C".$conta3, "Fecha");
$objSpreadsheet->getActiveSheet()->SetCellValue("D".$conta3, "Valor");

$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta3)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('C'.$conta3)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('D'.$conta3)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta3)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('C'.$conta3)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle('D'.$conta3)->applyFromArray($estilo_borde);


$sql = "SELECT s.id,s.fecha, s.conse, SUM(r.valor_e) as valore, r.fecha_e, r.legaliza FROM solicitudes_recursos s JOIN recursos_solicitados r ON r.id_solicitud=s.id JOIN tipos_recursos t ON t.id=r.tipo WHERE s.id_periodo='".$_GET["periodo"]."' AND s.id_colegio='".$_GET["cole"]."' AND s.estado='4' GROUP BY r.id_solicitud ORDER BY s.id DESC";


$req = $bdd->prepare($sql);
$req->execute();
$atenciones = $req->fetchAll();

$conta4=$conta3+1;

foreach ($atenciones as $atencion) {

	list($fecha,$hora)=explode(" ", $atencion["fecha"]);

	$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta4", "$atencion[conse]");
	$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta4", "$fecha");
	$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta4", "$atencion[valore]");

	$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta4)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('C'.$conta4)->applyFromArray($estilo_borde);
	$objSpreadsheet->getActiveSheet()->getStyle('D'.$conta4)->applyFromArray($estilo_borde);

	$t_valore[]=$atencion["valore"];

	$objSpreadsheet->getActiveSheet()->getStyle("D$conta4")
    ->getNumberFormat()
    ->setFormatCode(
    '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
);

	
	$conta4++;
}

$conta4++;

if (!empty($t_valore) ) {

	$t_valore=array_sum($t_valore);
}

$objSpreadsheet->getActiveSheet()->SetCellValue("C$conta4", "Total");
if (!empty($t_valore) ) {
	$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta4", "$t_valore");
}else{
	$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta4", "");
}

$objSpreadsheet->getActiveSheet()->getStyle('D'.$conta4)->applyFromArray($estilo_borde);
$objSpreadsheet->getActiveSheet()->getStyle("D$conta4")
    ->getNumberFormat()
    ->setFormatCode(
    '_("$"* #,##0_);_("$"* \(#,##0\);_("$"* "-"??_);_(@_)'
);
$objSpreadsheet->getActiveSheet()->getStyle('C'.$conta4.':'.'D'.$conta4)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta2.':'.'D'.$conta4)->applyFromArray($estilo_borde);

$conta4=$conta4+2;

if (!empty($recurso["id_canal"])) {
	$sql_canal = "SELECT  canal_venta FROM canales_venta WHERE id='".$recurso["id_canal"]."'";
                           
	$req_canal = $bdd->prepare($sql_canal);
	$req_canal->execute();
	$canal = $req_canal->fetch();
}



if (!empty($recurso["fecha"]) ) {
	$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta4", "Fecha: ".$recurso["fecha"]);
}else{
	$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta4", "");
}

$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta4)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta4)->applyFromArray($estilo_centrar);

$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta4", "Canal de venta:");

if (!empty($canal["canal_venta"]) ) {
	$objSpreadsheet->getActiveSheet()->SetCellValue("G$conta4", "$canal[canal_venta]");
}else{
	$objSpreadsheet->getActiveSheet()->SetCellValue("G$conta4", "");
}


$objSpreadsheet->getActiveSheet()->getStyle('F'.$conta4)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('F'.$conta4)->applyFromArray($estilo_centrar);

$conta4=$conta4+2;
$conta41=$conta4+1;
$objSpreadsheet->getActiveSheet()->mergeCells('A'.$conta4.':L'.$conta41);
if (!empty($canal["observaciones"]) ) {
	$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta4", "Observaciones: ".$recurso["observaciones"]);
}else{
	$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta4", "");
}


$conta4=$conta4+4;
$conta5=$conta4+1;

$objSpreadsheet->getActiveSheet()->mergeCells('B'.$conta4.':D'.$conta4);
$objSpreadsheet->getActiveSheet()->mergeCells('F'.$conta4.':H'.$conta4);
$objSpreadsheet->getActiveSheet()->mergeCells('J'.$conta4.':L'.$conta4);
$objSpreadsheet->getActiveSheet()->mergeCells('B'.$conta5.':D'.$conta5);
$objSpreadsheet->getActiveSheet()->mergeCells('F'.$conta5.':H'.$conta5);
$objSpreadsheet->getActiveSheet()->mergeCells('J'.$conta5.':L'.$conta5);

$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta4.':J'.$conta4)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta4.':J'.$conta4)->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta5.':J'.$conta5)->applyFromArray($estilo_centrar);
$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta5.':J'.$conta5)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta4", "__________________________________________");
$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta5", "FIRMA ASESOR");
$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta4", "__________________________________________");
$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta5", "FIRMA GERENTE P Y V");
$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta4", "__________________________________________");
$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta5", "FIRMA GERENTE EUREKA");



/*$sql_rec = "SELECT  * FROM recursos WHERE id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["cole"]."'";
                           
$req_rec = $bdd->prepare($sql_rec);
$req_rec->execute();
$recurso = $req_rec->fetch();

$sql_canal = "SELECT  canal_venta FROM canales_venta WHERE id='".$recurso["id_canal"]."'";
                           
$req_canal = $bdd->prepare($sql_canal);
$req_canal->execute();
$canal = $req_canal->fetch();

$conta1=$conta + 2;
$conta2=$conta1 + 1;

$conta3=$conta2 + 2;
$conta4=$conta3 + 1;

$conta5=$conta4+ 2;

$conta6=$conta5 + 2;
$conta7=$conta6 + 1;

$conta8=$conta7 + 4;*/

/*$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta1.':H'.$conta1)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta1", "RECURSO ENTREGADO");
$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta2", "$recurso[recurso]");
$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta1", "REINTEGRO");
$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta2", "$recurso[reintegro]");
$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta1", "CANAL DE VENTA");
$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta2", "$canal[canal_venta]");

$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta3.':H'.$conta3)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta3", "VALOR RECURSO");
$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta4", "$$recurso[valor_recurso]");
$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta3", "VALOR REINTEGRO");
$objSpreadsheet->getActiveSheet()->SetCellValue("D$conta4", "$$recurso[valor_reintegro]");
$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta3", "DESCRIPCION");
$objSpreadsheet->getActiveSheet()->SetCellValue("H$conta4", "$recurso[descripcion_canal]");

$objSpreadsheet->getActiveSheet()->getStyle('A'.$conta5)->applyFromArray($estilo_negrita);

$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta5", "FECHA: $recurso[fecha]");*/

/*$objSpreadsheet->getActiveSheet()->mergeCells('A'.$conta6.':L'.$conta7);
$objSpreadsheet->getActiveSheet()->SetCellValue("A$conta6", "OBSERVACIONES: $recurso[observaciones]");*/

/*$objSpreadsheet->getActiveSheet()->mergeCells('B'.$conta8.':D'.$conta8);
$objSpreadsheet->getActiveSheet()->mergeCells('F'.$conta8.':H'.$conta8);
$objSpreadsheet->getActiveSheet()->mergeCells('J'.$conta8.':L'.$conta8);

$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta8.':J'.$conta8)->applyFromArray($estilo_negrita);
$objSpreadsheet->getActiveSheet()->getStyle('B'.$conta8.':J'.$conta8)->applyFromArray($estilo_centrar);*/
/*$conta8=$conta + 2;
$conta9=$cont8 + 1;
$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta8", "__________________________________________");
$objSpreadsheet->getActiveSheet()->SetCellValue("B$conta9", "FIRMA ASESOR");
$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta8", "__________________________________________");
$objSpreadsheet->getActiveSheet()->SetCellValue("F$conta9", "FIRMA GERENTE P Y V");
$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta8", "__________________________________________");
$objSpreadsheet->getActiveSheet()->SetCellValue("J$conta9", "FIRMA GERENTE EUREKA");*/

$objSpreadsheet->getActiveSheet()->getStyle('A1:N'.$conta5)->applyFromArray($estilo_fuente);
$objSpreadsheet->getActiveSheet()->getColumnDimensionByColumn(1)->setWidth(30);



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
$objSpreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth('10');
$objSpreadsheet->getActiveSheet()->getRowDimension(10)->setRowHeight(20);
foreach (range('A', 'Z') as $columnID) {
  $objSpreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);  
}
foreach (excelColumnRange('AA', 'ZZ') as $columnID) {
  $objSpreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);  
}

$objWriter = new Xlsx($objSpreadsheet); //Escribir archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header('Content-Disposition: attachment; filename="Adopción_'.$cole["colegio"].'.xlsx"');


header('Cache-Control: max-age=0');
header('Expires: 0');
header('Pragma: public');
$objWriter->save('php://output');
}
?>