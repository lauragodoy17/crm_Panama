<?php
ini_set('display_errors', 0);
error_reporting(0);
require_once("aut.php");
require_once('../conexion/bdd.php');

header('Content-Type: application/json; charset=utf-8');

try {

$draw        = isset($_GET['draw'])   ? intval($_GET['draw'])   : 1;
$start       = isset($_GET['start'])  ? intval($_GET['start'])  : 0;
$length      = isset($_GET['length']) ? intval($_GET['length']) : 10;
$searchValue = $_GET['search']['value'] ?? '';

// Columnas ordenables (índice = columna en DataTables según rol)
$sortable = [
    'c.colegio', 'z.zona', 'sz.sub_zona', 'c.responsable',
    'd.departamento', 'c.ciudad', 'c.direccion', 'c.periodo', 'c.acciones'
];
$orderSQL = ' ORDER BY c.colegio ASC';
if (isset($_GET['order'][0]['column'])) {
    $col = intval($_GET['order'][0]['column']);
    $dir = ($_GET['order'][0]['dir'] ?? 'asc') === 'desc' ? 'DESC' : 'ASC';
    if (isset($sortable[$col])) {
        $orderSQL = ' ORDER BY ' . $sortable[$col] . ' ' . $dir;
    }
}

// JOIN base (siempre presente)
$joinSQL = "
    FROM colegios c
    LEFT JOIN departamentos d  ON d.id       = c.departamento
    LEFT JOIN zonas         z  ON z.codigo   = c.cod_zona
    LEFT JOIN sub_zonas     sz ON sz.id      = c.sub_zona
    LEFT JOIN (
        SELECT cod_zona, CONCAT(nombres,' ',apellidos) AS promotor
        FROM usuarios
        WHERE tipo IN (1,3)
        GROUP BY cod_zona
    ) u ON u.cod_zona = c.cod_zona
";

// WHERE base según rol (sin búsqueda, para recordsTotal)
$baseParams = [];
if ($_SESSION['zona'] == '5656' || ($_SESSION["tipo"] != 3 && $_SESSION["tipo"] != 6 && $_SESSION["tipo"] != 10)) {
    $baseSQL = " WHERE c.cod_zona != '' AND c.cod_zona != '0' AND c.id > 0";
} elseif ($_SESSION["tipo"] == 10) {
    $baseSQL = " WHERE (c.cod_zona = :zona_base OR c.zona_madre = :zona_base)";
    $baseParams[':zona_base'] = $_SESSION['zona'];
} else {
    $baseSQL = " WHERE c.cod_zona = :zona_base";
    $baseParams[':zona_base'] = $_SESSION['zona'];
}

// WHERE con búsqueda y filtros adicionales
$searchSQL = $baseSQL;
$params    = $baseParams;

if (!empty($searchValue)) {
    $searchSQL .= " AND (c.colegio LIKE :search OR c.codigo LIKE :search)";
    $params[':search'] = '%' . $searchValue . '%';
}

$zona_filter   = isset($_GET['zona_filter'])   ? trim($_GET['zona_filter'])               : '';
$depto_filter  = isset($_GET['depto_filter'])  ? intval($_GET['depto_filter'])            : 0;
$ciudad_filter = isset($_GET['ciudad_filter']) ? trim(strip_tags($_GET['ciudad_filter'])) : '';
$resp_filter   = isset($_GET['resp_filter'])   ? trim(strip_tags($_GET['resp_filter']))   : '';
$dir_filter    = isset($_GET['dir_filter'])    ? trim(strip_tags($_GET['dir_filter']))    : '';

if (!empty($zona_filter)) {
    $searchSQL .= " AND c.sub_zona = :zona_filter";
    $params[':zona_filter'] = $zona_filter;
}
if ($depto_filter > 0) {
    $searchSQL .= " AND c.departamento = :depto_filter";
    $params[':depto_filter'] = $depto_filter;
}
if (!empty($ciudad_filter)) {
    $searchSQL .= " AND c.ciudad LIKE :ciudad_filter";
    $params[':ciudad_filter'] = '%' . $ciudad_filter . '%';
}
if (!empty($resp_filter) && ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 2)) {
    $searchSQL .= " AND c.cod_zona IN (
        SELECT z2.codigo FROM zonas z2
        JOIN usuarios u2 ON z2.codigo = u2.cod_zona
        WHERE CONCAT(u2.nombres,' ',u2.apellidos) = :resp_filter
    )";
    $params[':resp_filter'] = $resp_filter;
}
if (!empty($dir_filter)) {
    $searchSQL .= " AND c.direccion LIKE :dir_filter";
    $params[':dir_filter'] = '%' . $dir_filter . '%';
}

// Total sin filtros (recordsTotal)
$stmtTotal = $bdd->prepare("SELECT COUNT(*) $joinSQL $baseSQL");
$stmtTotal->execute($baseParams);
$total = (int) $stmtTotal->fetchColumn();

// Total con filtros (recordsFiltered)
$stmtFiltered = $bdd->prepare("SELECT COUNT(*) $joinSQL $searchSQL");
$stmtFiltered->execute($params);
$filtered = (int) $stmtFiltered->fetchColumn();

// Periodos
$gp_periodo    = $bdd->query("SELECT id, periodo FROM periodos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$primerPeriodo = !empty($gp_periodo) ? $gp_periodo[0]['id'] : 0;

// Datos paginados con todos los JOINs ya incluidos
$selectSQL = "SELECT c.id, c.codigo, c.dane, c.colegio, c.direccion,
                     d.departamento  AS depto_nombre,
                     c.ciudad, c.telefono, c.cod_zona, c.sub_zona,
                     z.zona          AS zona_full,
                     sz.sub_zona     AS subzona_nombre,
                     u.promotor      AS promotor_nombre,
                     c.responsable   AS responsable_raw";

$stmt = $bdd->prepare("$selectSQL $joinSQL $searchSQL $orderSQL LIMIT :lstart, :llength");
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':lstart',   $start,  PDO::PARAM_INT);
$stmt->bindValue(':llength',  $length, PDO::PARAM_INT);
$stmt->execute();

$data = [];

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $colegio) {

    // Empresa y zona (zonas.zona puede venir como "EMPRESA/ZONA" o solo nombre)
    if ($_SESSION['tipo'] == 1 || $_SESSION["tipo"] == 7 || $_SESSION["tipo"] == 10 || $_SESSION["tipo"] == 5 || $_SESSION['zona'] == '5656') {
        $zona_full = $colegio['zona_full'] ?? '';
        if (strpos($zona_full, '/') !== false) {
            [$empresa, $zona_name] = array_map('trim', explode('/', $zona_full, 2));
        } else {
            $empresa   = $zona_full;
            $zona_name = $colegio['subzona_nombre'] ?? '';
        }
        $colegio['empresa']     = $empresa;
        $colegio['zona']        = $colegio['subzona_nombre'] ?? '';
        $colegio['responsable'] = $colegio['promotor_nombre'] ?: ($colegio['responsable_raw'] ?? '');
    } elseif ($_SESSION['tipo'] == 6) {
        $colegio['zona']        = $colegio['subzona_nombre'] ?? '';
        $colegio['responsable'] = $colegio['promotor_nombre'] ?: ($colegio['responsable_raw'] ?? '');
    }

    $colegio['departamento'] = $colegio['depto_nombre'] ?? '';

    // Select de periodos
    $selectPeriodo = '<select id="periodo' . $colegio['id'] . '" name="periodo" style="width:100px;">';
    foreach ($gp_periodo as $p) {
        $selectPeriodo .= '<option value="' . $p['id'] . '">' . htmlspecialchars($p['periodo']) . '</option>';
    }
    $selectPeriodo .= '</select>';
    $colegio['periodo'] = $selectPeriodo;

    // Link del colegio con nombre y DANE
    $colegio['colegio'] = '<a class="linkcole" href="colegio.php?codigo=' . $colegio['codigo']
        . '&periodo=' . $primerPeriodo
        . '" data-id="' . $colegio['id']
        . '" data-codigo="' . $colegio['codigo'] . '">'
        . htmlspecialchars($colegio['colegio']) . '</a>';

    // Acciones
    $colegio['acciones'] = '<button class="btn btn-sm btn-ver-detalle"'
        . ' data-codigo="' . $colegio['codigo'] . '"'
        . ' data-id="' . $colegio['id'] . '"'
        . ' title="Ver detalle"><i class="bi bi-eye"></i></button>';
    if ($_SESSION['tipo'] == 1) {
        $colegio['acciones'] .= ' <a class="btn btn-sm btn-danger eliminar" href="#"'
            . ' data-codigo="' . $colegio['codigo'] . '"><i class="fa fa-trash-o bigger-120"></i></a>';
    }

    $data[] = $colegio;
}

echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $total,
    "recordsFiltered" => $filtered,
    "data"            => $data,
]);

} catch (Throwable $e) {
    echo json_encode([
        "draw"            => intval($_GET['draw'] ?? 1),
        "recordsTotal"    => 0,
        "recordsFiltered" => 0,
        "data"            => [],
        "error"           => $e->getMessage() . ' (línea ' . $e->getLine() . ')',
    ]);
}
?>
