<?php
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
require_once("../php/aut.php");
require_once('../conexion/bdd.php');

// Parámetros enviados por DataTables
$draw = $_GET['draw'];
$start = intval($_GET['start']);
$length = intval($_GET['length']);
$searchValue = $_GET['search']['value'] ?? '';

$params = [];

$columns = ['o.id', 'usuario', 'fecha', 'n_doc', 'valor', 'guia', 'cliente', 'estado'];

$orderSQL = '';
if (isset($_GET['order'][0]['column'])) {
    $columnIndex = intval($_GET['order'][0]['column']);
    $sortDir = $_GET['order'][0]['dir'] === 'desc' ? 'ASC' : 'DESC';

    if (isset($columns[$columnIndex])) {
        $orderSQL = " ORDER BY " . $columns[$columnIndex] . " $sortDir";
    }
}

if ($_GET['tp']==1) {
   $searchSQL = " WHERE c.cliente LIKE :search OR o.id LIKE :search";
}elseif ($_GET['tp']==2) {
    $searchSQL = " WHERE (c.cliente LIKE :search OR o.id LIKE :search) AND o.estado=1";
}elseif ($_GET['tp']==3) {
    $searchSQL = " WHERE (c.cliente LIKE :search OR o.id LIKE :search) AND o.estado=2";
}elseif ($_GET['tp']==4) {
    $searchSQL = " WHERE (c.cliente LIKE :search OR o.id LIKE :search) AND o.estado=4";
}

$params[':search'] = "%" . $searchValue . "%";
    

// Total con filtro
$stmt = $bdd->prepare("SELECT COUNT(*) FROM ordenes_pedidos o JOIN tipo_doc t ON o.tipo_doc=t.id JOIN clientes c ON c.id=o.cliente JOIN usuarios u ON u.id=o.usuario JOIN estados_op e ON e.id=o.estado $searchSQL");
$stmt->execute($params);
$filtered = $stmt->fetchColumn();

// Datos paginados con filtro
$dataSQL = "SELECT o.id as opid, o.usuario, o.fecha, o.n_doc, o.solicitante, o.valor, o.guia, o.fecha_entrega, o.archivo,o.fecha_entrega, o.fecha_at, o.usuario_at, o.usuario_anu, o.fecha_anu, t.tipo, c.*, CONCAT(u.nombres,' ',u.apellidos) AS usuario, e.estado FROM ordenes_pedidos o JOIN tipo_doc t ON o.tipo_doc=t.id JOIN clientes c ON c.id=o.cliente JOIN usuarios u ON u.id=o.usuario JOIN estados_op e ON e.id=o.estado $searchSQL $orderSQL LIMIT :start, :length";

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



foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $op) {
    if ($_GET['tp']==3) {
        $sql = "SELECT CONCAT(nombres,' ',apellidos) AS usr_aten FROM usuarios WHERE id='".$op["usuario_at"]."' ";
        $req = $bdd->prepare($sql);
        $req->execute();
        $aten= $req->fetch();
        $op["usuario_at"]=$aten["usr_aten"];
        $op["id"]="<a href='op_pendiente.php?op=".$op["opid"]."'>".$op["opid"]."</a>";
    }elseif($_GET['tp']==4){
        $sql = "SELECT CONCAT(nombres,' ',apellidos) AS usr_anu FROM usuarios WHERE id='".$op["usuario_anu"]."' ";
        $req = $bdd->prepare($sql);
        $req->execute();
        $anu= $req->fetch();
        $op["usuario_anu"]=$anu["usr_anu"];
        $op["id"]=$op["opid"];
    }else{
        $op["id"]="<a href='op_pendiente.php?op=".$op["opid"]."'>".$op["opid"]."</a>";
    }

    $op["acciones"]="<a href='formato_op.php?op=".$op["opid"]."' target='_blank' class='btn btn-info btn-sm'><i class='bi bi-printer'></i></a>";
    $data[] = $op;
}

// Respuesta JSON
echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => $filtered,
    "recordsFiltered" => $filtered,
    "data" => $data
]);
?>
