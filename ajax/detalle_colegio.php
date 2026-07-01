<?php
require_once('../php/aut.php');
require_once('../conexion/bdd.php');

header('Content-Type: application/json');

$codigo  = isset($_GET['codigo'])  ? trim($_GET['codigo'])  : '';
$periodo = isset($_GET['periodo']) ? intval($_GET['periodo']) : 0;

if (empty($codigo)) {
    echo json_encode(['error' => 'Código requerido']);
    exit;
}

// Datos básicos del colegio con su calendario, segmento y último status registrado
$stmt = $bdd->prepare("
    SELECT
        c.id,
        c.colegio,
        c.codigo,
        c.direccion,
        c.ciudad,
        c.telefono,
        (SELECT s.segmento FROM segmentos s WHERE s.id = c.id_segmento) AS segmento,
        (SELECT sc.status
         FROM colegios_status cs
         JOIN status_cubrimiento sc ON cs.id_status = sc.id
         WHERE cs.id_colegio = c.id
         ORDER BY cs.id_periodo DESC LIMIT 1) AS status,
        (SELECT ec.estado
         FROM colegios_estados_clientes cec
         JOIN estados_cliente ec ON cec.id_estado_cliente = ec.id
         WHERE cec.id_colegio = c.id
         ORDER BY cec.id_periodo DESC LIMIT 1) AS estado_cliente
    FROM colegios c
    WHERE c.codigo = :codigo
    LIMIT 1
");
$stmt->execute([':codigo' => $codigo]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo json_encode(['error' => 'No encontrado']);
    exit;
}

// Si el frontend no envía periodo (panel abierto sin selección),
// se usa el último periodo que tenga presupuestos aprobados para ese colegio
if ($periodo > 0) {
    $ultimo_periodo = $periodo;
} else {
    $stmt_per = $bdd->prepare("SELECT MAX(id_periodo) FROM presupuestos WHERE id_colegio = ? AND pre_aprob = 1");
    $stmt_per->execute([$data['id']]);
    $ultimo_periodo = (int) $stmt_per->fetchColumn();
}

// Cálculo de venta potencial — replica exacta de la lógica de tab_presup.php y colegios_tabla_presup.php:
//   venta_potencial = floor(alumnos_grado × tasa_compra) × (precio − precio × descuento)
// Se usa pre_aprob (no pre_definido) porque es el flag que controla qué aparece en la vista de presupuesto.
// pre_aprob y pre_definido no siempre coinciden, usar pre_definido daría valores distintos a los que ve el usuario.
$valor_potencial = 0;

if ($ultimo_periodo > 0) {
    // Trae los libros aprobados en el presupuesto del colegio para el periodo indicado.
    // Se usa l.precio (precio base del libro) en lugar de p.precio (precio del presupuesto)
    // para mantener consistencia con lo que calcula tab_presup.php en la ficha del colegio.
    $stmt_presup = $bdd->prepare("
        SELECT l.precio, p.tasa_compra, p.descuento, p.probabilidad, p.cod_area, l.id_grado
        FROM presupuestos p
        JOIN libros l ON l.id = p.id_libro
        WHERE p.id_colegio = ? AND p.id_periodo = ? AND p.pre_aprob = 1
    ");
    $stmt_presup->execute([$data['id'], $ultimo_periodo]);
    $presups = $stmt_presup->fetchAll(PDO::FETCH_ASSOC);

    // Se preparan fuera del loop para no re-compilar la query en cada iteración
    $stmt_go      = $bdd->prepare("SELECT id_grado_otro FROM areas_objetivas WHERE codigo = ? LIMIT 1");
    $stmt_alumnos = $bdd->prepare("SELECT SUM(alumnos) FROM grados_paralelos WHERE id_colegio = ? AND id_grado = ? AND id_periodo = ? AND alumnos > 0");

    foreach ($presups as $presup) {
        // Probabilidad 3 significa descartado: no aporta a la venta potencial
        if ($presup['probabilidad'] == 3) {
            continue;
        }

        // Los libros de áreas objetivas (cod_area != '') no tienen grado propio en la tabla libros (id_grado = 17).
        // Se busca el grado real en areas_objetivas usando el código del área.
        // La búsqueda es solo por código porque el código es único por área objetiva en el sistema.
        if ($presup['cod_area'] === '') {
            $id_grado = $presup['id_grado'];
        } else {
            $stmt_go->execute([$presup['cod_area']]);
            $id_grado = $stmt_go->fetchColumn() ?: $presup['id_grado'];
        }

        $stmt_alumnos->execute([$data['id'], $id_grado, $ultimo_periodo]);
        $alumnos = (int) $stmt_alumnos->fetchColumn();

        // floor() porque no se pueden vender fracciones de libro
        $alumnos_tasa = floor($alumnos * $presup['tasa_compra']);
        $precio_neto  = $presup['precio'] - ($presup['precio'] * $presup['descuento']);
        $valor_potencial += $precio_neto * $alumnos_tasa;
    }
}

$data['valor_potencial'] = round($valor_potencial, 2);

// Cálculo de venta potencial de adopciones — replica exacta de la lógica de tab_adopciones.php:
// pre_definido=1 (libro marcado para adopción), aprobado<2, probabilidad!=3, definido=1 (adopción confirmada).
// Se usa p.precio del presupuesto siempre en el cálculo, sin fallback a l.precio,
// porque tab_adopciones.php usa $presup["precio"] directamente (si es 0, precio_neto queda en 0).
// La comparación de tasa usa != (laxa) igual que el original, donde "0.00" != "" es false en PHP.
$valor_potencial_adopciones = 0;

if ($ultimo_periodo > 0) {
    $stmt_adop = $bdd->prepare("
        SELECT p.precio, p.tasa_compra, p.tasa_compra_d, p.descuento, p.descuento_d,
               p.cod_area, l.id_grado
        FROM presupuestos p
        JOIN libros l ON l.id = p.id_libro
        WHERE p.id_colegio = ? AND p.id_periodo = ?
          AND p.pre_definido = 1 AND p.aprobado < 2
          AND p.probabilidad != 3 AND p.definido = 1
    ");
    $stmt_adop->execute([$data['id'], $ultimo_periodo]);
    $adops = $stmt_adop->fetchAll(PDO::FETCH_ASSOC);

    foreach ($adops as $adop) {
        if ($adop['cod_area'] == '') {
            $id_grado = $adop['id_grado'];
        } else {
            $stmt_go->execute([$adop['cod_area']]);
            $id_grado = $stmt_go->fetchColumn() ?: $adop['id_grado'];
        }

        $stmt_alumnos->execute([$data['id'], $id_grado, $ultimo_periodo]);
        $alumnos = (int) $stmt_alumnos->fetchColumn();

        // p.precio usado directamente: si es 0, precio_neto = 0 (igual que tab_adopciones.php)
        $precio = (float)$adop['precio'];

        // Condiciones laxas (!=) para replicar el comportamiento de PHP en tab_adopciones.php,
        // donde "0.00" != "" evalúa como false (ambos convierten a 0.0).
        if ($adop['tasa_compra'] != '' && $adop['tasa_compra_d'] == 0) {
            $tasa = (float)$adop['tasa_compra'];
            $desc = (float)$adop['descuento'];
        } elseif ($adop['tasa_compra_d'] != '') {
            $tasa = (float)$adop['tasa_compra_d'];
            $desc = (float)$adop['descuento_d'];
        } else {
            continue;
        }

        $precio_neto = $precio - ($precio * $desc);
        $valor_potencial_adopciones += $precio_neto * floor($alumnos * $tasa);
    }
}

$data['valor_potencial_adopciones'] = round($valor_potencial_adopciones, 2);

echo json_encode($data);
