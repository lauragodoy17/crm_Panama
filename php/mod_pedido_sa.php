<?php
require_once("../php/aut.php");
require_once("../conexion/bdd.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../lib/PHPMailer/src/Exception.php';
require '../lib/PHPMailer/src/PHPMailer.php';
require '../lib/PHPMailer/src/SMTP.php';

header("Content-Type:text/html;charset=utf-8");

$error     = null;
$id_pedido = intval($_POST['pedido'] ?? 0);
$codigo    = $_POST['codigo']       ?? '';
$tp        = $_POST['tp']           ?? '2';
$redirect  = '../pedido_colegio_sa.php?id_pedido=' . $id_pedido . '&tp=' . $tp;

$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    // Eliminar libros que ya no están en el formulario
    $req = $bdd->prepare("SELECT id FROM libros_pedidos2 WHERE cod_pedido = ?");
    $req->execute([$codigo]);
    $libsp    = array_column($req->fetchAll(), 'id');
    $ids_post = array_map('intval', $_POST['lpid'] ?? []);
    foreach (array_diff($libsp, $ids_post) as $id_elim) {
        $bdd->prepare("DELETE FROM libros_pedidos2 WHERE id = ?")->execute([$id_elim]);
    }

    // Insertar nuevos libros
    foreach ($_POST['libro_e'] ?? [] as $libro) {
        if (empty($libro) || strpos($libro, '/') === false) continue;
        $parts    = explode('/', $libro, 3);
        $id_libro = $parts[0] ?? '';
        $cantidad = $parts[1] ?? '';
        $descuento = $parts[2] ?? '';
        if (trim($id_libro) === '') continue;
        $bdd->prepare("INSERT INTO libros_pedidos2(cod_pedido, id_libro, cantidad, descuento) VALUES(?, ?, ?, ?)")
            ->execute([$codigo, $id_libro, $cantidad, $descuento]);
    }

    // Actualizar cantidades/descuentos aprobados
    foreach ($_POST['lib_p'] ?? [] as $lib_p) {
        if (trim($lib_p) === '') continue;
        $parts = explode('/', $lib_p, 3);
        $cant  = $parts[0] ?? '';
        $lib   = $parts[1] ?? '';
        $desc  = $parts[2] ?? '';
        if (trim($lib) === '') continue;
        $bdd->prepare("UPDATE libros_pedidos2 SET cantidad_aprob = ?, descuento_aprob = ? WHERE id = ?")
            ->execute([$cant, $desc, $lib]);
    }

    $obs    = $_POST['observaciones'] ?? '';
    $salida = $_POST['salida']       ?? '';

    // Solo activa confirm+email cuando el botón fue "Confirmar", no "Guardar cambios"
    if ($salida === 'confirmar') {
        $bdd->prepare("UPDATE pedidos2 SET observaciones = ?, verify = '1' WHERE id = ?")
            ->execute([$obs, $id_pedido]);

        // Obtener nombre del promotor
        $sq = $bdd->prepare("SELECT CONCAT(nombres, ' ', apellidos) AS promotor FROM usuarios WHERE id = ?");
        $sq->execute([$_SESSION['id']]);
        $promo = $sq->fetch();

        // Enviar email (no bloquear si falla)
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host        = 'somoseureka.com.co';
            $mail->SMTPAuth    = true;
            $mail->SMTPAutoTLS = false;
            $mail->Username    = 'crm@somoseureka.com.co';
            $mail->Password    = 'cRm14356$';
            $mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port        = 587;
            $mail->SMTPOptions = ['ssl' => ['verify_peer'=>false,'verify_peer_name'=>false,'allow_self_signed'=>true]];
            $mail->setFrom('crm@somoseureka.com.co', 'CRM Eureka');
            $mail->addAddress('felipe.vargas@somoseureka.com.co');
            $mail->addReplyTo('crm@somoseureka.com.co', 'CRM Eureka');
            $mail->addCC('comercial@somoseureka.com.co');
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Solicitud de pedido sin adopción #' . $id_pedido;
            $mail->Body    = '<p style="font-size:17px;">El usuario: ' . htmlspecialchars($promo['promotor'] ?? '') .
                             ' hizo la solicitud de pedido sin adopción #' . $id_pedido .
                             '. Haz clic <a href="https://crm.somoseureka.com.co/pedido_colegio_sa.php?id_pedido_dist=' . $id_pedido . '&tp=2">aquí</a> para revisarlo.</p>';
            $mail->AltBody = 'Solicitud de pedido sin adopción #' . $id_pedido;
            $mail->send();
        } catch (Exception $e) {
            // El email falló pero el pedido ya se guardó; no es error fatal
        }

        $redirect = '../pedido_colegio_sa.php?id_pedido=' . $id_pedido . '&tp=2';
    } else {
        $bdd->prepare("UPDATE pedidos2 SET observaciones = ? WHERE id = ?")
            ->execute([$obs, $id_pedido]);
    }

} catch (Exception $e) {
    $error = 'Error al guardar el pedido: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Inkpulse - Confirmar pedido SA</title>
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
    <h2><?= ($_POST['salida'] ?? '') === 'confirmar' ? '¡Pedido confirmado!' : '¡Cambios guardados!' ?></h2>
    <p>El pedido SA #<?= $id_pedido ?> fue <?= ($_POST['salida'] ?? '') === 'confirmar' ? 'confirmado' : 'actualizado' ?> correctamente.</p>
    <p class="countdown" id="msg">Redirigiendo en 3 segundos...</p>
    <a href="<?= htmlspecialchars($redirect) ?>" class="btn btn-ok">Ver pedido</a>
  </div>
  <script>
    var dest = <?= json_encode($redirect) ?>;
    var s = 3;
    var t = setInterval(function () {
      s--;
      document.getElementById('msg').textContent = 'Redirigiendo en ' + s + ' segundo' + (s !== 1 ? 's' : '') + '...';
      if (s <= 0) { clearInterval(t); window.location.href = dest; }
    }, 1000);
  </script>

<?php else: ?>
  <div class="alert-card">
    <div class="icon-wrap icon-err">&#10007;</div>
    <h2>Error al guardar</h2>
    <p><?= htmlspecialchars($error) ?></p>
    <a href="javascript:history.back()" class="btn btn-err">Volver e intentar de nuevo</a>
  </div>
<?php endif; ?>

</body>
</html>
