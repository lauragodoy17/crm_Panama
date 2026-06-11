<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$tp = intval($_GET['tp'] ?? 2);

$status_cfg = [
  2 => ['label'=>'Pendientes',  'badge'=>'lm-badge-yellow', 'icon'=>'bi-hourglass-split'],
  3 => ['label'=>'Aprobados',   'badge'=>'lm-badge-green',  'icon'=>'bi-check-circle-fill'],
  4 => ['label'=>'Entregados',  'badge'=>'lm-badge-blue',   'icon'=>'bi-truck'],
  5 => ['label'=>'Anulados',    'badge'=>'lm-badge-red',    'icon'=>'bi-x-circle-fill'],
];
$st = $status_cfg[$tp] ?? $status_cfg[2];

$st_accent = [
  2 => ['hdr'=>'#92400e', 'even'=>'#fffbeb', 'hover'=>'#fef3c7', 'accent'=>'#b45309'],
  3 => ['hdr'=>'#166534', 'even'=>'#f0fdf4', 'hover'=>'#dcfce7', 'accent'=>'#16a34a'],
  4 => ['hdr'=>'#1e40af', 'even'=>'#eff6ff', 'hover'=>'#dbeafe', 'accent'=>'#2563eb'],
  5 => ['hdr'=>'#991b1b', 'even'=>'#fff1f2', 'hover'=>'#fee2e2', 'accent'=>'#b91c1c'],
];
$ac = $st_accent[$tp] ?? $st_accent[2];

if ($tp == 2) {
  $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='1' AND p.verify='1' GROUP BY p.id";
} elseif ($tp == 3) {
  $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='2' GROUP BY p.id";
} elseif ($tp == 4) {
  $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='4' GROUP BY p.id";
} else {
  $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='3' GROUP BY p.id";
}

$req = $bdd->prepare($sql);
$req->execute();
$pedidos = $req->fetchAll();
$total   = count($pedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Pedidos SA <?= $st['label'] ?></title>
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
    .lm-status-badge { display:inline-flex; align-items:center; gap:5px; font-size:13px; font-weight:600; padding:3px 12px; border-radius:20px; margin-left:10px; vertical-align:middle; }
    .lm-badge-yellow { background:#fef3c7; color:#b45309; }
    .lm-badge-green  { background:#dcfce7; color:#15803d; }
    .lm-badge-blue   { background:#dbeafe; color:#1d4ed8; }
    .lm-badge-red    { background:#fee2e2; color:#dc2626; }
    .lm-count-badge  { font-size:12px; color:#64748b; background:#f1f5f9; border-radius:20px; padding:3px 10px; font-weight:500; }
    .ft-date-wrap    { display:flex; align-items:center; gap:6px; }
    .ft-date-label   { font-size:12px; color:#64748b; font-weight:600; white-space:nowrap; margin:0; }
    #lps-table thead th {
      background: <?= $ac['hdr'] ?> !important;
      color: #fff !important;
      font-weight: 600; font-size: .80rem; padding: 11px 12px;
      white-space: nowrap; border: none;
    }
    #lps-table tbody tr:nth-child(even) td { background: <?= $ac['even'] ?>; }
    #lps-table tbody tr:hover td           { background: <?= $ac['hover'] ?> !important; }
    #lps-table tbody tr                    { border-left: 3px solid transparent; transition: border-color .15s; }
    #lps-table tbody tr:hover              { border-left-color: <?= $ac['accent'] ?>; }
    .lm-btn-ver {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 600;
      border: 1.5px solid <?= $ac['accent'] ?>; color: <?= $ac['accent'] ?>; background: transparent;
      text-decoration: none; white-space: nowrap; transition: background .15s, color .15s;
    }
    .lm-btn-ver:hover { background: <?= $ac['accent'] ?>; color: #fff; text-decoration: none; }
    @page { margin: 15px; size: landscape; }
    @media print {
      a, .left-side-bar, .header, .d-print-none { display: none !important; }
      a[href]:after { content: none !important; }
      body { font-size: 8px; }
      .main-container, .pd-ltr-20, .table-responsive { overflow: visible !important; }
      #lps-table { width: 100% !important; table-layout: auto !important; font-size: 7.5px !important; }
      #lps-table th, #lps-table td { padding: 3px 4px !important; }
      #lps-table thead th { background: <?= $ac['hdr'] ?> !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      #lps-table thead, #lps-table tfoot { display: table-row-group !important; }
      table { page-break-inside: auto; }
      tr    { page-break-inside: avoid; }
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
            <div class="title">
              <h4>
                Pedidos sin adopción
                <span class="lm-status-badge <?= $st['badge'] ?>">
                  <i class="bi <?= $st['icon'] ?>"></i> <?= $st['label'] ?>
                </span>
              </h4>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:<?= $ac['hover'] ?>;color:<?= $ac['accent'] ?>">
              <i class="bi <?= $st['icon'] ?>"></i>
            </div>
            <div class="stat-info-modern">
              <h3><?= $total ?></h3>
              <p class="stat-label"><?= $st['label'] ?></p>
              <span class="stat-sub">Total de registros</span>
            </div>
          </div>
        </div>
      </div>

      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="lps-search" placeholder="Buscar por colegio o distribuidor...">
        </div>
        <div class="ft-date-wrap">
          <span class="ft-date-label">Desde</span>
          <input type="date" class="ft-select" id="lps-fecha-desde" style="min-width:140px">
        </div>
        <div class="ft-date-wrap">
          <span class="ft-date-label">Hasta</span>
          <input type="date" class="ft-select" id="lps-fecha-hasta" style="min-width:140px">
        </div>
        <button class="ft-btn ft-apply" id="lps-btn-apply"><i class="bi bi-funnel"></i> Filtrar</button>
        <button class="ft-btn ft-clear" id="lps-btn-clear"><i class="bi bi-x-circle"></i> Limpiar</button>
      </div>

      <div class="modern-card">
        <div class="card-head">
          <h5><i class="bi bi-list-ul mr-2"></i> Lista — <?= $st['label'] ?></h5>
          <span class="lm-count-badge" style="background:<?= $ac['hover'] ?>;color:<?= $ac['accent'] ?>"><?= $total ?> registros</span>
        </div>
        <div class="table-responsive px-2 pb-2">
          <table class="table table-sm table-hover" id="lps-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Distribuidor</th>
                <th>Colegio</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pedidos as $p):
                $promotor = htmlspecialchars(trim(($p['nombres'] ?? '').' '.($p['apellidos'] ?? '')));
                $fecha_d  = date('d/m/Y', strtotime($p['fecha']));
                $fecha_r  = substr($p['fecha'], 0, 10);
              ?>
              <tr data-date="<?= $fecha_r ?>">
                <td><?= $p['id'] ?></td>
                <td><?= $fecha_d ?></td>
                <td><?= $promotor ?></td>
                <td><?= htmlspecialchars($p['colegio']) ?></td>
                <td>
                  <a href="pedido_colegio_sa.php?id_pedido=<?= $p['id'] ?>&tp=<?= $tp ?>" class="lm-btn-ver">
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
<script>
$(document).ready(function () {
  var table;

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'lps-table') return true;
    var desde = $('#lps-fecha-desde').val();
    var hasta = $('#lps-fecha-hasta').val();
    if ((desde || hasta) && table) {
      var raw = $(table.row(dataIndex).node()).data('date') || '';
      if (desde && raw < desde) return false;
      if (hasta && raw > hasta) return false;
    }
    return true;
  });

  table = $('#lps-table').DataTable({
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

  $('#lps-search').on('keyup', function () { table.search(this.value).draw(); });
  $('#lps-btn-apply').on('click', function () { table.draw(); });
  $('#lps-fecha-desde, #lps-fecha-hasta').on('change', function () { table.draw(); });
  $('#lps-btn-clear').on('click', function () {
    $('#lps-search').val('');
    $('#lps-fecha-desde, #lps-fecha-hasta').val('');
    table.search('').draw();
  });
});
</script>
</body>
</html>
