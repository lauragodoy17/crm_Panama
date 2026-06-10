<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$sql = "SELECT p.id, p.tipo, u.nombres, u.apellidos, p.fecha, e.estado, c.proveedor AS cliente
        FROM devoluciones_prov p
        JOIN usuarios u ON u.id=p.id_usuario
        JOIN estados_pedidos e ON e.id=p.estado
        JOIN proveedores c ON c.id=p.persona
        WHERE p.tipo='2'";
$req = $bdd->prepare($sql);
$req->execute();
$pedidos = $req->fetchAll();
$total   = count($pedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Devoluciones de proveedores</title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
  <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <style>
    .lm-count-badge { font-size:12px; color:#64748b; background:#f1f5f9; border-radius:20px; padding:3px 10px; font-weight:500; }
    #vdp-table thead th {
      background: #1e40af !important; color: #fff !important;
      font-weight: 600; font-size: .80rem; padding: 11px 12px;
      white-space: nowrap; border: none;
    }
    #vdp-table tbody tr:nth-child(even) td { background: #eff6ff; }
    #vdp-table tbody tr:hover td           { background: #dbeafe !important; }
    #vdp-table tbody tr                    { border-left: 3px solid transparent; transition: border-color .15s; }
    #vdp-table tbody tr:hover              { border-left-color: #2563eb; }
    .vdp-btn-ver {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 600;
      border: 1.5px solid #2563eb; color: #2563eb; background: transparent;
      text-decoration: none; white-space: nowrap; transition: background .15s, color .15s;
    }
    .vdp-btn-ver:hover { background: #2563eb; color: #fff; text-decoration: none; }
    .vdp-badge-realizada { background:#dcfce7; color:#15803d; border-radius:20px; padding:2px 10px; font-size:11px; font-weight:600; }
    .vdp-badge-anulada   { background:#fee2e2; color:#dc2626; border-radius:20px; padding:2px 10px; font-size:11px; font-weight:600; }
    .vdp-badge-otro      { background:#fef9c3; color:#92400e; border-radius:20px; padding:2px 10px; font-size:11px; font-weight:600; }
  </style>
</head>
<body>

<?php include("template/nav_side.php"); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-sm-12">
            <div class="title"><h4>Devoluciones de proveedores</h4></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#dbeafe;color:#1d4ed8">
              <i class="bi bi-arrow-return-left"></i>
            </div>
            <div class="stat-info-modern">
              <h3><?= $total ?></h3>
              <p class="stat-label">Devoluciones</p>
              <span class="stat-sub">Total de registros</span>
            </div>
          </div>
        </div>
      </div>

      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="vdp-search" placeholder="Buscar por proveedor, usuario, estado...">
        </div>
      </div>

      <div class="modern-card">
        <div class="card-head">
          <h5><i class="bi bi-list-ul mr-2"></i> Lista de devoluciones</h5>
          <span class="lm-count-badge"><?= $total ?> registros</span>
        </div>
        <div class="table-responsive px-2 pb-2">
          <table class="table table-sm table-hover" id="vdp-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Proveedor</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pedidos as $pedido):
                $promotor   = $pedido['nombres'] . ' ' . $pedido['apellidos'];
                $estado_raw = $pedido['estado'];
                if ($estado_raw == 'Pendiente') {
                  $estado_txt = 'Realizada';
                  $badge_cls  = 'vdp-badge-realizada';
                } elseif ($estado_raw == 'Aprobado') {
                  $estado_txt = 'Aprobado';
                  $badge_cls  = 'vdp-badge-realizada';
                } elseif ($estado_raw == 'Anulado') {
                  $estado_txt = 'Anulado';
                  $badge_cls  = 'vdp-badge-anulada';
                } else {
                  $estado_txt = htmlspecialchars($estado_raw);
                  $badge_cls  = 'vdp-badge-otro';
                }
              ?>
              <tr>
                <td><?= $pedido['id'] ?></td>
                <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                <td><?= htmlspecialchars($promotor) ?></td>
                <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                <td><span class="<?= $badge_cls ?>"><?= $estado_txt ?></span></td>
                <td>
                  <a href="vista_devol.php?id_devol=<?= $pedido['id'] ?>&tipo=<?= $pedido['tipo'] ?>" class="vdp-btn-ver">
                    <i class="bi bi-eye"></i> Ver detalle
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
    <?php include("template/footer.php"); ?>
  </div>
</div>

<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="src/ink-alerts.js"></script>
<script>
$(document).ready(function () {
  var table = $('#vdp-table').DataTable({
    scrollX: true,
    autoWidth: false,
    order: [[0, 'desc']],
    language: {
      lengthMenu:   'Mostrar _MENU_ registros',
      zeroRecords:  'No se encontraron resultados',
      emptyTable:   'No hay información para mostrar',
      info:         'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty:    'Sin registros disponibles',
      infoFiltered: '(filtrado de _MAX_ registros)',
      search:       '',
      paginate: { first:'«', previous:'‹', next:'›', last:'»' }
    },
    initComplete: function () { $('.dataTables_filter').hide(); }
  });

  $('#vdp-search').on('keyup', function () { table.search(this.value).draw(); });
});
</script>
</body>
</html>
