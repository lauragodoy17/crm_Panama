<?php
require_once("../php/aut.php");
require_once("../conexion/bdd.php");

if ($_SESSION['tipo'] != 1) {
    header('Location: ../index.php');
    exit;
}

// Normaliza una ciudad para comparación: minúsculas, sin acentos, sin "D.C.", sin espacios extra
function normalizar($str) {
    $str = mb_strtolower(trim($str), 'UTF-8');
    $from = ['á','é','í','ó','ú','ü','ñ','à','è','ì','ò','ù','Á','É','Í','Ó','Ú','Ü','Ñ'];
    $to   = ['a','e','i','o','u','u','n','a','e','i','o','u','a','e','i','o','u','u','n'];
    $str  = str_replace($from, $to, $str);
    // Quitar sufijos comunes (D.C., Distrito Capital, etc.)
    $str  = preg_replace('/[\s,\-]*(d\.?\s*c\.?|distrito\s+capital)$/u', '', $str);
    // Colapsar espacios múltiples
    $str  = preg_replace('/\s+/', ' ', $str);
    return trim($str);
}

$mensaje = '';

// ── Aplicar cambios ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['canonico'])) {
    $actualizados = 0;
    $stmt_upd = $bdd->prepare("UPDATE colegios SET ciudad = ? WHERE ciudad = ?");

    foreach ($_POST['canonico'] as $norm => $canonico) {
        $variantes = json_decode($_POST['variantes'][$norm] ?? '[]', true);
        foreach ($variantes as $variante) {
            if ($variante !== $canonico) {
                $stmt_upd->execute([$canonico, $variante]);
                $actualizados += $stmt_upd->rowCount();
            }
        }
    }
    $mensaje = "Se actualizaron <strong>$actualizados registros</strong> correctamente.";
}

// ── Leer ciudades y agrupar ───────────────────────────────────────────────────
$stmt = $bdd->query("
    SELECT ciudad, COUNT(*) AS total
    FROM colegios
    WHERE ciudad != '' AND ciudad IS NOT NULL
    GROUP BY ciudad
    ORDER BY ciudad
");
$todas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$grupos = [];
foreach ($todas as $row) {
    $norm = normalizar($row['ciudad']);
    $grupos[$norm][] = ['ciudad' => $row['ciudad'], 'total' => (int)$row['total']];
}

// Solo grupos con más de una variante
$duplicados = [];
foreach ($grupos as $norm => $variantes) {
    if (count($variantes) > 1) {
        // Canónico sugerido: el que tiene más registros; en empate, el más largo (más completo)
        usort($variantes, function($a, $b) {
            if ($b['total'] !== $a['total']) return $b['total'] - $a['total'];
            return mb_strlen($b['ciudad']) - mb_strlen($a['ciudad']);
        });
        $duplicados[$norm] = $variantes;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Limpieza de ciudades</title>
  <link rel="stylesheet" href="../vendors/styles/core.css">
  <link rel="stylesheet" href="../vendors/styles/style.css">
  <style>
    body { padding: 20px; }
    .grupo { background:#fff; border:1px solid #dee2e6; border-radius:6px; padding:16px; margin-bottom:16px; }
    .grupo h6 { margin:0 0 10px; color:#495057; font-weight:600; }
    .variante { display:flex; align-items:center; gap:10px; padding:4px 0; }
    .badge-count { background:#e9ecef; color:#495057; border-radius:20px; padding:2px 8px; font-size:12px; }
    .canonico-label { color:#198754; font-weight:600; font-size:12px; }
    .sin-duplicados { background:#f8f9fa; border-radius:6px; padding:20px; text-align:center; color:#6c757d; }
  </style>
</head>
<body>
<?php include("../template/nav_side.php"); ?>
<div class="main-container">
<div class="pd-ltr-20 xs-pd-20-10">
<div class="min-height-200px">

  <div class="page-header">
    <div class="row align-items-center">
      <div class="col">
        <h4>Limpieza de ciudades duplicadas</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administración</li>
            <li class="breadcrumb-item active">Limpieza de ciudades</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>

  <?php if ($mensaje): ?>
  <div class="alert alert-success"><?= $mensaje ?></div>
  <?php endif; ?>

  <?php if (empty($duplicados)): ?>
  <div class="sin-duplicados">
    <i class="bi bi-check-circle" style="font-size:2rem;color:#198754"></i>
    <p class="mt-2 mb-0">No se encontraron ciudades duplicadas. La tabla está limpia.</p>
  </div>
  <?php else: ?>

  <div class="alert alert-info">
    Se encontraron <strong><?= count($duplicados) ?></strong> grupo(s) con nombres duplicados.
    Para cada grupo, selecciona el nombre canónico que quieres conservar y pulsa <strong>Aplicar limpieza</strong>.
  </div>

  <form method="POST">
    <?php foreach ($duplicados as $norm => $variantes): ?>
    <?php $canonico_sugerido = $variantes[0]['ciudad']; ?>
    <div class="grupo">
      <h6>Grupo: <em><?= htmlspecialchars($norm) ?></em></h6>
      <input type="hidden" name="variantes[<?= htmlspecialchars($norm) ?>]"
             value="<?= htmlspecialchars(json_encode(array_column($variantes, 'ciudad'))) ?>">
      <?php foreach ($variantes as $i => $v): ?>
      <div class="variante">
        <input type="radio"
               name="canonico[<?= htmlspecialchars($norm) ?>]"
               value="<?= htmlspecialchars($v['ciudad']) ?>"
               id="r_<?= md5($norm.$i) ?>"
               <?= $i === 0 ? 'checked' : '' ?>>
        <label for="r_<?= md5($norm.$i) ?>" style="margin:0;cursor:pointer">
          <?= htmlspecialchars($v['ciudad']) ?>
        </label>
        <span class="badge-count"><?= $v['total'] ?> colegio<?= $v['total'] != 1 ? 's' : '' ?></span>
        <?php if ($i === 0): ?>
        <span class="canonico-label">&#10003; sugerido</span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>

    <div class="mt-3 mb-4">
      <button type="submit" class="btn btn-success">
        Aplicar limpieza
      </button>
      <a href="../ver_colegios.php" class="btn btn-secondary ml-2">Cancelar</a>
    </div>
  </form>
  <?php endif; ?>

</div>
<?php include("../template/footer.php"); ?>
</div>
</div>
<script src="../vendors/scripts/core.js"></script>
</body>
</html>
