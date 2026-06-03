<?php
require_once("../php/aut.php");
require_once("../conexion/bdd.php");

if ($_SESSION['tipo'] != 1) {
    header('Location: ../index.php');
    exit;
}

// Normaliza para comparación: minúsculas, sin acentos, sin "D.C.", sin espacios extra
function normalizar($str) {
    $str = mb_strtolower(trim($str), 'UTF-8');
    $from = ['á','é','í','ó','ú','ü','ñ','à','è','ì','ò','ù','â','ê','î','ô','û'];
    $to   = ['a','e','i','o','u','u','n','a','e','i','o','u','a','e','i','o','u'];
    $str  = str_replace($from, $to, $str);
    $str  = preg_replace('/[\s,\-]*(d\.?\s*c\.?|distrito\s+capital)$/u', '', $str);
    $str  = preg_replace('/\s+/', ' ', $str);
    return trim($str);
}

$csv_path = __DIR__ . '/../../subregiones.csv';
$mensajes = [];
$step     = $_POST['step'] ?? '';

// ── PASO 1: Crear tabla e importar CSV ───────────────────────────────────────
if ($step === 'importar') {
    $bdd->exec("
        CREATE TABLE IF NOT EXISTS municipios (
            id            INT AUTO_INCREMENT PRIMARY KEY,
            nombre        VARCHAR(150) NOT NULL,
            codigo_dane   VARCHAR(10),
            id_departamento INT,
            INDEX idx_depto (id_departamento),
            INDEX idx_nombre (nombre(50))
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
    ");
    $bdd->exec("TRUNCATE TABLE municipios");

    // Mapa de departamentos: nombre_normalizado → id
    $depts_db = $bdd->query("SELECT id, departamento FROM departamentos")->fetchAll(PDO::FETCH_ASSOC);
    $dept_map = [];
    foreach ($depts_db as $d) {
        $dept_map[normalizar($d['departamento'])] = $d['id'];
    }

    $inserted   = 0;
    $sin_match  = [];
    $current_dept    = '';
    $current_dept_id = null;

    if (!file_exists($csv_path)) {
        $mensajes[] = ['danger', "No se encontró el archivo CSV en: $csv_path"];
    } else {
        $handle  = fopen($csv_path, 'r');
        $primero = true;

        // Quitar BOM si existe
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") rewind($handle);

        while (($row = fgetcsv($handle, 1000, ';')) !== false) {
            if ($primero) { $primero = false; continue; } // cabecera
            if (count($row) < 5) continue;

            $col0 = trim($row[0]);

            // Saltar filas de totales
            if (stripos($col0, 'Total') === 0) continue;

            // Actualizar departamento cuando la celda no está vacía
            if ($col0 !== '') {
                $current_dept    = $col0;
                $norm_dept       = normalizar($col0);
                $current_dept_id = $dept_map[$norm_dept] ?? null;
                if (!$current_dept_id) {
                    $sin_match[$col0] = true;
                }
            }

            $codigo = trim($row[2]);
            $nombre = trim($row[4]);

            if ($codigo === '' || $nombre === '') continue;

            $stmt = $bdd->prepare("INSERT INTO municipios (nombre, codigo_dane, id_departamento) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $codigo, $current_dept_id]);
            $inserted++;
        }
        fclose($handle);

        $mensajes[] = ['success', "Se importaron <strong>$inserted municipios</strong> correctamente."];
        if ($sin_match) {
            $mensajes[] = ['warning', "Departamentos del CSV sin coincidencia en la BD: <strong>" . implode(', ', array_keys($sin_match)) . "</strong>. Verifica que los nombres coincidan con la tabla <code>departamentos</code>."];
        }
    }
}

// ── PASO 2: Normalizar ciudades de colegios ──────────────────────────────────
if ($step === 'normalizar') {
    // Construir mapa: [id_departamento][nombre_normalizado] = nombre_canónico
    $mun_rows = $bdd->query("SELECT id_departamento, nombre FROM municipios WHERE id_departamento IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);
    $mun_map  = [];
    foreach ($mun_rows as $m) {
        $norm = normalizar($m['nombre']);
        $mun_map[$m['id_departamento']][$norm] = $m['nombre'];
    }

    $stmt_upd    = $bdd->prepare("UPDATE colegios SET ciudad = ? WHERE ciudad = ? AND departamento = ?");
    $actualizados = 0;

    $coles = $bdd->query("SELECT DISTINCT ciudad, departamento FROM colegios WHERE ciudad != '' AND ciudad IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($coles as $c) {
        $dept_id   = $c['departamento'];
        $ciudad    = $c['ciudad'];
        $norm      = normalizar($ciudad);
        $canonical = $mun_map[$dept_id][$norm] ?? null;

        if ($canonical && $canonical !== $ciudad) {
            $stmt_upd->execute([$canonical, $ciudad, $dept_id]);
            $actualizados += $stmt_upd->rowCount();
        }
    }
    $mensajes[] = ['success', "Se actualizaron <strong>$actualizados registros</strong> de colegios con nombres normalizados."];
}

// ── Estado actual ─────────────────────────────────────────────────────────────
$tabla_existe = false;
$total_mun    = 0;
try {
    $total_mun   = (int) $bdd->query("SELECT COUNT(*) FROM municipios")->fetchColumn();
    $tabla_existe = true;
} catch (Exception $e) {}

// ── Vista previa de ciudades que cambiarían ───────────────────────────────────
$preview = [];
if ($tabla_existe && $total_mun > 0) {
    $mun_rows = $bdd->query("SELECT id_departamento, nombre FROM municipios WHERE id_departamento IS NOT NULL")->fetchAll(PDO::FETCH_ASSOC);
    $mun_map  = [];
    foreach ($mun_rows as $m) {
        $norm = normalizar($m['nombre']);
        $mun_map[$m['id_departamento']][$norm] = $m['nombre'];
    }

    $coles = $bdd->query("
        SELECT c.ciudad, c.departamento, d.departamento AS nombre_dept, COUNT(*) AS total
        FROM colegios c
        LEFT JOIN departamentos d ON d.id = c.departamento
        WHERE c.ciudad != '' AND c.ciudad IS NOT NULL
        GROUP BY c.ciudad, c.departamento
        ORDER BY d.departamento, c.ciudad
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($coles as $c) {
        $norm      = normalizar($c['ciudad']);
        $canonical = $mun_map[$c['departamento']][$norm] ?? null;
        if ($canonical && $canonical !== $c['ciudad']) {
            $preview[] = [
                'dept'     => $c['nombre_dept'] ?? '—',
                'actual'   => $c['ciudad'],
                'canonico' => $canonical,
                'total'    => $c['total'],
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Importar y normalizar municipios</title>
  <link rel="stylesheet" href="../vendors/styles/core.css">
  <link rel="stylesheet" href="../vendors/styles/style.css">
  <style>
    body { padding: 20px; }
    .step-card { background:#fff; border:1px solid #dee2e6; border-radius:8px; padding:20px; margin-bottom:20px; }
    .step-card h5 { font-weight:700; margin-bottom:4px; }
    .step-card .sub { color:#6c757d; font-size:13px; margin-bottom:14px; }
    .badge-dept { background:#e9ecef; border-radius:4px; padding:2px 8px; font-size:12px; color:#495057; }
    .arrow { color:#adb5bd; margin:0 6px; }
    .nuevo { color:#198754; font-weight:600; }
    table.prev td, table.prev th { padding:6px 10px; font-size:13px; }
  </style>
</head>
<body>
<div class="main-container">
<div class="pd-ltr-20 xs-pd-20-10">
<div class="min-height-200px">

  <div class="page-header">
    <div class="row align-items-center">
      <div class="col">
        <h4>Importar municipios y normalizar ciudades</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administración</li>
            <li class="breadcrumb-item active">Municipios</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <?php foreach ($mensajes as [$tipo, $texto]): ?>
  <div class="alert alert-<?= $tipo ?>"><?= $texto ?></div>
  <?php endforeach; ?>

  <!-- PASO 1 -->
  <div class="step-card">
    <h5>Paso 1 — Importar municipios desde el CSV</h5>
    <p class="sub">
      Lee el archivo <code>subregiones.csv</code> del escritorio, crea (o reinicia) la tabla
      <code>municipios</code> y la llena con los nombres oficiales de cada municipio.
      <?php if ($tabla_existe): ?>
        <strong>Estado actual: <?= number_format($total_mun) ?> municipios cargados.</strong>
      <?php else: ?>
        <strong>La tabla aún no existe.</strong>
      <?php endif; ?>
    </p>
    <form method="POST">
      <input type="hidden" name="step" value="importar">
      <button class="btn btn-primary"
              onclick="return confirm('Esto vaciará y recargará la tabla municipios. ¿Continuar?')">
        <?= $tabla_existe ? 'Reimportar municipios' : 'Importar municipios' ?>
      </button>
    </form>
  </div>

  <!-- PASO 2 -->
  <div class="step-card">
    <h5>Paso 2 — Normalizar ciudades en la tabla <code>colegios</code></h5>
    <p class="sub">
      Compara cada ciudad registrada en colegios contra los municipios importados
      (ignorando mayúsculas, acentos y sufijos como "D.C.") y actualiza al nombre oficial.
    </p>

    <?php if (!$tabla_existe || $total_mun === 0): ?>
      <div class="alert alert-warning mb-0">Primero debes completar el Paso 1.</div>
    <?php elseif (empty($preview)): ?>
      <div class="alert alert-success mb-2">
        Todas las ciudades ya están normalizadas o no se encontraron coincidencias para mejorar.
      </div>
    <?php else: ?>
      <p>Se encontraron <strong><?= count($preview) ?></strong> ciudad(es) que se normalizarían:</p>
      <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered prev">
          <thead class="thead-light">
            <tr>
              <th>Departamento</th>
              <th>Ciudad actual en colegios</th>
              <th></th>
              <th>Nombre oficial (CSV)</th>
              <th>Colegios afectados</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($preview as $p): ?>
            <tr>
              <td><span class="badge-dept"><?= htmlspecialchars($p['dept']) ?></span></td>
              <td><?= htmlspecialchars($p['actual']) ?></td>
              <td class="arrow text-center">→</td>
              <td class="nuevo"><?= htmlspecialchars($p['canonico']) ?></td>
              <td class="text-center"><?= $p['total'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <form method="POST">
        <input type="hidden" name="step" value="normalizar">
        <button class="btn btn-success"
                onclick="return confirm('¿Aplicar la normalización a <?= count($preview) ?> ciudad(es)?')">
          Aplicar normalización
        </button>
      </form>
    <?php endif; ?>
  </div>

</div>
</div>
</div>
<script src="../vendors/scripts/core.js"></script>
</body>
</html>
