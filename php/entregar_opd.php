<?php
require_once("../php/aut.php");
require_once("../conexion/bdd.php");

header("Content-Type:text/html;charset=utf-8");

$error      = null;
$opd        = intval($_POST['opd'] ?? 0);
$observacion = $_POST['observaciones'] ?? '';

$arrays = [
    'entrega1' => $_POST['entrega1'] ?? [],
    'entrega2' => $_POST['entrega2'] ?? [],
    'entrega3' => $_POST['entrega3'] ?? [],
];

$stmt = $bdd->prepare("INSERT INTO entregas_opd(id_libro_opd, cant_entregada, observacion_entrega) VALUES(?, ?, ?)");

foreach ($arrays as $arr) {
    foreach ($arr as $val) {
        if (trim($val) === '') continue;

        $parts    = explode("/", $val, 2);
        $libro_id = $parts[0] ?? '';
        $cantidad = $parts[1] ?? '';

        if (trim($libro_id) === '') continue;

        $ok = $stmt->execute([$libro_id, $cantidad, $observacion]);
        if (!$ok) {
            $error = "Ocurrió un error al registrar una de las entregas.";
            break 2;
        }
    }
}

// Cambiar estado a "En proceso de entrega" y guardar fecha
if (!$error) {
    $ok = $bdd->prepare("UPDATE ordenes_produccion SET estado = 2, fecha_entrega = NOW() WHERE id = ?")
               ->execute([$opd]);
    if (!$ok) {
        $error = "Las entregas se guardaron pero no se pudo actualizar el estado de la OPD.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Inkpulse - Entregar OPD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .alert-card { background: #fff; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,.10); padding: 40px 48px; text-align: center; max-width: 440px; width: 90%; }
    .icon-wrap { width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; }
    .icon-ok  { background: #dcfce7; color: #16a34a; }
    .icon-err { background: #fee2e2; color: #dc2626; }
    h2 { font-size: 1.25rem; font-weight: 700; margin-bottom: 10px; }
    p  { font-size: .9rem; color: #64748b; line-height: 1.5; }
    .btn { display: inline-block; margin-top: 24px; padding: 10px 28px; border-radius: 8px; font-size: .9rem; font-weight: 600; text-decoration: none; cursor: pointer; border: none; }
    .btn-ok  { background: #16a34a; color: #fff; }
    .btn-err { background: #dc2626; color: #fff; }
    .countdown { font-size: .78rem; color: #94a3b8; margin-top: 10px; }
  </style>
</head>
<body>

<?php if (!$error): ?>
  <div class="alert-card">
    <div class="icon-wrap icon-ok">&#10003;</div>
    <h2>¡Entrega registrada!</h2>
    <p>Las entregas de la OPD #<?= $opd ?> fueron guardadas correctamente.</p>
    <p class="countdown" id="msg">Redirigiendo en 3 segundos...</p>
    <a href="../opd_solicitada.php?opd=<?= $opd ?>" class="btn btn-ok">Ver OPD</a>
  </div>
  <script>
    var s = 3;
    var t = setInterval(function () {
      s--;
      document.getElementById('msg').textContent = 'Redirigiendo en ' + s + ' segundo' + (s !== 1 ? 's' : '') + '...';
      if (s <= 0) { clearInterval(t); window.location.href = '../opd_solicitada.php?opd=<?= $opd ?>'; }
    }, 1000);
  </script>

<?php else: ?>
  <div class="alert-card">
    <div class="icon-wrap icon-err">&#10007;</div>
    <h2>Error al registrar</h2>
    <p><?= htmlspecialchars($error) ?></p>
    <a href="javascript:history.back()" class="btn btn-err">Volver e intentar de nuevo</a>
  </div>
<?php endif; ?>

</body>
</html>
