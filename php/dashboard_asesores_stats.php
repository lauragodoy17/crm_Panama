<?php
require_once("aut.php");
require_once("../conexion/bdd.php");
header('Content-Type: application/json');

// A diferencia de los otros dashboard_*_stats.php, este cruce por asesor es
// solo para el administrador (tipo 1), no para promotores/distribuidores.
$tipo_sesion = intval($_SESSION["tipo"] ?? 0);
if ($tipo_sesion !== 1) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
// Libera el lock del archivo de sesión: este endpoint solo lee $_SESSION y nunca
// escribe, así que mantenerlo abierto solo bloquea las otras peticiones paralelas
// del dashboard (visitas/presupuestos/adopciones/asesores) contra la misma sesión de PHP.
session_write_close();

$periodo = intval($_GET['periodo'] ?? 0);
if ($periodo <= 0) {
    $periodo = intval($bdd->query("SELECT id FROM periodos ORDER BY id DESC LIMIT 1")->fetchColumn());
}

// Esta gráfica compara asesor contra asesor tal como aparecen en los reportes de
// valorización (php/valoriza_excel.php y php/valoriza_global_excel.php), así que agrupa por
// quien cargó cada línea (presupuestos.id_usuario) igual que ellos — a diferencia del resto
// del dashboard (presupuestos/adopciones/venta real), que atribuye por el dueño de zona del
// colegio para que un colegio siempre sume al mismo asesor sin importar quién digitó la
// línea. Agrupar aquí por dueño de zona dejaba una porción enorme de colegios (~38% del
// total en un caso real) bajo "SIN ASESOR ASIGNADO" — colegios sin dueño de zona resuelto —
// que en los reportes sí aparecen atribuidos a un asesor real, haciendo que esta gráfica no
// cuadrara con ellos.
// Grupos por rol, por id_usuario (quien cargó la línea) — igual que el resto de esta
// gráfica, no por dueño de zona como usan dashboard_presupuestos_stats.php /
// dashboard_adopciones_stats.php / dashboard_ventareal_stats.php.
$rolCondiciones = ['promotor' => 'p.id_usuario IN (SELECT id FROM usuarios WHERE tipo = 3)', 'distribuidor' => 'p.id_usuario IN (SELECT id FROM usuarios WHERE tipo = 6)', 'otros' => 'p.id_usuario IN (SELECT id FROM usuarios WHERE tipo IN (1,4,10))'];
$rol = $_GET['rol'] ?? '';
$usuario = intval($_GET['usuario'] ?? 0);
if ($usuario > 0) {
    $userFilter = " AND p.id_usuario = $usuario";
} elseif (isset($rolCondiciones[$rol])) {
    $userFilter = " AND " . $rolCondiciones[$rol];
} else {
    // "Todos" es literalmente todos (promotores, otros y distribuidores combinados); el
    // filtro "Distribuidores" es solo un atajo para acotar la vista a ese grupo puntual.
    $userFilter = "";
}

$sql_periodo = $bdd->prepare("SELECT periodo FROM periodos WHERE id = ?");
$sql_periodo->execute([$periodo]);
$nombre_periodo = $sql_periodo->fetchColumn();

// Mismos joins/fórmulas que dashboard_presupuestos_stats.php y dashboard_adopciones_stats.php:
// valorizan cada ítem con el precio neto por el número de alumnos que se proyecta que compren
// (alumnos * tasa_compra), para poder comparar en las mismas unidades el presupuesto en
// definición contra lo ya adoptado, agrupado por asesor (presupuestos.id_usuario).
// El join a areas_objetivas se limita a un código de área por colegio (id_colegio, codigo).
// Puede haber muchas filas para el mismo código de área (una por estudiante/materia, no es
// raro tener ~100), así que la ambigüedad real no es "más de una fila" sino "más de un
// id_grado_otro distinto" entre esas filas.
$gradoJoin = "LEFT JOIN (
        SELECT id_colegio, codigo, MAX(id_grado_otro) as id_grado_otro
        FROM areas_objetivas
        WHERE id_periodo = $periodo AND codigo <> ''
        GROUP BY id_colegio, codigo
        HAVING COUNT(DISTINCT id_grado_otro) = 1
    ) ao ON ao.codigo = p.cod_area AND ao.id_colegio = p.id_colegio AND p.cod_area <> '' AND p.cod_area IS NOT NULL
    LEFT JOIN (SELECT id_colegio, id_grado, id_periodo, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_periodo = $periodo GROUP BY id_colegio, id_grado, id_periodo) gp
        ON gp.id_colegio = p.id_colegio AND gp.id_grado = COALESCE(ao.id_grado_otro, l.id_grado) AND gp.id_periodo = p.id_periodo";

$ventaPotencialExpr = "((p.precio - p.precio * p.descuento) * FLOOR(COALESCE(gp.alumnos, 0) * p.tasa_compra))";
$ventaAdopcionExpr = "(CASE WHEN p.tasa_compra_d = 0
    THEN (p.precio - p.precio * p.descuento) * FLOOR(COALESCE(gp.alumnos, 0) * p.tasa_compra)
    ELSE (p.precio - p.precio * p.descuento_d) * FLOOR(COALESCE(gp.alumnos, 0) * p.tasa_compra_d) END)";

// Mismo criterio que usa php/valoriza_excel.php ("valorización libro a libro") para que la
// venta real cuente igual en dashboard y reporte: exige que la línea ya tenga posición
// asignada en la zona (fila_zona > 0), excluye probabilidad "Perdida" (id=3) y las líneas
// sin ninguna tasa de compra asignada (ni propia ni de distribuidor).
$condicionesNegocio = " AND p.fila_zona > 0 AND p.probabilidad != 3 AND (p.tasa_compra != 0.00 OR p.tasa_compra_d != 0.00)";

// Se exige el JOIN a colegios en ambas consultas porque hay presupuestos "huérfanos" que
// referencian id_colegio de colegios ya eliminados; sin este join se seguían sumando aunque
// no pertenezcan a ningún colegio visible, mientras que php/valoriza_global_excel.php los
// excluye automáticamente al partir su consulta principal de la tabla colegios.

// ── Presupuesto potencial por asesor ──
$stmt = $bdd->prepare("SELECT p.id_usuario, UPPER(CONCAT(u.nombres, ' ', u.apellidos)) as asesor, SUM($ventaPotencialExpr) as total
    FROM presupuestos p
    JOIN colegios c ON p.id_colegio = c.id
    JOIN libros l ON p.id_libro = l.id
    JOIN usuarios u ON u.id = p.id_usuario
    $gradoJoin
    WHERE p.id_periodo = ? AND p.pre_definido = 1" . $condicionesNegocio . $userFilter . "
    GROUP BY p.id_usuario, asesor HAVING total > 0");
$stmt->execute([$periodo]);
$presupuestoPorAsesor = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Venta potencial de adopciones por asesor ──
$stmt = $bdd->prepare("SELECT p.id_usuario, UPPER(CONCAT(u.nombres, ' ', u.apellidos)) as asesor, SUM($ventaAdopcionExpr) as total
    FROM presupuestos p
    JOIN colegios c ON p.id_colegio = c.id
    JOIN libros l ON p.id_libro = l.id
    JOIN usuarios u ON u.id = p.id_usuario
    $gradoJoin
    WHERE p.id_periodo = ? AND p.definido = 1" . $condicionesNegocio . $userFilter . "
    GROUP BY p.id_usuario, asesor HAVING total > 0");
$stmt->execute([$periodo]);
$adopcionesPorAsesor = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Venta real por asesor ──
// Mismo criterio que php/valoriza_global_excel.php: por colegio, si recursos.venta_real
// está capturado (>0) ese valor manual reemplaza por completo al calculado (precio neto de
// adopción * unidades de venta real); si no, se usa el calculado. La decisión es por
// colegio (no se pueden sumar ambas fuentes), así que primero se arma el total por colegio
// y luego se agrupa por asesor.
// El valor calculado sí puede venir de varios asesores para un mismo colegio (cada línea
// conserva su propio id_usuario); el manual en cambio es un solo valor por colegio+período
// en recursos.venta_real, sin desglose por línea, así que cuando aplica se atribuye al
// asesor con MIN(p.id_usuario) del colegio (elección arbitraria pero determinística, igual
// de imperfecta que la que hace php/valoriza_global_excel.php al agrupar por colegio).
$stmt = $bdd->prepare("SELECT p.id_colegio, p.id_usuario, UPPER(CONCAT(u.nombres, ' ', u.apellidos)) as asesor,
        SUM(CASE WHEN p.tasa_compra_d = 0
            THEN (p.precio - p.precio * p.descuento) * p.uni_vr
            ELSE (p.precio - p.precio * p.descuento_d) * p.uni_vr END) as venta_calculada
    FROM presupuestos p
    JOIN colegios c ON p.id_colegio = c.id
    JOIN usuarios u ON u.id = p.id_usuario
    WHERE p.id_periodo = ? AND p.definido != 0" . $condicionesNegocio . $userFilter . "
    GROUP BY p.id_colegio, p.id_usuario, asesor");
$stmt->execute([$periodo]);
$ventaCalculadaPorColegio = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bdd->prepare("SELECT r.id_colegio, resp.id_usuario, UPPER(CONCAT(u.nombres, ' ', u.apellidos)) as asesor, MAX(r.venta_real) as venta_real
    FROM recursos r
    JOIN colegios c ON r.id_colegio = c.id
    JOIN (
        SELECT id_colegio, MIN(id_usuario) as id_usuario
        FROM presupuestos
        WHERE id_periodo = ?
        GROUP BY id_colegio
    ) resp ON resp.id_colegio = r.id_colegio
    JOIN usuarios u ON u.id = resp.id_usuario
    WHERE r.id_periodo = ? AND r.venta_real > 0" . str_replace('p.id_usuario', 'resp.id_usuario', $userFilter) . "
    GROUP BY r.id_colegio, resp.id_usuario, asesor");
$stmt->execute([$periodo, $periodo]);
$ventaManualPorColegio = $stmt->fetchAll(PDO::FETCH_ASSOC);

$ventaRealColegio = [];
foreach ($ventaCalculadaPorColegio as $row) {
    $ventaRealColegio[$row['id_colegio']] = ['id_usuario' => $row['id_usuario'], 'asesor' => $row['asesor'], 'total' => floatval($row['venta_calculada'])];
}
foreach ($ventaManualPorColegio as $row) {
    // El manual reemplaza al calculado para ese colegio, sin importar si ya había uno.
    $ventaRealColegio[$row['id_colegio']] = ['id_usuario' => $row['id_usuario'], 'asesor' => $row['asesor'], 'total' => floatval($row['venta_real'])];
}

$ventaRealPorAsesor = [];
foreach ($ventaRealColegio as $fila) {
    if (!isset($ventaRealPorAsesor[$fila['id_usuario']])) {
        $ventaRealPorAsesor[$fila['id_usuario']] = ['asesor' => $fila['asesor'], 'total' => 0.0];
    }
    $ventaRealPorAsesor[$fila['id_usuario']]['total'] += $fila['total'];
}

// ── Combina los tres grupos por asesor (no es un JOIN porque un asesor puede tener
// presupuesto sin adopciones todavía, o viceversa) ──
$porAsesor = [];
foreach ($presupuestoPorAsesor as $row) {
    $porAsesor[$row['id_usuario']] = ['asesor' => $row['asesor'], 'presupuesto' => floatval($row['total']), 'adopciones' => 0.0, 'venta_real' => 0.0];
}
foreach ($adopcionesPorAsesor as $row) {
    if (!isset($porAsesor[$row['id_usuario']])) {
        $porAsesor[$row['id_usuario']] = ['asesor' => $row['asesor'], 'presupuesto' => 0.0, 'adopciones' => 0.0, 'venta_real' => 0.0];
    }
    $porAsesor[$row['id_usuario']]['adopciones'] = floatval($row['total']);
}
foreach ($ventaRealPorAsesor as $id_usuario => $row) {
    if ($row['total'] <= 0) continue;
    if (!isset($porAsesor[$id_usuario])) {
        $porAsesor[$id_usuario] = ['asesor' => $row['asesor'], 'presupuesto' => 0.0, 'adopciones' => 0.0, 'venta_real' => 0.0];
    }
    $porAsesor[$id_usuario]['venta_real'] = $row['total'];
}

// Segunda vista (misma data, otro orden): el ranking por defecto prioriza el total
// combinado (efectivamente presupuesto primero, por ser la magnitud más grande de
// las tres); esta copia se ordena priorizando adopciones para responder "quién ha
// adoptado más", con el total combinado solo como desempate.
$porAsesorPorAdopciones = $porAsesor;
usort($porAsesorPorAdopciones, function ($a, $b) {
    return [$b['adopciones'], $b['presupuesto'] + $b['adopciones'] + $b['venta_real']]
        <=> [$a['adopciones'], $a['presupuesto'] + $a['adopciones'] + $a['venta_real']];
});
$porAsesorPorAdopciones = array_slice($porAsesorPorAdopciones, 0, 8);

usort($porAsesor, function ($a, $b) {
    return ($b['presupuesto'] + $b['adopciones'] + $b['venta_real']) <=> ($a['presupuesto'] + $a['adopciones'] + $a['venta_real']);
});
$porAsesor = array_slice($porAsesor, 0, 8);

echo json_encode([
    'success' => true,
    'periodo' => $nombre_periodo,
    'asesores' => [
        'labels' => array_column($porAsesor, 'asesor'),
        'presupuesto' => array_column($porAsesor, 'presupuesto'),
        'adopciones' => array_column($porAsesor, 'adopciones'),
        'venta_real' => array_column($porAsesor, 'venta_real'),
    ],
    'asesores_por_adopciones' => [
        'labels' => array_column($porAsesorPorAdopciones, 'asesor'),
        'presupuesto' => array_column($porAsesorPorAdopciones, 'presupuesto'),
        'adopciones' => array_column($porAsesorPorAdopciones, 'adopciones'),
        'venta_real' => array_column($porAsesorPorAdopciones, 'venta_real'),
    ],
]);
