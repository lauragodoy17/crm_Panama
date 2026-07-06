<?php
require_once("php/aut.php");
require_once("conexion/bdd.php");

$puede_gestionar = ($_SESSION["tipo"] == 1 || $_SESSION["tipo"] == 2);

$sql = "SELECT l.id, l.isbn, l.libro, l.id_materia, l.id_grado, l.pri_sec, l.precio, l.presupuesto, m.materia, g.grado
        FROM libros l
        JOIN materias m ON l.id_materia = m.id
        JOIN grados g ON l.id_grado = g.id
        ORDER BY g.id, l.libro";
$req = $bdd->prepare($sql);
$req->execute();
$libros = $req->fetchAll();

$total_libros = count($libros);
$total_activos = 0;
foreach ($libros as $libro) {
    if ($libro["presupuesto"] == 1) $total_activos++;
}

$sql_materias = "SELECT id, materia FROM materias ORDER BY materia";
$req_materias = $bdd->prepare($sql_materias);
$req_materias->execute();
$materias = $req_materias->fetchAll();

$sql_grados = "SELECT id, grado FROM grados ORDER BY id";
$req_grados = $bdd->prepare($sql_grados);
$req_grados->execute();
$grados = $req_grados->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Libros</title>
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
    #libros-table input[type="text"],
    #libros-table input[type="number"],
    #libros-table select {
      width: 100%;
      min-width: 90px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 4px 8px;
      font-size: .8rem;
    }
    @media (max-width: 575px) {
      #libros-table_wrapper { overflow-x: auto; }
      #libros-table { min-width: 1050px; }
    }

    /* Botón de guardar (acciones) */
    .btn-save-libro {
      width: 34px; height: 34px; padding: 0; border: none; border-radius: 10px;
      display: inline-flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg,#4f46e5,#4338ca);
      color: #fff; font-size: .95rem; cursor: pointer;
      box-shadow: 0 3px 8px rgba(67,56,202,.28);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .btn-save-libro:hover {
      background: linear-gradient(135deg,#4338ca,#3730a3);
      box-shadow: 0 5px 14px rgba(67,56,202,.4);
      transform: translateY(-1px);
      color: #fff;
    }
    .btn-save-libro:active { transform: translateY(0); box-shadow: 0 2px 6px rgba(67,56,202,.3); }
    .btn-save-libro:focus { outline: none; box-shadow: 0 0 0 3px rgba(79,70,229,.25); }

    /* Botón de eliminar (acciones) */
    .acciones-libro { display: flex; align-items: center; gap: 6px; }
    .btn-delete-libro {
      width: 34px; height: 34px; padding: 0; border: none; border-radius: 10px;
      display: inline-flex; align-items: center; justify-content: center;
      background: linear-gradient(135deg,#ef4444,#dc2626);
      color: #fff; font-size: .95rem; cursor: pointer;
      box-shadow: 0 3px 8px rgba(220,38,38,.28);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .btn-delete-libro:hover {
      background: linear-gradient(135deg,#dc2626,#b91c1c);
      box-shadow: 0 5px 14px rgba(220,38,38,.4);
      transform: translateY(-1px);
      color: #fff;
    }
    .btn-delete-libro:active { transform: translateY(0); box-shadow: 0 2px 6px rgba(220,38,38,.3); }
    .btn-delete-libro:focus { outline: none; box-shadow: 0 0 0 3px rgba(239,68,68,.25); }

    /* Modal: Crear libro */
    #ModalCrearLibro .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(15,23,42,.18); }
    #ModalCrearLibro .ml-header {
      padding: 22px 24px 18px; border-bottom: 1px solid #e2e8f0;
      display: flex; align-items: center; gap: 14px;
    }
    #ModalCrearLibro .ml-icon-badge {
      width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
      background: linear-gradient(135deg,#7c3aed,#4f46e5);
      display: flex; align-items: center; justify-content: center;
    }
    #ModalCrearLibro .ml-icon-badge i { color: #fff; font-size: 1.2rem; }
    #ModalCrearLibro .ml-title { margin: 0; font-size: .98rem; font-weight: 700; color: #0f172a; }
    #ModalCrearLibro .ml-subtitle { margin: 2px 0 0; font-size: .76rem; color: #64748b; }
    #ModalCrearLibro .close {
      font-size: 1.3rem; color: #94a3b8; background: none; border: none; cursor: pointer; padding: 0; line-height: 1;
    }
    #ModalCrearLibro .ml-body { padding: 22px 24px 4px; }
    #ModalCrearLibro .ml-row { display: flex; gap: 14px; }
    #ModalCrearLibro .ml-row > .ml-field { flex: 1 1 0; min-width: 0; }
    #ModalCrearLibro .ml-field { margin-bottom: 18px; }
    #ModalCrearLibro .ml-label {
      font-size: .72rem; font-weight: 700; color: #374151; text-transform: uppercase;
      letter-spacing: .05em; display: block; margin-bottom: 8px;
    }
    #ModalCrearLibro .ml-label .req { color: #ef4444; margin-left: 2px; }
    #ModalCrearLibro .ml-input, #ModalCrearLibro .ml-select {
      width: 100%; padding: 10px 14px; border: 1.5px solid #d1d5db; border-radius: 8px;
      font-size: .875rem; color: #1e293b; background: #f9fafb; outline: none; font-family: inherit;
    }
    #ModalCrearLibro .ml-input:focus, #ModalCrearLibro .ml-select:focus { border-color: #7c3aed; background: #fff; }
    #ModalCrearLibro .ml-select {
      appearance: none; -webkit-appearance: none; cursor: pointer; padding-right: 36px;
      background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'12\' height=\'8\' viewBox=\'0 0 12 8\'%3E%3Cpath d=\'M1 1l5 5 5-5\' stroke=\'%2364748b\' stroke-width=\'1.5\' fill=\'none\' stroke-linecap=\'round\'/%3E%3C/svg%3E');
      background-repeat: no-repeat; background-position: right 14px center;
    }
    #ModalCrearLibro .ml-footer {
      padding: 18px 24px 22px; display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #e2e8f0;
    }
    #ModalCrearLibro .ml-btn-cancel {
      padding: 9px 20px; border-radius: 8px; border: 1.5px solid #d1d5db; background: #fff;
      color: #64748b; font-size: .875rem; font-weight: 600; cursor: pointer;
    }
    #ModalCrearLibro .ml-btn-submit {
      padding: 9px 22px; border-radius: 8px; border: none; color: #fff; font-size: .875rem; font-weight: 600; cursor: pointer;
      background: linear-gradient(135deg,#7c3aed,#4f46e5); box-shadow: 0 4px 12px rgba(79,70,229,.3);
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
            <div class="title"><h4>Libros</h4></div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Libros</li>
              </ol>
            </nav>
          </div>
          <?php if ($puede_gestionar): ?>
          <div class="col-md-6 col-sm-12 text-md-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCrearLibro">
              <i class="bi bi-plus-circle mr-1"></i> Crear libro
            </button>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Tarjetas de estadística -->
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern sblue"><i class="bi bi-book"></i></div>
            <div class="stat-info-modern">
              <h3><?= $total_libros ?></h3>
              <p class="stat-label">Total libros</p>
              <span class="stat-sub">En el catálogo</span>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="stat-card-modern">
            <div class="stat-icon-modern sgreen"><i class="bi bi-check-circle"></i></div>
            <div class="stat-info-modern">
              <h3><?= $total_activos ?></h3>
              <p class="stat-label">Activos en presupuesto</p>
              <span class="stat-sub">Disponibles para presupuestar</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Barra de filtros -->
      <div class="filter-toolbar">
        <div class="ft-search">
          <i class="bi bi-search ft-search-icon"></i>
          <input type="text" id="libros-search" placeholder="Buscar por ISBN, título, materia o grado...">
        </div>
      </div>

      <!-- Tabla -->
      <div class="modern-card">
        <div class="card-head">
          <h5><i class="bi bi-list-ul mr-2"></i> Lista de libros</h5>
        </div>
        <div class="table-responsive px-2 pb-2">
          <table class="table table-sm table-hover" id="libros-table">
            <thead>
              <tr>
                <th>ISBN</th>
                <th>Título</th>
                <th>Materia</th>
                <th>Grado</th>
                <th>Precio $</th>
                <th>Activo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($libros as $libro):
                $form_id = "form-libro-" . $libro["id"];
                $es_serie = ($libro["id_grado"] == 15 || $libro["id_grado"] == 16);
              ?>
              <tr>
                <td><input type="text" name="isbn" value="<?= htmlspecialchars($libro["isbn"]) ?>" form="<?= $form_id ?>" <?= $puede_gestionar ? 'required' : 'disabled' ?>></td>
                <td><input type="text" name="libro" value="<?= htmlspecialchars($libro["libro"]) ?>" form="<?= $form_id ?>" <?= $puede_gestionar ? 'required' : 'disabled' ?>></td>
                <td>
                  <select name="materia" form="<?= $form_id ?>" <?= $puede_gestionar ? 'required' : 'disabled' ?>>
                    <?php foreach ($materias as $materia): ?>
                      <option value="<?= $materia["id"] ?>" <?= $materia["id"] == $libro["id_materia"] ? 'selected' : '' ?>><?= htmlspecialchars($materia["materia"]) ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <select name="grado" form="<?= $form_id ?>" <?= $puede_gestionar ? 'required' : 'disabled' ?>>
                    <?php foreach ($grados as $grado): ?>
                      <option value="<?= $grado["id"] ?>" <?= $grado["id"] == $libro["id_grado"] ? 'selected' : '' ?>><?= htmlspecialchars($grado["grado"]) ?></option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td>
                  <?php if ($es_serie): ?>
                    <span class="text-muted">—</span>
                  <?php else: ?>
                    <input type="number" name="precio" value="<?= htmlspecialchars($libro["precio"]) ?>" step="any" form="<?= $form_id ?>" <?= $puede_gestionar ? 'required' : 'disabled' ?>>
                  <?php endif; ?>
                </td>
                <td>
                  <select name="presupuesto" form="<?= $form_id ?>" <?= $puede_gestionar ? 'required' : 'disabled' ?>>
                    <option value="1" <?= $libro["presupuesto"] == 1 ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= $libro["presupuesto"] == 0 ? 'selected' : '' ?>>No</option>
                  </select>
                </td>
                <td>
                  <?php if ($puede_gestionar): ?>
                    <input type="hidden" name="id_libro" value="<?= $libro["id"] ?>" form="<?= $form_id ?>">
                    <div class="acciones-libro">
                      <button type="submit" class="btn-save-libro" form="<?= $form_id ?>" title="Guardar cambios">
                        <i class="bi bi-check-lg"></i>
                      </button>
                      <button type="button" class="btn-delete-libro" data-form="<?= $form_id ?>" data-titulo="<?= htmlspecialchars($libro["libro"], ENT_QUOTES) ?>" title="Eliminar libro">
                        <i class="bi bi-trash3"></i>
                      </button>
                    </div>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <?php foreach ($libros as $libro): ?>
        <form id="form-libro-<?= $libro["id"] ?>" method="POST" action="php/modificar_libro.php"></form>
      <?php endforeach; ?>

      <!-- Modal: Crear libro -->
      <?php if ($puede_gestionar): ?>
      <div class="modal fade" id="ModalCrearLibro" tabindex="-1" role="dialog" aria-labelledby="ModalCrearLibroLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <form method="POST" action="php/crear_libro.php">

              <div class="ml-header">
                <div class="ml-icon-badge"><i class="bi bi-journal-plus"></i></div>
                <div style="flex:1;">
                  <h5 class="ml-title" id="ModalCrearLibroLabel">Crear libro</h5>
                  <p class="ml-subtitle">Agrega un nuevo título al catálogo</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>

              <div class="ml-body">

                <div class="ml-row">
                  <div class="ml-field">
                    <label class="ml-label" for="isbn">ISBN<span class="req">*</span></label>
                    <input type="text" name="isbn" id="isbn" class="ml-input" required>
                  </div>
                  <div class="ml-field">
                    <label class="ml-label" for="precio">Precio $</label>
                    <input type="number" name="precio" id="precio" class="ml-input" step="any">
                  </div>
                </div>

                <div class="ml-field">
                  <label class="ml-label" for="libro">Título<span class="req">*</span></label>
                  <input type="text" name="libro" id="libro" class="ml-input" required>
                </div>

                <div class="ml-row">
                  <div class="ml-field">
                    <label class="ml-label" for="materia">Materia<span class="req">*</span></label>
                    <select name="materia" id="materia" class="ml-select" required>
                      <option value="">Seleccione</option>
                      <?php foreach ($materias as $materia): ?>
                        <option value="<?= $materia["id"] ?>"><?= htmlspecialchars($materia["materia"]) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="ml-field">
                    <label class="ml-label" for="grado">Grado<span class="req">*</span></label>
                    <select name="grado" id="grado" class="ml-select" required>
                      <option value="">Seleccione</option>
                      <?php foreach ($grados as $grado): ?>
                        <option value="<?= $grado["id"] ?>"><?= htmlspecialchars($grado["grado"]) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="ml-field">
                  <label class="ml-label" for="presupuesto">¿Activo en presupuesto?<span class="req">*</span></label>
                  <select name="presupuesto" id="presupuesto" class="ml-select" required>
                    <option value="">Seleccione</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                  </select>
                </div>

              </div>
              <div class="ml-footer">
                <button type="button" class="ml-btn-cancel" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="ml-btn-submit"><i class="bi bi-check-lg mr-1"></i> Crear libro</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endif; ?>

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
  var table = $('#libros-table').DataTable({
    responsive: { details: false },
    autoWidth: false,
    dom: '<"top"l>rt<"bottom"ip>',
    columnDefs: [
      {
        targets: '_all',
        render: function (data, type, row) {
          if (type === 'filter' || type === 'sort') {
            var $wrap = $('<div>').html(data);
            var $field = $wrap.find('input, select');
            if ($field.length) {
              return $field.is('select') ? $field.find('option:selected').text() : $field.val();
            }
          }
          return data;
        }
      }
    ],
    language: {
      lengthMenu:   "Mostrar _MENU_ registros",
      zeroRecords:  "No se encontraron resultados",
      emptyTable:   "No hay información para mostrar",
      info:         "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty:    "Sin registros disponibles",
      infoFiltered: "(filtrado de _MAX_ registros)",
      search:       "Buscar:",
      paginate: { first: "«", previous: "‹", next: "›", last: "»" }
    }
  });

  $('#libros-search').on('keyup', function () {
    table.search(this.value).draw();
  });

  $('.btn-delete-libro').on('click', function () {
    var formId = $(this).data('form');
    var titulo = $(this).data('titulo');
    inkConfirm({
      title: '¿Eliminar este libro?',
      text: 'Se eliminará "' + titulo + '" del catálogo. Esta acción no se puede deshacer.',
      btnOk: 'Eliminar'
    }, function () {
      var form = document.getElementById(formId);
      form.action = 'php/eliminar_libro.php';
      form.submit();
    });
  });
});
</script>
<script src="src/ink-alerts.js"></script>
</body>
</html>
