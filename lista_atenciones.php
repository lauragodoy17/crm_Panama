<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$tp = intval($_GET['tp'] ?? 2);

$status_cfg = [
  1 => ['label' => 'Todas',       'icon' => 'bi-list-ul'],
  2 => ['label' => 'Solicitadas', 'icon' => 'bi-hourglass-split'],
  3 => ['label' => 'Aprobadas',  'icon' => 'bi-check-circle-fill'],
  4 => ['label' => 'Entregadas',    'icon' => 'bi-cash-coin'],
  5 => ['label' => 'Anuladas',    'icon' => 'bi-x-circle-fill'],
];
$st = $status_cfg[$tp] ?? $status_cfg[2];

$ac_map = [
  1 => ['hdr' => '#4c1d95', 'even' => '#f5f3ff', 'hover' => '#ede9fe', 'accent' => '#7c3aed'],
  2 => ['hdr' => '#92400e', 'even' => '#fffbeb', 'hover' => '#fef3c7', 'accent' => '#b45309'],
  3 => ['hdr' => '#166534', 'even' => '#f0fdf4', 'hover' => '#dcfce7', 'accent' => '#16a34a'],
  4 => ['hdr' => '#1e40af', 'even' => '#eff6ff', 'hover' => '#dbeafe', 'accent' => '#2563eb'],
  5 => ['hdr' => '#991b1b', 'even' => '#fff1f2', 'hover' => '#fee2e2', 'accent' => '#b91c1c'],
];
$ac = $ac_map[$tp] ?? ['hdr' => '#374151', 'even' => '#f8fafc', 'hover' => '#f1f5f9', 'accent' => '#6b7280'];

if ($tp == 1) {
  $sql = "SELECT e.estado, s.id, s.fecha, CONCAT(t.nombre,' ',t.apellido) as solicitante, ca.cargo,
          s.fecha_entrega, s.conse, s.contab, c.colegio, CONCAT(u.nombres,' ',u.apellidos) as promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          ORDER BY s.id DESC";
} elseif ($tp == 2) {
  $sql = "SELECT e.estado, s.id, s.fecha, CONCAT(t.nombre,' ',t.apellido) as solicitante, ca.cargo,
          s.fecha_entrega, s.conse, s.contab, c.colegio, CONCAT(u.nombres,' ',u.apellidos) as promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          WHERE s.estado=1 ORDER BY s.id DESC";
} elseif ($tp == 3) {
  $sql = "SELECT e.estado, s.id, s.fecha, CONCAT(t.nombre,' ',t.apellido) as solicitante, ca.cargo,
          s.fecha_entrega, s.conse, s.contab, c.colegio, CONCAT(u.nombres,' ',u.apellidos) as promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          WHERE s.estado=2 ORDER BY s.id DESC";
} elseif ($tp == 4) {
  $sql = "SELECT e.estado, s.id, s.fecha, CONCAT(t.nombre,' ',t.apellido) as solicitante, ca.cargo,
          s.fecha_entrega, s.conse, s.contab, c.colegio, CONCAT(u.nombres,' ',u.apellidos) as promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          WHERE s.estado=4 ORDER BY s.id DESC";
} elseif ($tp == 5) {
  $sql = "SELECT e.estado, s.id, s.fecha, CONCAT(t.nombre,' ',t.apellido) as solicitante, ca.cargo,
          s.fecha_entrega, s.conse, s.contab, c.colegio, CONCAT(u.nombres,' ',u.apellidos) as promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          WHERE s.estado=3 ORDER BY s.id DESC";
} else {
  $sql = "SELECT e.estado, s.id, s.fecha, CONCAT(t.nombre,' ',t.apellido) as solicitante, ca.cargo,
          s.fecha_entrega, s.conse, s.contab, c.colegio, CONCAT(u.nombres,' ',u.apellidos) as promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          ORDER BY s.id DESC";
}

$req = $bdd->prepare($sql);
$req->execute();
$solicitudes = $req->fetchAll();
$total = count($solicitudes);

$promotores_uniq = [];
$estados_uniq    = [];
foreach ($solicitudes as $s) {
  if ($s['promotor'] && !in_array($s['promotor'], $promotores_uniq))
    $promotores_uniq[] = $s['promotor'];
  if ($s['estado'] && !in_array($s['estado'], $estados_uniq))
    $estados_uniq[] = $s['estado'];
}
sort($promotores_uniq);
sort($estados_uniq);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Atenciones <?= htmlspecialchars($st['label']) ?></title>
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
    #la-table thead th { background: <?= $ac['hdr'] ?>; color: #fff; font-size: .78rem; font-weight: 600; white-space: nowrap; padding: 10px 12px; border: none; }
    #la-table tbody tr:nth-child(even) { background: <?= $ac['even'] ?>; }
    #la-table tbody tr:hover { background: <?= $ac['hover'] ?>; }
    #la-table tbody td { font-size: .82rem; padding: 9px 12px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; color: #1e293b; }
    .la-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:600; }
    .la-badge-yellow { background:#fef9c3; color:#854d0e; }
    .la-badge-green  { background:#dcfce7; color:#166534; }
    .la-badge-blue   { background:#dbeafe; color:#1e40af; }
    .la-badge-purple { background:#f5f3ff; color:#4c1d95; }
    .la-badge-red    { background:#fee2e2; color:#991b1b; }
    .la-badge-gray   { background:#f1f5f9; color:#475569; }
    .la-link { color: <?= $ac['accent'] ?>; font-weight: 600; text-decoration: none; }
    .la-link:hover { text-decoration: underline; }
    #la-count-badge { background: <?= $ac['accent'] ?>; color:#fff; font-size:.72rem; font-weight:700; padding:3px 10px; border-radius:20px; }
    .ft-date-range { display:flex; align-items:center; gap:6px; flex-wrap:wrap; }
    .ft-date-label { font-size:.78rem; font-weight:600; color:#64748b; white-space:nowrap; }
    @media (max-width: 575px) {
      #la-table_wrapper { overflow-x: auto; }
      #la-table { min-width: 700px; }
      #la-table td, #la-table th { display: table-cell !important; }
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
          <div class="col-md-6 col-sm-12">
            <div class="title">
              <h4><i class="bi bi-headset mr-2" style="color:<?= $ac['accent'] ?>"></i>Atenciones — <?= htmlspecialchars($st['label']) ?></h4>
            </div>
          </div>
        </div>
      </div>

      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="la-search" placeholder="Buscar por colegio, consecutivo...">
        </div>
        <?php if (!empty($promotores_uniq)): ?>
        <select class="ft-select" id="la-promotor">
          <option value="">Todos los promotores</option>
          <?php foreach ($promotores_uniq as $p): ?>
          <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
          <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <?php if ($tp == 1 && !empty($estados_uniq)): ?>
        <select class="ft-select" id="la-estado">
          <option value="">Todos los estados</option>
          <?php foreach ($estados_uniq as $e): ?>
          <option value="<?= htmlspecialchars($e) ?>"><?= htmlspecialchars($e) ?></option>
          <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <div class="ft-date-range">
          <span class="ft-date-label">Desde</span>
          <input type="date" class="ft-select" id="la-fecha-desde">
          <span class="ft-date-label">Hasta</span>
          <input type="date" class="ft-select" id="la-fecha-hasta">
        </div>
        <button class="ft-btn ft-apply" id="la-btn-apply">
          <i class="bi bi-funnel"></i> Filtrar
        </button>
        <button class="ft-btn ft-clear" id="la-btn-clear">
          <i class="bi bi-x-circle"></i> Limpiar
        </button>
      </div>

      <div class="modern-card">
        <div class="table-responsive px-2 pb-2">
          <table class="table table-sm table-hover" id="la-table">
            <thead>
              <tr>
                <th>Consecutivo</th>
                <th>Fecha</th>
                <th>Promotor</th>
                <th>Colegio</th>
                <th>Solicitante</th>
                <th>Fecha de entrega</th>
                <th>Valor</th>
                <th>Estado</th>
                <?php if ($tp == 4): ?>
                  <th>Contabilizada</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($solicitudes as $s):
                $req_val = $bdd->prepare("SELECT SUM(presupuesto) as total FROM recursos_solicitados WHERE id_solicitud=?");
                $req_val->execute([$s['id']]);
                $valor = $req_val->fetchColumn() ?? 0;

                $e_low = strtolower($s['estado']);
                $badge_class = match(true) {
                  str_contains($e_low, 'solicit') || str_contains($e_low, 'pendient') => 'la-badge-yellow',
                  str_contains($e_low, 'aprob')                                        => 'la-badge-green',
                  str_contains($e_low, 'entreg') || str_contains($e_low, 'cobr')       => 'la-badge-blue',
                  str_contains($e_low, 'anul') || str_contains($e_low, 'rechaz') || str_contains($e_low, 'cerr') => 'la-badge-red',
                  default => 'la-badge-gray',
                };
                $fecha_raw = substr($s['fecha'], 0, 10);
              ?>
              <tr data-fecha="<?= $fecha_raw ?>" data-promotor="<?= htmlspecialchars($s['promotor']) ?>" data-estado="<?= htmlspecialchars($s['estado']) ?>">
                <td><a href="vista_solicitud.php?id=<?= $s['id'] ?>" class="la-link vista_soli"><?= htmlspecialchars($s['conse']) ?></a></td>
                <td><?= htmlspecialchars($s['fecha']) ?></td>
                <td><?= htmlspecialchars($s['promotor']) ?></td>
                <td><?= htmlspecialchars($s['colegio']) ?></td>
                <td><?= htmlspecialchars($s['solicitante'] . ($s['cargo'] ? ' ('.$s['cargo'].')' : '')) ?></td>
                <td><?= htmlspecialchars($s['fecha_entrega']) ?></td>
                <td>$ <?= number_format($valor, 0, ',', '.') ?></td>
                <td><span class="la-badge <?= $badge_class ?>"><?= htmlspecialchars($s['estado']) ?></span></td>
                <?php if ($tp == 4): ?>
                  <td><?= $s['contab'] ? 'Sí' : 'No' ?></td>
                <?php endif; ?>
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
  $.fn.dataTable.ext.errMode = 'none';

  var table = $('#la-table').dataTable({
    responsive: { details: false },
    autoWidth:  false,
    order:      [[1, 'desc']],
    dom:        '<"top"l>rt<"bottom"ip>',
    language: {
      lengthMenu:   "Mostrar _MENU_ registros",
      zeroRecords:  "No se encontraron resultados",
      emptyTable:   "No hay información para mostrar",
      info:         "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty:    "Sin registros disponibles",
      infoFiltered: "(filtrado de _MAX_ registros)",
      paginate: { first: "«", previous: "‹", next: "›", last: "»" }
    },
    initComplete: function () {
      var api = this.api();
      $('#la-search').on('keyup', function () {
        var val = this.value;
        if (val.length >= 1 || val.length === 0) api.search(val).draw();
      });
    }
  });

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'la-table') return true;
    var promotor = $('#la-promotor').val();
    var estado   = $('#la-estado').val();
    var desde    = $('#la-fecha-desde').val();
    var hasta    = $('#la-fecha-hasta').val();
    var $row     = $(table.api().row(dataIndex).node());
    if (promotor && $row.data('promotor') !== promotor) return false;
    if (estado   && $row.data('estado')   !== estado)   return false;
    if (desde || hasta) {
      var fecha = $row.data('fecha') || '';
      if (desde && fecha < desde) return false;
      if (hasta && fecha > hasta) return false;
    }
    return true;
  });

  $('#la-promotor, #la-estado').on('change', function () {
    table.api().draw();
  });

  $('#la-btn-apply').on('click', function () {
    table.api().draw();
  });

  $('#la-btn-clear').on('click', function () {
    $('#la-search').val('');
    $('#la-promotor').val('');
    $('#la-estado').val('');
    $('#la-fecha-desde, #la-fecha-hasta').val('');
    table.api().search('').draw();
  });

  $(document).on('click', '.vista_soli', function (e) {
    e.preventDefault();
    var url = $(this).attr('href');
    window.open(url, 'Popup', 'height=700,width=1300,scrollbars=1,resizable=1');
  });
});
</script>
</body>
</html>
