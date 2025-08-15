<?php

require_once("aut.php");
require_once('../conexion/bdd.php');

// Parámetros enviados por DataTables
$draw = $_GET['draw'];
$start = intval($_GET['start']);
$length = intval($_GET['length']);
$searchValue = $_GET['search']['value'] ?? '';

$params = [];

$columns = ['id', 'codigo', 'dane', 'colegio', 'direccion', 'barrio', 'departamento', 'ciudad', 'telefono', 'cod_zona', 'responsable', 'sub_zona'];

$orderSQL = '';
if (isset($_GET['order'][0]['column'])) {
    $columnIndex = intval($_GET['order'][0]['column']);
    $sortDir = $_GET['order'][0]['dir'] === 'desc' ? 'DESC' : 'ASC';

    if (isset($columns[$columnIndex])) {
        $orderSQL = " ORDER BY " . $columns[$columnIndex] . " $sortDir";
    }
}

if ($_SESSION["tipo"]!=3 && $_SESSION["tipo"]!=6 && $_SESSION["tipo"]!=10 ) {

    
        $searchSQL = " WHERE (colegio LIKE :search OR dane LIKE :search) AND cod_zona !=0 AND id > 2";
        $params[':search'] = "%" . $searchValue . "%";
    

}else {

    if ($_SESSION["tipo"]==10){
       
            $searchSQL = " WHERE (colegio LIKE :search OR dane LIKE :search) AND (cod_zona='".$_SESSION['zona']."' OR zona_madre='".$_SESSION['zona']."')";
            $params[':search'] = "%" . $searchValue . "%";
        
    }else{
       
        $searchSQL = " WHERE (colegio LIKE :search OR dane LIKE :search) AND cod_zona='".$_SESSION['zona']."'";
        $params[':search'] = "%" . $searchValue . "%";
    

    }

}






// Total con filtro
$stmt = $bdd->prepare("SELECT COUNT(*) FROM colegios $searchSQL");
$stmt->execute($params);
$filtered = $stmt->fetchColumn();

// Datos paginados con filtro
$dataSQL = "SELECT id, codigo, dane, colegio, direccion, barrio,departamento,ciudad,telefono, cod_zona, responsable, sub_zona, id_calendario FROM colegios $searchSQL $orderSQL LIMIT :start, :length";
$stmt = $bdd->prepare($dataSQL);

// Agregar parámetros de límite y desplazamiento
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':length', $length, PDO::PARAM_INT);

// Agregar el parámetro de búsqueda si corresponde
if (!empty($searchSQL)) {
    $stmt->bindValue(':search', "%" . $searchValue . "%", PDO::PARAM_STR);
}

$stmt->execute();
$data = [];

$sql_periodo1="SELECT id FROM periodos ORDER BY id DESC";
$req_periodo1 = $bdd->prepare($sql_periodo1);
$req_periodo1->execute();
$periodo1 = $req_periodo1->fetch();
$periodo2=$periodo1["id"];
$cache_alumnos = [];
$cache_calculo_tasa = [];
$cache_precio_venta = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $colegio) {

    if ($_SESSION['tipo'] == 1 || $_SESSION["tipo"]==7 || $_SESSION["tipo"]==10 || $_SESSION["tipo"]==5) {

        $sql_zona="SELECT zona, CONCAT(nombres,' ',apellidos) as promotor, u.tipo FROM zonas z JOIN usuarios u ON z.codigo=u.cod_zona WHERE z.codigo='".$colegio['cod_zona']."'";
        $req_zona = $bdd->prepare($sql_zona);
        $req_zona->execute();
        $zona = $req_zona->fetch();

                                                  
        if ($zona['tipo']==3 || $zona['tipo']==1 || $zona['tipo']==10) {
                                                    
            list($empresa,$n_zona) = explode("/", $zona["zona"]);

            $colegio['empresa']=$empresa;
            $colegio['zona']="$n_zona";
            $colegio['responsable']=$zona['promotor'];
           
        }else{

            $sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$colegio["sub_zona"]."'";
            $req_sz = $bdd->prepare($sql_sz);
            $req_sz->execute();
            $sub_zona = $req_sz->fetch();

            $colegio['empresa']=$zona['zona'];
            $colegio['zona']=$sub_zona['sub_zona'];
            $colegio['responsable']=$colegio['responsable'];
    
        }
                                     

    }elseif ($_SESSION['tipo'] == 6) {
        
        $colegio['zona']="";
        $colegio['responsable']=$colegio['responsable'];                                              
    }

    $sql_dep="SELECT departamento FROM departamentos WHERE id='".$colegio['departamento']."' ";
    $req_dep = $bdd->prepare($sql_dep);
    $req_dep->execute();
    $dep = $req_dep->fetch();


    $sql_presup="SELECT p.tasa_compra, p.descuento, l.id as idlibro, l.id_grado, p.precio, p.cod_area FROM presupuestos p JOIN libros l ON l.id=p.id_libro WHERE p.id_colegio='".$colegio['id']."' AND p.id_periodo=7 AND p.pre_definido=1";
    $req_presup = $bdd->prepare($sql_presup);
    $req_presup->execute();
    $presups = $req_presup->fetchAll();

    foreach ($presups as $presup) {

        if ($presup["id_grado"] != 17 && $presup["cod_area"]=="") {
            $key = $colegio["id"] . "_" . $presup["id_grado"] . "_" . '7';

            if (!isset($cache_alumnos[$key])) {

                $sq_gp = "SELECT  SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio= ? AND id_grado= ? AND id_periodo= ? AND alumnos > 0";

                $req_gp = $bdd->prepare($sq_gp);
                $req_gp->execute([$colegio["id"], $presup["id_grado"], '7']);
                $gp = $req_gp->fetch(PDO::FETCH_ASSOC);
                $cache_alumnos[$key] = isset($gp['alumnos']) && $gp['alumnos'] !== null ? $gp['alumnos'] : 0;
            }
      
            $alumnos = $cache_alumnos[$key];
                      

        }else{

            $sql_go = "SELECT id_grado_otro FROM areas_objetivas WHERE id_colegio='".$colegio['id']."' AND id_libro_eureka='".$presup["idlibro"]."' AND id_periodo='7' AND codigo='".$presup["cod_area"]."'";
            
            $req_go = $bdd->prepare($sql_go);
            $req_go->execute();
            $grado_o = $req_go->fetch();

            $key = $colegio["id"] . "_" . $grado_o["id_grado_otro"] . "_" . '7';

            if (!isset($cache_alumnos[$key])) {
                $sq_gp = "SELECT  SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio= ? AND id_grado= ? AND id_periodo= ? AND alumnos > 0";

                $req_gp = $bdd->prepare($sq_gp);
                $req_gp->execute([$colegio["id"], $grado_o["id_grado_otro"], '7']);
                $gp = $req_gp->fetch(PDO::FETCH_ASSOC);
                $cache_alumnos[$key] = isset($gp['alumnos']) && $gp['alumnos'] !== null ? $gp['alumnos'] : 0;
            }

            $alumnos = $cache_alumnos[$key];
        }

        $key_calc = $alumnos . "_" . $presup["tasa_compra"];

        if (!isset($cache_calculo_tasa[$key_calc])) {

            $alumnos_tasa = floor($alumnos * $presup["tasa_compra"]);
            $cache_calculo_tasa[$key_calc] = $alumnos_tasa;
        }
        
        $alumnos_tasa = $cache_calculo_tasa[$key_calc];
        $precio_neto = $presup["precio"] - ($presup["precio"] * $presup["descuento"]);
        $venta_ppto[$colegio['id']][] = $precio_neto * $alumnos_tasa;

        
    }

    if (isset($venta_ppto[$colegio['id']])) {
        $venta_ppto[$colegio["id"]]=number_format($venta_ppto[$colegio['id']] = array_sum($venta_ppto[$colegio['id']]),0,",", ".");
    } else {
        $venta_ppto[$colegio['id']] = 0; // o null, dependiendo de qué valores quieras tener
    }
    

    $sql_sta="SELECT s.status FROM `colegios_status` cs JOIN status_cubrimiento s ON cs.id_status=s.id WHERE cs.id_colegio='".$colegio['id']."' AND cs.id_periodo=7";
    $req_sta = $bdd->prepare($sql_sta);
    $req_sta->execute();
    $sta = $req_sta->fetch();

    $sql_periodo="SELECT id, periodo FROM periodos WHERE id_calendario='".$colegio['id_calendario']."' ORDER BY id DESC";
    $req_periodo = $bdd->prepare($sql_periodo);
    $req_periodo->execute();
    $gp_periodo = $req_periodo->fetchAll();

    // Armas el select de forma segura
    $selectPeriodo = '<select id="periodo' . $colegio["id"] . '" name="periodo" style="width: 100px;">';
    foreach ($gp_periodo as $periodo) {
        $selectPeriodo .= '<option value="' . $periodo["id"] . '">' . htmlspecialchars($periodo["periodo"]) . '</option>';
    }
    $selectPeriodo .= '</select>';

    // Lo agregas como una nueva columna
    $colegio['periodo'] = $selectPeriodo;
    $colegio['departamento'] = $dep['departamento'];
    $colegio['acciones'] ="";
    $colegio['status'] =$sta['status'];
    $colegio['presupuesto'] =$venta_ppto[$colegio["id"]];
    /*$colegio['acciones'] = '
        <button class="btn btn-sm btn-info" data-id='.$colegio['id'].' data-codigo='.$colegio['codigo'].'>
            <i class="fa fa-pencil bigger-120"></i>
        </button>';*/

    if ($_SESSION['tipo'] == 1) {
         $colegio['acciones'].='<a class="btn btn-sm btn-danger eliminar" href="#" data-codigo='.$colegio['codigo'].'>
            <i class="fa fa-trash-o bigger-120"></i>
        </a>';
    }

    $colegio['colegio']='<a class="linkcole" id="'.$colegio["id"].'" href="colegio.php?codigo='.$colegio['codigo'].'&periodo='.$periodo2.'&tab=presupuesto" data-id='.$colegio['id'].' data-codigo='.$colegio['codigo'].'>'.$colegio['colegio'].'</a>';

    $data[] = $colegio;
}

// Respuesta JSON
echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => $filtered,
    "recordsFiltered" => $filtered,
    "data" => $data
]);
?>
