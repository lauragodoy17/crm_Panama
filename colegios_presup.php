<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Zonificación Presupuesto</title>
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
    .dt-periodo-sel { min-width: 100px; }
    @media (max-width: 575px) {
      #cp-table_wrapper { overflow-x: auto; }
      #cp-table { min-width: 750px; }
      #cp-table td, #cp-table th { display: table-cell !important; }
    }
  </style>
</head>
<body>

<?php include("template/nav_side.php"); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

      <!-- Encabezado -->
      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-md-6 col-sm-12">
            <div class="title"><h4>Zonificación — Presupuesto</h4></div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="ver_colegios.php">Zonificación</a></li>
                <li class="breadcrumb-item active" aria-current="page">Ver presupuesto</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <!-- Tarjeta de estadística -->
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern sblue"><i class="bi bi-building"></i></div>
            <div class="stat-info-modern">
              <h3 id="cp-stat-total">—</h3>
              <p class="stat-label">Total colegios</p>
              <span class="stat-sub">En tu zonificación</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Barra de filtros -->
      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="cp-search" placeholder="Buscar por colegio o DANE (mín. 4 caracteres)...">
        </div>
        <button class="ft-btn ft-apply" id="cp-btn-apply">
          <i class="bi bi-funnel"></i> Filtrar
        </button>
        <button class="ft-btn ft-clear" id="cp-btn-clear">
          <i class="bi bi-x-circle"></i> Limpiar
        </button>
      </div>

      <!-- Tabla -->
      <div class="modern-card">
        <div class="card-head">
          <h5><i class="bi bi-list-ul mr-2"></i> Lista de colegios</h5>
        </div>
        <div class="table-responsive px-2 pb-2">
          <table class="table table-sm table-hover" id="cp-table">
            <thead>
              <tr>
                <th>Colegio</th>
                <?php if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 7 || $_SESSION['tipo'] == 10 || $_SESSION['tipo'] == 5): ?>
                  <th>Empresa</th>
                  <th>Zona</th>
                  <th>Responsable</th>
                <?php elseif ($_SESSION['tipo'] == 6): ?>
                  <th>Zona</th>
                  <th>Responsable</th>
                <?php endif; ?>
                <th>Dirección</th>
                <th>Status</th>
                <th>Presupuesto</th>
                <th>Periodo</th>
                <th>Acciones</th>
              </tr>
            </thead>
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

  function avatarRender(data, type, row) {
    if (type !== 'display') return data;
    var txt    = $('<div>').html(data).text();
    var words  = txt.trim().split(/\s+/).filter(function(w){ return w.length > 2; });
    var inits  = words.slice(0, 2).map(function(w){ return w[0].toUpperCase(); }).join('');
    if (!inits) inits = txt.substring(0, 2).toUpperCase();
    var dane   = row.dane ? '<div class="dt-dane-sub">' + row.dane + '</div>' : '';
    return '<div class="dt-cole-wrap">'
         + '<span class="school-avatar">' + inits + '</span>'
         + '<div class="dt-cole-info">' + txt + dane + '</div>'
         + '</div>';
  }

  var table = $('#cp-table').dataTable({
    processing: true,
    serverSide: true,
    responsive: { details: false },
    autoWidth:  false,
    dom:        '<"top"l>rt<"bottom"ip>',
    ajax: {
      url: "php/colegios_tabla_presup.php",
      data: function (d) {
        d.search_val = $('#cp-search').val();
      }
    },
    <?php if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 7 || $_SESSION['tipo'] == 10 || $_SESSION['tipo'] == 5): ?>
    columns: [
      { data: "colegio",     responsivePriority: 1, render: avatarRender, className: "dt-colegio all" },
      { data: "empresa",     responsivePriority: 6, className: "dt-empresa" },
      { data: "zona",        responsivePriority: 3, className: "dt-zona" },
      { data: "responsable", responsivePriority: 2, className: "dt-resp" },
      { data: "direccion",   responsivePriority: 7, className: "dt-dir" },
      { data: "status",      responsivePriority: 5, className: "dt-status" },
      { data: "presupuesto", responsivePriority: 4, className: "dt-ppto" },
      { data: "periodo",     responsivePriority: 1, orderable: false, className: "dt-periodo all" },
      { data: "acciones",    responsivePriority: 1, orderable: false, className: "dt-acc all" },
    ],
    <?php elseif ($_SESSION['tipo'] == 6): ?>
    columns: [
      { data: "colegio",     responsivePriority: 1, render: avatarRender, className: "dt-colegio all" },
      { data: "zona",        responsivePriority: 3, className: "dt-zona" },
      { data: "responsable", responsivePriority: 2, className: "dt-resp" },
      { data: "direccion",   responsivePriority: 6, className: "dt-dir" },
      { data: "status",      responsivePriority: 5, className: "dt-status" },
      { data: "presupuesto", responsivePriority: 4, className: "dt-ppto" },
      { data: "periodo",     responsivePriority: 1, orderable: false, className: "dt-periodo all" },
      { data: "acciones",    responsivePriority: 1, orderable: false, className: "dt-acc all" },
    ],
    <?php else: ?>
    columns: [
      { data: "colegio",     responsivePriority: 1, render: avatarRender, className: "dt-colegio all" },
      { data: "direccion",   responsivePriority: 5, className: "dt-dir" },
      { data: "status",      responsivePriority: 4, className: "dt-status" },
      { data: "presupuesto", responsivePriority: 3, className: "dt-ppto" },
      { data: "periodo",     responsivePriority: 1, orderable: false, className: "dt-periodo all" },
      { data: "acciones",    responsivePriority: 1, orderable: false, className: "dt-acc all" },
    ],
    <?php endif; ?>
    language: {
      lengthMenu:   "Mostrar _MENU_ registros",
      zeroRecords:  "No se encontraron resultados",
      emptyTable:   "No hay información para mostrar",
      info:         "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty:    "Sin registros disponibles",
      infoFiltered: "(filtrado de _MAX_ registros)",
      search:       "Buscar:",
      processing:   '<div class="dt-loading"><i class="bi bi-arrow-repeat"></i> Cargando...</div>',
      paginate: { first: "«", previous: "‹", next: "›", last: "»" }
    },
    initComplete: function () {
      var api = this.api();
      $('#cp-search').on('keyup', function () {
        var val = this.value;
        if (val.length >= 4 || val.length === 0) {
          api.search(val).draw();
        }
      });
      $('#cp-stat-total').text(api.page.info().recordsTotal.toLocaleString('es-CO'));
    }
  });

  table.api().on('draw', function () {
    $('#cp-stat-total').text(table.api().page.info().recordsTotal.toLocaleString('es-CO'));
  });

  $('#cp-btn-apply').on('click', function () {
    table.api().draw();
  });

  $('#cp-btn-clear').on('click', function () {
    $('#cp-search').val('');
    table.api().search('').draw();
  });

  $('#cp-table').on('click', '.btn-ir-presup', function (e) {
    e.preventDefault();
    var id      = $(this).data('id');
    var codigo  = $(this).data('codigo');
    var periodo = $('#periodo' + id).val();
    window.location.href = 'colegio.php?codigo=' + codigo + '&periodo=' + periodo + '&tab=presupuesto';
  });

  $('#cp-table').on('click', '.linkcole', function (e) {
    e.preventDefault();
    var id      = $(this).attr('id');
    var codigo  = $(this).data('codigo');
    var periodo = $('#periodo' + id).val();
    window.location.href = 'colegio.php?codigo=' + codigo + '&periodo=' + periodo + '&tab=presupuesto';
  });

  $('#cp-table').on('click', '.eliminar', function () {
    var cod = $(this).data('codigo');
    if (confirm('¿Seguro que desea eliminar este colegio?')) {
      window.location = 'php/eliminar_colegio.php?codigo=' + cod;
    }
  });
});
</script>
<script src="src/ink-alerts.js"></script>
</body>
</html>
