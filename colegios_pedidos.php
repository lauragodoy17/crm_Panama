<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$periodo_id = isset($_POST["periodo"]) ? intval($_POST["periodo"]) : intval($_GET["periodo"] ?? 0);

$stmt = $bdd->prepare("SELECT id, periodo FROM periodos WHERE id=?");
$stmt->execute([$periodo_id]);
$gp_periodo = $stmt->fetch();

if ($_SESSION["tipo"] == 1 || $_SESSION["tipo"] == 2) {
  $sql = "SELECT c.id, c.dane AS codigo, c.colegio, c.direccion, c.barrio, c.telefono
          FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio
          WHERE p.definido='1' AND p.id_periodo='".$gp_periodo["id"]."' GROUP BY c.id";
} else {
  $sql = "SELECT c.id, c.dane AS codigo, c.colegio, c.direccion, c.barrio, c.telefono
          FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio
          WHERE c.cod_zona='".$_SESSION["zona"]."' AND p.definido='1' AND p.id_periodo='".$gp_periodo["id"]."' GROUP BY c.id";
}
$req = $bdd->prepare($sql);
$req->execute();
$colegios = $req->fetchAll();
$total = count($colegios);
$periodo_nombre = htmlspecialchars($gp_periodo["periodo"] ?? "—");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Pedidos de venta</title>
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
    #cp-table thead th {
      background: #1e40af !important; color: #fff !important;
      font-weight: 600; font-size: .80rem; padding: 11px 12px;
      white-space: nowrap; border: none;
    }
    #cp-table tbody tr:nth-child(even) td { background: #eff6ff; }
    #cp-table tbody tr:hover td           { background: #dbeafe !important; }
    #cp-table tbody tr                    { border-left: 3px solid transparent; transition: border-color .15s; }
    #cp-table tbody tr:hover              { border-left-color: #2563eb; }
    .cp-btn-solicitar {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 600;
      border: 1.5px solid #2563eb; color: #2563eb; background: transparent;
      text-decoration: none; white-space: nowrap; transition: background .15s, color .15s;
    }
    .cp-btn-solicitar:hover { background: #2563eb; color: #fff; text-decoration: none; }
    .cp-periodo-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: #eff6ff; color: #1d4ed8; border-radius: 8px;
      padding: 6px 14px; font-size: .82rem; font-weight: 600; border: 1px solid #bfdbfe;
    }
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
            <div class="title"><h4>Pedidos de venta</h4></div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#dbeafe;color:#1d4ed8">
              <i class="bi bi-building"></i>
            </div>
            <div class="stat-info-modern">
              <h3><?= $total ?></h3>
              <p class="stat-label">Colegios disponibles</p>
              <span class="stat-sub">Para el período seleccionado</span>
            </div>
          </div>
        </div>
      </div>

      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="cp-search" placeholder="Buscar por colegio, dane, barrio...">
        </div>
        <div class="cp-periodo-badge">
          <i class="bi bi-calendar3"></i> Período: <?= $periodo_nombre ?>
        </div>
      </div>

      <div class="modern-card">
        <div class="card-head">
          <h5><i class="bi bi-building mr-2"></i> Colegios — <?= $periodo_nombre ?></h5>
          <span class="lm-count-badge"><?= $total ?> colegios</span>
        </div>
        <div class="table-responsive px-2 pb-2">
          <table class="table table-sm table-hover" id="cp-table">
            <thead>
              <tr>
                <th>Dane</th>
                <th>Colegio</th>
                <th>Dirección</th>
                <th>Barrio</th>
                <th>Teléfono</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($colegios as $colegio): ?>
              <tr>
                <td><?= htmlspecialchars($colegio['codigo']) ?></td>
                <td><?= htmlspecialchars($colegio['colegio']) ?></td>
                <td><?= htmlspecialchars($colegio['direccion']) ?></td>
                <td><?= htmlspecialchars($colegio['barrio']) ?></td>
                <td><?= htmlspecialchars($colegio['telefono']) ?></td>
                <td>
                  <a href="solicitar_pedido.php?id_colegio=<?= $colegio['id'] ?>&periodo=<?= $gp_periodo['id'] ?>" class="cp-btn-solicitar">
                    <i class="bi bi-pencil-square"></i> Solicitar
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
  var table = $('#cp-table').DataTable({
    scrollX: true,
    autoWidth: false,
    order: [[1, 'asc']],
    language: {
      lengthMenu:   'Mostrar _MENU_ registros',
      zeroRecords:  'No se encontraron resultados',
      emptyTable:   'No hay colegios para este período',
      info:         'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty:    'Sin registros disponibles',
      infoFiltered: '(filtrado de _MAX_ registros)',
      search:       '',
      paginate: { first:'«', previous:'‹', next:'›', last:'»' }
    },
    initComplete: function () { $('.dataTables_filter').hide(); }
  });

  $('#cp-search').on('keyup', function () { table.search(this.value).draw(); });
});
</script>
</body>
</html>
