<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$tp = intval($_GET['tp'] ?? 2);

$status_cfg = [
  1 => ['label'=>'Solicitudes', 'badge'=>'lm-badge-purple', 'icon'=>'bi-clipboard-check'],
  2 => ['label'=>'Pendientes',  'badge'=>'lm-badge-yellow', 'icon'=>'bi-hourglass-split'],
  3 => ['label'=>'Aprobados',   'badge'=>'lm-badge-green',  'icon'=>'bi-check-circle-fill'],
  4 => ['label'=>'Despachados', 'badge'=>'lm-badge-blue',   'icon'=>'bi-truck'],
  5 => ['label'=>'Anulados',    'badge'=>'lm-badge-red',    'icon'=>'bi-x-circle-fill'],
];
$st = $status_cfg[$tp] ?? $status_cfg[2];

// Paleta de color por estado: header, filas pares, hover, acento para botón y borde
$st_accent = [
  1 => ['hdr'=>'#5b21b6', 'even'=>'#faf5ff', 'hover'=>'#ede9fe', 'accent'=>'#6d28d9'],
  2 => ['hdr'=>'#92400e', 'even'=>'#fffbeb', 'hover'=>'#fef3c7', 'accent'=>'#b45309'],
  3 => ['hdr'=>'#166534', 'even'=>'#f0fdf4', 'hover'=>'#dcfce7', 'accent'=>'#16a34a'],
  4 => ['hdr'=>'#1e40af', 'even'=>'#eff6ff', 'hover'=>'#dbeafe', 'accent'=>'#2563eb'],
  5 => ['hdr'=>'#991b1b', 'even'=>'#fff1f2', 'hover'=>'#fee2e2', 'accent'=>'#b91c1c'],
];
$ac = $st_accent[$tp] ?? $st_accent[2];

$gp_periodo = $bdd->query("SELECT id FROM periodos ORDER BY id DESC LIMIT 1")->fetch();

if ($tp == 1) {
  $sql = "SELECT e.estado AS estado_nombre, s.id, s.fecha, s.fecha_entrega, s.conse,
                 CONCAT(t.nombre,' ',t.apellido) AS solicitante, ca.cargo, c.colegio,
                 CONCAT(u.nombres,' ',u.apellidos) AS promotor
          FROM solicitudes_recursos s
          JOIN estados_pedidos e ON e.id=s.estado
          LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id
          LEFT JOIN cargos ca ON ca.id=t.cargo
          JOIN colegios c ON c.id=s.id_colegio
          JOIN usuarios u ON u.id=s.usuario
          WHERE s.id_periodo='".$gp_periodo['id']."'
          ORDER BY s.id DESC";
} elseif ($tp == 2) {
  if ($_SESSION['tipo'] != 10) {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='1' GROUP BY p.id";
  } else {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='1' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
  }
} elseif ($tp == 3) {
  if ($_SESSION['tipo'] != 10) {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='2' GROUP BY p.id";
  } else {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='2' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
  }
} elseif ($tp == 4) {
  if ($_SESSION['tipo'] != 10) {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='4' GROUP BY p.id";
  } else {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='4' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
  }
} else {
  if ($_SESSION['tipo'] != 10) {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='3' GROUP BY p.id";
  } else {
    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='3' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
  }
}

$req = $bdd->prepare($sql);
$req->execute();
$pedidos = $req->fetchAll();
$total   = count($pedidos);

$sub_zonas_map = [];
if ($tp != 1) {
  foreach ($bdd->query("SELECT id, sub_zona FROM sub_zonas")->fetchAll() as $sz)
    $sub_zonas_map[$sz['id']] = $sz['sub_zona'];
}

$zonas_uniq = [];
if ($tp != 1) {
  foreach ($pedidos as $p) {
    $tipo_p = intval($p['tipo'] ?? 0);
    $parts  = explode("/", $p['zona'] ?? '');
    $z      = ($tipo_p == 3 || $tipo_p == 1) ? trim($parts[1] ?? $parts[0] ?? '') : trim($p['zona'] ?? '');
    if ($z && !in_array($z, $zonas_uniq)) $zonas_uniq[] = $z;
  }
  sort($zonas_uniq);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Muestreo</title>
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
    .lm-badge-purple { background:#ede9fe; color:#6d28d9; }
    .lm-count-badge  { font-size:12px; color:#64748b; background:#f1f5f9; border-radius:20px; padding:3px 10px; font-weight:500; }
    .ft-date-wrap    { display:flex; align-items:center; gap:6px; }
    .ft-date-label   { font-size:12px; color:#64748b; font-weight:600; white-space:nowrap; margin:0; }
    .estado-badge    { display:inline-block; padding:2px 10px; border-radius:12px; font-size:11px; font-weight:600; }
    .eb-pending  { background:#fef3c7; color:#b45309; }
    /* ── Color temático por estado ─────────────────────────────── */
    #lm-table thead th {
      background: <?= $ac['hdr'] ?> !important;
      color: #fff !important;
      font-weight: 600;
      font-size: 0.80rem;
      padding: 11px 12px;
      white-space: nowrap;
      border: none;
    }
    #lm-table tbody tr:nth-child(even) td { background: <?= $ac['even'] ?>; }
    #lm-table tbody tr:hover td           { background: <?= $ac['hover'] ?> !important; }
    #lm-table tbody tr                    { border-left: 3px solid transparent; transition: border-color .15s; }
    #lm-table tbody tr:hover              { border-left-color: <?= $ac['accent'] ?>; }
    .lm-btn-ver {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 600;
      border: 1.5px solid <?= $ac['accent'] ?>; color: <?= $ac['accent'] ?>; background: transparent;
      text-decoration: none; white-space: nowrap; transition: background .15s, color .15s;
    }
    .lm-btn-ver:hover { background: <?= $ac['accent'] ?>; border-color: <?= $ac['accent'] ?>; color: #fff; text-decoration: none; }
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
                Muestreo
                <span class="lm-status-badge <?= $st['badge'] ?>">
                  <i class="bi <?= $st['icon'] ?>"></i> <?= $st['label'] ?>
                </span>
              </h4>
            </div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Muestreo</li>
                <li class="breadcrumb-item active"><?= $st['label'] ?></li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#eef2ff;color:#4361ee">
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
          <input type="text" id="lm-search" placeholder="Buscar por colegio, usuario, zona...">
        </div>
        <?php if ($tp != 1 && !empty($zonas_uniq)): ?>
        <select class="ft-select" id="lm-zona">
          <option value="">Todas las zonas</option>
          <?php foreach ($zonas_uniq as $z): ?>
          <option value="<?= htmlspecialchars($z) ?>"><?= htmlspecialchars($z) ?></option>
          <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <div class="ft-date-wrap">
          <span class="ft-date-label">Desde</span>
          <input type="date" class="ft-select" id="lm-fecha-desde" style="min-width:140px">
        </div>
        <div class="ft-date-wrap">
          <span class="ft-date-label">Hasta</span>
          <input type="date" class="ft-select" id="lm-fecha-hasta" style="min-width:140px">
        </div>
        <button class="ft-btn ft-apply" id="lm-btn-apply"><i class="bi bi-funnel"></i> Filtrar</button>
        <button class="ft-btn ft-clear" id="lm-btn-clear"><i class="bi bi-x-circle"></i> Limpiar</button>
      </div>

      <div class="modern-card">
        <div class="card-head">
          <h5><i class="bi bi-list-ul mr-2"></i> Lista — <?= $st['label'] ?></h5>
          <span class="lm-count-badge"><?= $total ?> registros</span>
        </div>
        <div class="table-responsive px-2 pb-2">

          <?php if ($tp == 1): ?>
          <table class="table table-sm table-hover" id="lm-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Conse</th>
                <th>Fecha</th>
                <th>Fecha entrega</th>
                <th>Solicitante</th>
                <th>Cargo</th>
                <th>Colegio</th>
                <th>Promotor</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pedidos as $p):
                $fecha_d = date('d/m/Y', strtotime($p['fecha']));
                $fecha_e = $p['fecha_entrega'] ? date('d/m/Y', strtotime($p['fecha_entrega'])) : '—';
                $fecha_r = substr($p['fecha'], 0, 10);
              ?>
              <tr data-date="<?= $fecha_r ?>">
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['conse']) ?></td>
                <td><?= $fecha_d ?></td>
                <td><?= $fecha_e ?></td>
                <td><?= htmlspecialchars($p['solicitante'] ?? '—') ?></td>
                <td><?= htmlspecialchars($p['cargo'] ?? '—') ?></td>
                <td><?= htmlspecialchars($p['colegio']) ?></td>
                <td><?= htmlspecialchars($p['promotor']) ?></td>
                <td><span class="estado-badge eb-pending"><?= htmlspecialchars($p['estado_nombre']) ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <?php else: ?>
          <table class="table table-sm table-hover" id="lm-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Empresa</th>
                <th>Zona</th>
                <th>Responsable</th>
                <th>Colegio</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pedidos as $p):
                $tipo_p = intval($p['tipo'] ?? 0);
                if ($tipo_p == 3 || $tipo_p == 1) {
                  $parts   = explode("/", $p['zona'] ?? '');
                  $empresa = htmlspecialchars(trim($parts[0] ?? ''));
                  $n_zona  = htmlspecialchars(trim($parts[1] ?? ''));
                  $resp    = htmlspecialchars(trim(($p['nombres'] ?? '').' '.($p['apellidos'] ?? '')));
                  $zona_d  = trim($parts[1] ?? $parts[0] ?? '');
                } else {
                  $empresa = htmlspecialchars($p['zona'] ?? '');
                  $n_zona  = htmlspecialchars($sub_zonas_map[$p['sub_zona']] ?? '—');
                  $resp    = htmlspecialchars($p['responsable'] ?? '—');
                  $zona_d  = $p['zona'] ?? '';
                }
                $fecha_d = date('d/m/Y', strtotime($p['fecha']));
                $fecha_r = substr($p['fecha'], 0, 10);
                $url_detalle = ($tp == 2)
                  ? "muestreo_colegio.php?id_pedido={$p['id']}"
                  : "muestreo_colegio_resto.php?id_pedido={$p['id']}&tp={$tp}";
              ?>
              <tr data-date="<?= $fecha_r ?>" data-zona="<?= htmlspecialchars($zona_d) ?>">
                <td><?= $p['id'] ?></td>
                <td><?= $fecha_d ?></td>
                <td><?= $empresa ?></td>
                <td><?= $n_zona ?></td>
                <td><?= $resp ?></td>
                <td><?= htmlspecialchars($p['colegio']) ?></td>
                <td>
                  <a href="<?= $url_detalle ?>" class="lm-btn-ver">
                    <i class="bi bi-eye"></i> Ver detalle
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>

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
    if (settings.nTable.id !== 'lm-table') return true;
    var zona  = $('#lm-zona').val();
    var desde = $('#lm-fecha-desde').val();
    var hasta = $('#lm-fecha-hasta').val();
    if (zona && table) {
      var rowZona = $(table.row(dataIndex).node()).data('zona') || '';
      if (rowZona !== zona) return false;
    }
    if ((desde || hasta) && table) {
      var raw = $(table.row(dataIndex).node()).data('date') || '';
      if (desde && raw < desde) return false;
      if (hasta && raw > hasta) return false;
    }
    return true;
  });

  table = $('#lm-table').DataTable({
    responsive: { details: false },
    autoWidth:  false,
    order:      [[0, 'desc']],
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

  $('#lm-search').on('keyup', function () { table.search(this.value).draw(); });
  $('#lm-btn-apply').on('click', function () { table.draw(); });
  $('#lm-fecha-desde, #lm-fecha-hasta').on('change', function () { table.draw(); });
  $('#lm-btn-clear').on('click', function () {
    $('#lm-search').val('');
    $('#lm-zona').val('');
    $('#lm-fecha-desde, #lm-fecha-hasta').val('');
    table.search('').draw();
  });
});
</script>
</body>
</html>
