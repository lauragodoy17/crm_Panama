<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");
?>
<?php
$sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, p.fecha, c.colegio, e.id as eid, e.estado
        FROM pedidos p
        JOIN colegios c       ON p.id_colegio = c.id
        JOIN zonas z          ON z.codigo = c.cod_zona
        JOIN usuarios u       ON u.cod_zona = z.codigo
        JOIN estados_pedidos e ON e.id = p.estado
        WHERE p.estado='2' AND p.id_usuario='".$_POST["promotor"]."' AND p.id_periodo='".$_POST["periodo"]."'
        GROUP BY p.id";
$req = $bdd->prepare($sql);
$req->execute();
$pedidos_raw = $req->fetchAll();

// Filtrar solo los que no tienen OP ni OP agrupado
$pedidos    = [];
$disponibles = 0;
foreach ($pedidos_raw as $pedido) {
  $r1 = $bdd->prepare("SELECT id FROM ordenes_pedidos WHERE id_pedido=?");
  $r1->execute([$pedido["id"]]);
  $op = $r1->rowCount();

  $r2 = $bdd->prepare("SELECT op FROM op_pedidos_agrupados WHERE id_pedido=?");
  $r2->execute([$pedido["id"]]);
  $op_agp = $r2->rowCount();

  if ($op == 0 && $op_agp == 0) {
    $pedido['promotor_nombre'] = $pedido["nombres"]." ".$pedido["apellidos"];
    $pedidos[] = $pedido;
    $disponibles++;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Agrupar pedidos</title>
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
    #pa-table thead th {
      background: #1e40af !important; color: #fff !important;
      font-weight: 600; font-size: .80rem; padding: 11px 12px;
      white-space: nowrap; border: none;
    }
    #pa-table tbody tr:nth-child(even) td { background: #eff6ff; }
    #pa-table tbody tr:hover td           { background: #dbeafe !important; }
    #pa-table tbody tr                    { border-left: 3px solid transparent; transition: border-color .15s; }
    #pa-table tbody tr:hover              { border-left-color: #2563eb; }
    #pa-table tbody td { padding: 9px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; vertical-align: middle; }
    .pa-link { color: #2563eb; font-weight: 600; text-decoration: none; }
    .pa-link:hover { text-decoration: underline; }
    .pa-check { width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer; }
    .mc-btn {
      display: inline-flex; align-items: center; gap: 7px;
      padding: 9px 24px; border-radius: 8px; font-size: 14px; font-weight: 600;
      border: none; cursor: pointer; transition: opacity .15s, transform .1s;
    }
    .mc-btn:hover { opacity: .88; transform: translateY(-1px); }
    .mc-btn-blue { background: linear-gradient(135deg, #1d4ed8, #2563eb); color: #fff !important; }
    @page { margin: 15px; size: landscape; }
    @media print {
      a, .left-side-bar, .header, .d-print-none { display: none !important; }
      a[href]:after { content: none !important; }
      body { font-size: 8px; }
      .main-container, .pd-ltr-20, .table-responsive { overflow: visible !important; }
      #pa-table { width: 100% !important; table-layout: auto !important; font-size: 7.5px !important; }
      #pa-table th, #pa-table td { padding: 3px 4px !important; }
      #pa-table thead th { background: #1e40af !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
      #pa-table thead, #pa-table tfoot { display: table-row-group !important; }
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
            <div class="title"><h4>Agrupar pedidos</h4></div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Pedidos</li>
                <li class="breadcrumb-item active">Agrupar</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <!-- Stat -->
      <div class="row mb-3">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#dbeafe;color:#1d4ed8">
              <i class="bi bi-layers"></i>
            </div>
            <div class="stat-info-modern">
              <h3><?= $disponibles ?></h3>
              <p class="stat-label">Pedidos disponibles</p>
              <span class="stat-sub">Sin OP asignada</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Buscador -->
      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="pa-search" placeholder="Buscar por colegio, promotor...">
        </div>
      </div>

      <!-- Tabla -->
      <form action="pedido_agrupado.php" method="POST">
        <input type="hidden" name="periodo" value="<?= htmlspecialchars($_POST["periodo"]) ?>">

        <div class="modern-card">
          <div class="card-head">
            <h5><i class="bi bi-ui-checks mr-2"></i> Pedidos aprobados</h5>
            <span class="lm-count-badge"><?= $disponibles ?> registros</span>
          </div>
          <div class="table-responsive px-2 pb-2">
            <table class="table table-sm table-hover" id="pa-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Fecha</th>
                  <th>Estado</th>
                  <th>Promotor</th>
                  <th>Colegio</th>
                  <th>Seleccionar</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                <tr>
                  <td><?= $pedido['id'] ?></td>
                  <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                  <td><?= htmlspecialchars($pedido['estado']) ?></td>
                  <td><?= htmlspecialchars($pedido['promotor_nombre']) ?></td>
                  <td>
                    <a href="pedido_colegio.php?id_pedido=<?= $pedido['id'] ?>&tp=3" target="_blank" class="pa-link">
                      <?= htmlspecialchars($pedido['colegio']) ?>
                    </a>
                  </td>
                  <td>
                    <input type="checkbox" name="pedidos[]" value="<?= $pedido['id'] ?>" class="pa-check">
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <?php if ($disponibles > 0): ?>
        <div style="display:flex;justify-content:center;margin-top:6px;padding-bottom:20px;">
          <button type="submit" class="mc-btn mc-btn-blue">
            <i class="bi bi-layers"></i> Agrupar seleccionados
          </button>
        </div>
        <?php endif; ?>

      </form>

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
  var table = $('#pa-table').DataTable({
    autoWidth: false,
    paging: false,
    order: [[0, 'desc']],
    columnDefs: [{ orderable: false, targets: 5 }],
    language: {
      lengthMenu:   'Mostrar _MENU_ registros',
      zeroRecords:  'No se encontraron resultados',
      emptyTable:   'No hay pedidos disponibles para agrupar',
      info:         'Mostrando _START_ a _END_ de _TOTAL_ registros',
      infoEmpty:    'Sin registros disponibles',
      infoFiltered: '(filtrado de _MAX_ registros)',
      search:       '',
      paginate: { first:'«', previous:'‹', next:'›', last:'»' }
    },
    initComplete: function () { $('.dataTables_filter').hide(); }
  });

  $('#pa-search').on('keyup', function () { table.search(this.value).draw(); });
});
</script>
</body>
</html>
