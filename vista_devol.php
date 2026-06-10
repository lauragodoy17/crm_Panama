<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$id_devol = intval($_GET['id_devol']);
$tipo     = intval($_GET['tipo']);

// Base record
if ($tipo == 1) {
    $sql_pedido = "SELECT id FROM devoluciones WHERE id='".$id_devol."'";
} else {
    $sql_pedido = "SELECT id FROM devoluciones_prov WHERE id='".$id_devol."'";
}
$req_pedido = $bdd->prepare($sql_pedido);
$req_pedido->execute();
$pedido_base = $req_pedido->fetch();

// Full data
if ($tipo == 1) {
    $sql_pedido = "SELECT pe.fecha,pe.observaciones,pe.archivo,pe.codigo,u.nombres, u.apellidos, e.id as eid,e.estado, c.cliente, c.id as cid FROM devoluciones pe JOIN usuarios u ON u.id=pe.id_usuario JOIN estados_dev e ON e.id=pe.estado JOIN clientes c ON pe.persona=c.id WHERE pe.id='".$pedido_base["id"]."'";
} else {
    $sql_pedido = "SELECT pe.fecha,pe.observaciones,pe.archivo,pe.codigo,u.nombres, u.apellidos, e.id as eid,e.estado, c.proveedor as cliente, c.id as cid FROM devoluciones_prov pe JOIN usuarios u ON u.id=pe.id_usuario JOIN estados_dev e ON e.id=pe.estado JOIN proveedores c ON pe.persona=c.id WHERE pe.id='".$pedido_base["id"]."'";
}
$req_pedido = $bdd->prepare($sql_pedido);
$req_pedido->execute();
$pedido = $req_pedido->fetch();

// Libros
if ($tipo == 1) {
    $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.isbn, m.materia, lp.cantidad, lp.id as lpid FROM devoluciones pe LEFT JOIN libros_devol lp ON lp.cod_pedido=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$id_devol."'";
} else {
    $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.isbn, m.materia, lp.cantidad, lp.id as lpid FROM devoluciones_prov pe LEFT JOIN libros_devol lp ON lp.cod_pedido=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$id_devol."'";
}
$req = $bdd->prepare($sql);
$req->execute();
$libros = $req->fetchAll();

// OP
if ($tipo == 1) {
    $sql = "SELECT id, estado FROM ordenes_pedidos WHERE id_devol_c='".$id_devol."' AND estado!=4";
} else {
    echo $_GET["id_devol_p"];
    $sql = "SELECT id, estado FROM ordenes_pedidos WHERE id_devol_p='".$id_devol."' AND estado!=4";
}
$req = $bdd->prepare($sql);
$req->execute();
$op   = $req->rowCount();
$n_op = $req->fetch();

// Personas for admin modify
$personas = [];
if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 2) {
    $sql_per = ($tipo == 1) ? "SELECT * FROM clientes" : "SELECT * FROM proveedores";
    $req_per = $bdd->prepare($sql_per);
    $req_per->execute();
    $personas = $req_per->fetchAll();
}

// Materias for agregar libro
$req_mat = $bdd->prepare("SELECT id, materia FROM materias");
$req_mat->execute();
$materias = $req_mat->fetchAll();

$total_c  = array_sum(array_column($libros, 'cantidad'));
$is_admin = ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 2);
$titulo   = ($tipo == 1) ? 'Devolución de muestras' : 'Devolución de proveedores';
$tipo_lbl = ($tipo == 2) ? 'Proveedor' : 'Cliente';
$back_url = ($tipo == 2) ? 'ver_devol_proveedores.php' : '#';
$back_lbl = ($tipo == 2) ? 'Devoluciones proveedores' : 'Devoluciones';

$eid = intval($pedido['eid']);
if (isset($n_op['estado']) && $n_op['estado'] == 2) {
    $estado_display = 'Atendida';
    $estado_cls     = 'vd-badge-green';
} elseif ($eid == 1) {
    $estado_display = $pedido['estado'];
    $estado_cls     = 'vd-badge-yellow';
} elseif ($eid == 2) {
    $estado_display = $pedido['estado'];
    $estado_cls     = 'vd-badge-blue';
} elseif ($eid == 3) {
    $estado_display = $pedido['estado'];
    $estado_cls     = 'vd-badge-red';
} else {
    $estado_display = $pedido['estado'];
    $estado_cls     = 'vd-badge-green';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - <?= $titulo ?></title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <style>
    @page { margin: 30px; }
    @media print {
      a { display: none; }
      a[href]:after { content: none !important; }
      body { font-size: 9px; }
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .dc { width: 70px !important; }

    /* Info cards */
    .vd-info-row { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .vd-info-card {
      background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
      padding: 12px 18px; flex: 1 1 160px; min-width: 140px;
      box-shadow: 0 1px 3px rgba(15,23,42,.05);
    }
    .vd-ic-label { display: block; font-size: .7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .vd-ic-value { display: block; font-size: .9rem; font-weight: 600; color: #0f172a; }

    /* Estado badges */
    .vd-badge-yellow { display:inline-block; background:#fef3c7; color:#92400e; border-radius:20px; padding:3px 12px; font-size:12px; font-weight:600; }
    .vd-badge-green  { display:inline-block; background:#dcfce7; color:#15803d; border-radius:20px; padding:3px 12px; font-size:12px; font-weight:600; }
    .vd-badge-blue   { display:inline-block; background:#dbeafe; color:#1d4ed8; border-radius:20px; padding:3px 12px; font-size:12px; font-weight:600; }
    .vd-badge-red    { display:inline-block; background:#fee2e2; color:#dc2626; border-radius:20px; padding:3px 12px; font-size:12px; font-weight:600; }

    /* Libros table */
    .lm-count-badge { font-size:12px; color:#64748b; background:#f1f5f9; border-radius:20px; padding:3px 10px; font-weight:500; }
    #vd-table thead th {
      background: #1e40af !important; color: #fff !important;
      font-weight: 600; font-size: .80rem; padding: 11px 12px;
      white-space: nowrap; border: none;
    }
    #vd-table tbody tr:nth-child(even) td { background: #eff6ff; }
    #vd-table tbody tr:hover td           { background: #dbeafe !important; }
    #vd-table tfoot td { background: #f8fafc !important; font-weight: 700; padding: 10px 12px; }

    /* mc-btn */
    .mc-btn {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 10px 22px; border-radius: 8px; font-size: .9rem; font-weight: 700;
      border: none; cursor: pointer; text-decoration: none;
      transition: opacity .15s, transform .1s;
    }
    .mc-btn:hover { opacity: .88; transform: translateY(-1px); text-decoration: none; color: inherit; }
    .mc-btn-teal  { background: linear-gradient(135deg, #0f766e, #0d9488); color: #fff; }
    .mc-btn-red   { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; }
    .mc-btn-green { background: linear-gradient(135deg, #15803d, #16a34a); color: #fff; }
    .mc-btn-amber { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; }
    .mc-btn-blue  { background: linear-gradient(135deg, #1d4ed8, #2563eb); color: #fff; }
    .mc-btn:hover { color: #fff; }

    /* Libro blocks */
    .vd-book-block {
      border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px 16px 4px;
      margin-bottom: 14px; background: #fafafa;
    }
    .vd-book-label {
      font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase;
      letter-spacing: .04em; margin: 0 0 12px; display: flex; align-items: center; gap: 6px;
    }
    .vd-book-label i { color: #1d4ed8; }
    .vd-add-btn {
      display: inline-flex; align-items: center; gap: 6px; color: #1d4ed8;
      font-size: 13px; font-weight: 600; cursor: pointer; border: none;
      background: none; padding: 0; margin: 12px 0 20px; text-decoration: none;
    }
    .vd-add-btn:hover { color: #1e40af; }

    /* Print signature area */
    .vd-print-sigs { display: none; }
    @media print { .vd-print-sigs { display: flex; justify-content: space-between; margin-top: 40px; } }
  </style>
</head>
<body>

<?php include("template/nav_side.php"); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

      <!-- Page header -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-sm-12">
            <div class="title"><h4><?= $titulo ?></h4></div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $back_url ?>"><?= $back_lbl ?></a></li>
                <li class="breadcrumb-item active"># <?= $id_devol ?></li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <!-- Info cards -->
      <div class="vd-info-row">
        <div class="vd-info-card">
          <span class="vd-ic-label"><?= $titulo ?></span>
          <span class="vd-ic-value"># <?= $id_devol ?></span>
        </div>
        <div class="vd-info-card">
          <span class="vd-ic-label">Fecha</span>
          <span class="vd-ic-value"><?= htmlspecialchars($pedido['fecha']) ?></span>
        </div>
        <?php if (!empty($pedido['codigo'])): ?>
        <div class="vd-info-card">
          <span class="vd-ic-label">Código</span>
          <span class="vd-ic-value"><?= htmlspecialchars($pedido['codigo']) ?></span>
        </div>
        <?php endif; ?>
        <div class="vd-info-card" style="flex:2 1 220px;">
          <span class="vd-ic-label">Usuario</span>
          <span class="vd-ic-value"><?= htmlspecialchars($pedido['nombres'].' '.$pedido['apellidos']) ?></span>
        </div>
        <div class="vd-info-card" style="flex:2 1 220px;">
          <span class="vd-ic-label"><?= $tipo_lbl ?></span>
          <span class="vd-ic-value"><?= htmlspecialchars($pedido['cliente']) ?></span>
        </div>
        <div class="vd-info-card">
          <span class="vd-ic-label">Estado</span>
          <span class="<?= $estado_cls ?>"><?= htmlspecialchars($estado_display) ?></span>
        </div>
      </div>

      <!-- OP section -->
      <?php if ($op != 0): ?>
      <div class="modern-card mb-3">
        <div class="card-head">
          <h5><i class="bi bi-file-earmark-check mr-2"></i> Orden de Pedido vinculada</h5>
        </div>
        <div class="px-4 py-3">
          <a href="op_pendiente.php?op=<?= $n_op['id'] ?>" target="_blank" class="mc-btn mc-btn-blue d-print-none">
            <i class="bi bi-box-arrow-up-right"></i> OP # <?= $n_op['id'] ?>
          </a>
        </div>
      </div>
      <?php endif; ?>

      <!-- Soporte adjunto -->
      <?php if (!empty($pedido['archivo'])): ?>
      <div class="modern-card mb-3 d-print-none">
        <div class="card-head">
          <h5><i class="bi bi-paperclip mr-2"></i> Soporte adjunto</h5>
        </div>
        <div class="px-4 py-3">
          <a href="adjuntos/<?= htmlspecialchars($pedido['archivo']) ?>" target="_blank" class="mc-btn mc-btn-blue">
            <i class="bi bi-file-earmark-arrow-down"></i> <?= htmlspecialchars($pedido['archivo']) ?>
          </a>
        </div>
      </div>
      <?php endif; ?>

      <!-- Hidden print fields -->
      <div id="impre"></div>
      <input type="hidden" id="fecha_impre">

      <!-- Form wrapper (only when not Anulado) -->
      <?php if ($pedido['estado'] != 'Anulado'): ?>
      <form method="POST" action="php/mod_devol.php" id="form_pedido">
      <?php endif; ?>

        <!-- Admin: modify persona -->
        <?php if ($is_admin && $pedido['estado'] != 'Anulado'): ?>
        <div class="modern-card mb-3">
          <div class="card-head">
            <h5><i class="bi bi-person-lines-fill mr-2"></i> Modificar <?= $tipo_lbl ?></h5>
          </div>
          <div class="px-4 py-3">
            <div class="row">
              <div class="col-md-6 col-12">
                <label class="control-label"><?= $tipo_lbl ?> <small style="color:red;">*</small></label>
                <select class="form-control select2" name="persona" id="persona" style="width:100%;" required>
                  <option value="">Seleccionar</option>
                  <?php foreach ($personas as $p):
                    $p_id  = $p['id'];
                    $p_nom = ($tipo == 1) ? $p['cliente'] : $p['proveedor'];
                  ?>
                  <option value="<?= $p_id ?>" <?= ($p_id == $pedido['cid']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p_nom) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Libros table -->
        <div class="modern-card mb-3">
          <div class="card-head">
            <h5><i class="bi bi-book mr-2"></i> Libros de la devolución</h5>
            <span class="lm-count-badge"><?= count($libros) ?> libro(s) &middot; Total: <?= $total_c ?></span>
          </div>
          <div class="table-responsive px-2 pb-2">
            <table class="table table-sm table-hover" id="vd-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>ISBN</th>
                  <th>Título</th>
                  <th>Materia</th>
                  <th>Grado</th>
                  <th>Cantidad</th>
                  <?php if ($is_admin): ?><th class="d-print-none">Acciones</th><?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($libros as $libro):
                  if (empty($libro['cod_area'])) {
                    $sql_g = "SELECT grado FROM grados WHERE id='".$libro['id_grado']."'";
                  } else {
                    $sql_ca = "SELECT id_grado_otro FROM areas_objetivas WHERE codigo='".$libro['cod_area']."'";
                    $req_ca = $bdd->prepare($sql_ca);
                    $req_ca->execute();
                    $go     = $req_ca->fetch();
                    $sql_g  = "SELECT grado FROM grados WHERE id='".$go['id_grado_otro']."'";
                  }
                  $req_g = $bdd->prepare($sql_g);
                  $req_g->execute();
                  $grado = $req_g->fetch();
                ?>
                <tr id="<?= $libro['lpid'] ?>">
                  <td><?= $i ?></td>
                  <td><?= htmlspecialchars($libro['isbn']) ?></td>
                  <td><?= htmlspecialchars($libro['libro']) ?></td>
                  <td><?= htmlspecialchars($libro['materia']) ?></td>
                  <td><?= htmlspecialchars($grado['grado'] ?? '—') ?></td>
                  <td>
                    <?php if ($is_admin && $pedido['estado'] != 'Anulado'): ?>
                    <input type="number" id="c<?= $libro['lpid'] ?>" name="cantidad_a"
                           value="<?= $libro['cantidad'] ?>" class="form-control dc">
                    <?php else: ?>
                    <?= $libro['cantidad'] ?>
                    <?php endif; ?>
                  </td>
                  <?php if ($is_admin): ?>
                  <td class="d-print-none">
                    <button type="button" class="btn btn-danger btn-xs" id="e<?= $libro['lpid'] ?>">
                      <i class="fa fa-trash"></i>
                    </button>
                    <input type="hidden" name="lpid[]" value="<?= $libro['lpid'] ?>">
                    <input type="hidden" name="lib_p[]" id="l<?= $libro['lpid'] ?>">
                  </td>
                  <?php else: ?>
                  <td style="display:none">
                    <input type="hidden" name="lpid[]" value="<?= $libro['lpid'] ?>">
                    <input type="hidden" name="lib_p[]" id="l<?= $libro['lpid'] ?>">
                  </td>
                  <?php endif; ?>
                </tr>
                <?php $i++; endforeach; ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" style="text-align:right; padding-right:16px;"><strong>Total:</strong></td>
                  <td><strong><?= $total_c ?></strong></td>
                  <?php if ($is_admin): ?><td class="d-print-none"></td><?php endif; ?>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <script>
        <?php foreach ($libros as $libro): ?>
        $('#c<?= $libro["lpid"] ?>').on('keyup', function(){
          var cant = $(this).val();
          $('#l<?= $libro["lpid"] ?>').val(cant + '/' + <?= $libro["lpid"] ?>);
        });
        <?php if ($is_admin): ?>
        (function(lpid, nombre){
          $('#e' + lpid).on('click', function(){
            inkConfirm({
              type: 'danger',
              title: '¿Eliminar libro?',
              text: 'Se quitará "' + nombre + '" de esta devolución.',
              btnOk: 'Sí, eliminar'
            }, function(){ $('#' + lpid).remove(); });
          });
        })(<?= $libro["lpid"] ?>, <?= json_encode($libro["libro"]) ?>);
        <?php endif; ?>
        <?php endforeach; ?>
        </script>

        <!-- Agregar libro hidden blocks -->
        <?php for ($i = 1; $i < 100; $i++): ?>
        <div id="agg_l<?= $i ?>" class="d-none vd-book-block">
          <p class="vd-book-label"><i class="bi bi-bookmark-fill"></i> Libro #<?= $i ?>:</p>
          <div class="row">
            <div class="form-group col-sm-4 col-12">
              <label id="l_materia<?= $i ?>" for="materia<?= $i ?>" class="control-label">
                Materia <small style="color:red;">*</small>
              </label>
              <select name="materia[]" id="materia<?= $i ?>" class="form-control">
                <option value="">Seleccionar</option>
                <?php foreach ($materias as $mat): ?>
                <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['materia']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group col-sm-4 col-12">
              <label id="l_libro<?= $i ?>" for="libro<?= $i ?>" class="control-label">
                Libro <small style="color:red;">*</small>
              </label>
              <select name="libro" id="libro<?= $i ?>" class="form-control select2"></select>
            </div>
            <div class="form-group col-sm-4 col-12">
              <label id="l_cantidad<?= $i ?>" for="cantidad<?= $i ?>" class="control-label">
                Cantidad <small style="color:red;">*</small>
              </label>
              <input type="number" class="form-control" name="cantidad" id="cantidad<?= $i ?>">
            </div>
          </div>
          <input type="hidden" name="libro_e[]" id="libro_e<?= $i ?>">
        </div>
        <?php endfor; ?>

        <a id="agregar_libro" class="vd-add-btn d-print-none">
          <i class="bi bi-plus-circle"></i> Agregar libro
        </a>

        <input type="hidden" name="pedido" value="<?= $id_devol ?>">
        <input type="hidden" name="codigo" value="<?= $pedido['codigo'] ?>">
        <input type="hidden" name="tipo"   value="<?= $tipo ?>">

        <!-- Observaciones -->
        <div class="modern-card mb-3">
          <div class="card-head">
            <h5><i class="bi bi-chat-text mr-2"></i> Observaciones</h5>
          </div>
          <div class="px-4 py-3">
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3"
              placeholder="Sin observaciones..."><?= htmlspecialchars($pedido['observaciones']) ?></textarea>
          </div>
        </div>

        <!-- Print signature area -->
        <div class="vd-print-sigs">
          <div id="entregado"></div>
          <div id="recibido"></div>
        </div>

        <!-- Action buttons -->
        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:24px;" class="d-print-none">
          <?php
          if ($eid == 1 && (!isset($n_op['estado']) || $n_op['estado'] != 2)) {
            if ($is_admin) {
              echo '<button class="mc-btn mc-btn-red" id="rechazar" type="button"><i class="bi bi-x-circle"></i> Anular</button>';
              echo '<button class="mc-btn mc-btn-green" id="aprobar" type="button"><i class="bi bi-check-circle"></i> Recibir</button>';
            }
          } elseif ($eid == 2 && (!isset($n_op['estado']) || $n_op['estado'] != 2)) {
            if ($is_admin) {
              echo '<button class="mc-btn mc-btn-red" id="rechazar" type="button"><i class="bi bi-x-circle"></i> Anular</button>';
              echo '<button class="mc-btn mc-btn-amber" id="proceso" type="button"><i class="bi bi-arrow-repeat"></i> En proceso</button>';
            }
          } elseif (isset($n_op['estado']) && $n_op['estado'] == 2) {
            echo '<span class="vd-badge-green" style="padding:10px 18px;font-size:.9rem;">Atendida</span>';
          } else {
            $bc = ($eid == 3) ? 'vd-badge-red' : 'vd-badge-blue';
            echo '<span class="'.$bc.'" style="padding:10px 18px;font-size:.9rem;">'.htmlspecialchars($pedido['estado']).'</span>';
          }
          ?>
          <?php if ($is_admin && $op == 0): ?>
            <?php if ($tipo == 1): ?>
            <a href="solicitar_op.php?id_devol_c=<?= $id_devol ?>" target="_blank" class="mc-btn mc-btn-amber">
              <i class="bi bi-file-earmark-plus"></i> Solicitar OP
            </a>
            <?php else: ?>
            <a href="solicitar_op.php?id_devol_p=<?= $id_devol ?>" target="_blank" class="mc-btn mc-btn-amber">
              <i class="bi bi-file-earmark-plus"></i> Solicitar OP
            </a>
            <?php endif; ?>
          <?php endif; ?>
          <button type="button" id="imprimir" class="mc-btn mc-btn-teal">
            <i class="bi bi-printer"></i> Imprimir
          </button>
          <?php if ($is_admin && $pedido['estado'] != 'Anulado'): ?>
          <button type="button" class="mc-btn mc-btn-blue" id="modificar">
            <i class="bi bi-check-lg"></i> Guardar cambios
          </button>
          <?php endif; ?>
        </div>

      <?php if ($pedido['estado'] != 'Anulado'): ?>
      </form>
      <?php endif; ?>

    </div>
    <?php include("template/footer.php"); ?>
  </div>
</div>

<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
<script src="src/ink-alerts.js"></script>
<script>
  $("#rechazar").click(function(){
    inkConfirm({
      type: 'danger',
      title: '¿Anular devolución?',
      text: 'Esta acción no se puede deshacer.',
      btnOk: 'Sí, anular'
    }, function(){
      window.location = "php/accion_devol.php?rechazar=<?= $id_devol ?>&tipo=<?= $tipo ?>";
    });
  });

  $("#aprobar").click(function(){
    inkConfirm({
      type: 'success',
      title: '¿Confirmar recepción?',
      text: 'Se marcará la devolución como recibida.',
      btnOk: 'Sí, recibir'
    }, function(){
      window.location = "php/accion_devol.php?aprobar=<?= $id_devol ?>&tipo=<?= $tipo ?>";
    });
  });

  $("#proceso").click(function(){
    inkConfirm({
      type: 'warning',
      title: '¿Poner en proceso?',
      text: 'Se actualizará el estado de la devolución.',
      btnOk: 'Confirmar'
    }, function(){
      window.location = "php/accion_devol.php?proceso=<?= $id_devol ?>&tipo=<?= $tipo ?>";
    });
  });

  $("#modificar").click(function(){
    $("#form_pedido").submit();
  });

  $("#imprimir").click(function(){
    window.print();
  });

  var m = 1;
  $("#agregar_libro").click(function(){
    if (m > 98) $(this).addClass("d-none");
    $("#agg_l" + m).removeClass("d-none");
    m++;

    <?php for ($i = 1; $i < 100; $i++): ?>
    $('#materia<?= $i ?>').on('change', function(){
      var valor = $(this).val();
      var dataString = 'mat_gra=' + valor;
      $.ajax({
        url: "ajax/buscar_l_eureka2.php",
        type: "POST",
        data: dataString,
        dataType: "html",
        success: function(resp){
          $("#libro<?= $i ?>").html(resp);
          var cant  = $('#cantidad<?= $i ?>').val();
          var libro = $('#libro<?= $i ?>').val();
          var desc  = $('#descuento<?= $i ?>').val();
          $('#libro_e<?= $i ?>').val(libro + '/' + cant + '/' + desc);
        },
        error: function(jqXHR, estado, error){ alert("error"); console.log(estado); console.log(error); },
        complete: function(jqXHR, estado){ console.log(estado); }
      });
    });

    $('#cantidad<?= $i ?>').keyup(function(){
      var cant  = $('#cantidad<?= $i ?>').val();
      var libro = $('#libro<?= $i ?>').val();
      var desc  = $('#descuento<?= $i ?>').val();
      $('#libro_e<?= $i ?>').val(libro + '/' + cant + '/' + desc);
    });

    $('#libro<?= $i ?>').on('change', function(){
      var cant  = $('#cantidad<?= $i ?>').val();
      var libro = $('#libro<?= $i ?>').val();
      var desc  = $('#descuento<?= $i ?>').val();
      $('#libro_e<?= $i ?>').val(libro + '/' + cant + '/' + desc);
    });
    <?php endfor; ?>
  });

  <?php if ($is_admin): ?>
  window.addEventListener('beforeprint', function(){
    $("#impre").html("<h4>Fecha recibido bodega: <?= date('Y-m-d H:i') ?></h4>");
    $("#entregado").html("<h4>Entregado por: ___________________________  </h4>");
    $("#recibido").html("<h4>Recibido por: ___________________________</h4>");
    $.ajax({
      url: "ajax/fecha_impre_devol.php",
      type: "POST",
      data: 'feid=' + "<?= date('Y-m-d H:i:s') ?>" + '/' + "<?= $id_devol ?>",
      dataType: "html",
      success: function(resp){},
      error: function(jqXHR, estado, error){ alert("error"); console.log(estado); console.log(error); },
      complete: function(jqXHR, estado){ console.log(estado); }
    });
  });
  <?php endif; ?>
</script>
</body>
</html>
