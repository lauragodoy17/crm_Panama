<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Resumen solicitud</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css"/>
  <link rel="stylesheet" href="src/plugins/datatables/css/responsive.bootstrap4.min.css"/>
  <link rel="stylesheet" href="vendors/styles/core.css"/>
  <link rel="stylesheet" href="vendors/styles/icon-font.min.css"/>
  <link rel="stylesheet" href="vendors/styles/style.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>

  <style>
    /* ── Modo iframe: ocultar nav y header ────────────────────── */
    html.in-iframe .left-side-bar,
    html.in-iframe .header,
    html.in-iframe .mobile-menu-overlay,
    html.in-iframe .main-container > footer { display: none !important; }
    html.in-iframe .main-container         { padding-left: 0 !important; min-height: unset; }
    html.in-iframe body                    { background: #f8fafc !important; }

    /* ── Contenedor ────────────────────────────────────────────── */
    .vs-wrap { padding: 22px 24px; }

    /* ── Encabezado solicitud ─────────────────────────────────── */
    .vs-header {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .vs-num {
      font-size: 1.1rem;
      font-weight: 800;
      color: #0f172a;
      margin: 0;
    }

    /* Badge estado */
    .vs-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .vs-badge.pendiente  { background:#fef9c3; color:#854d0e; }
    .vs-badge.aprobado   { background:#dcfce7; color:#166534; }
    .vs-badge.entregado  { background:#dbeafe; color:#1e40af; }
    .vs-badge.cobrado    { background:#dbeafe; color:#1e40af; }
    .vs-badge.anulado    { background:#fee2e2; color:#991b1b; }
    .vs-badge.rechazado  { background:#fee2e2; color:#991b1b; }
    .vs-badge.default    { background:#f1f5f9; color:#64748b; }

    /* ── Info cards ──────────────────────────────────────────── */
    .vs-info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 12px;
      margin-bottom: 24px;
    }
    .vs-info-card {
      background: #fff;
      border-radius: 8px;
      padding: 12px 16px;
      box-shadow: 0 1px 4px rgba(15,23,42,.07);
      border-left: 3px solid #6366f1;
    }
    .vs-info-label { font-size: 0.72rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing:.04em; margin: 0 0 3px; }
    .vs-info-val   { font-size: 0.88rem; font-weight: 600; color: #0f172a; margin: 0; }

    /* ── Sección título ─────────────────────────────────────── */
    .vs-section-title {
      font-size: 0.88rem;
      font-weight: 700;
      color: #1e293b;
      margin: 22px 0 10px;
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .vs-section-title i { color: #6366f1; }

    /* ── Tablas ─────────────────────────────────────────────── */
    .vs-table-wrap { border-radius: 10px; overflow: hidden; box-shadow: 0 1px 8px rgba(15,23,42,.09); margin-bottom: 20px; }
    .vs-table { width: 100%; font-size: 0.82rem; border-collapse: collapse; }
    .vs-table thead th {
      background: #f8fafc;
      color: #374151;
      font-weight: 600;
      padding: 10px 12px;
      text-align: left;
      border: none;
      border-bottom: 2px solid #e2e8f0;
      white-space: nowrap;
      font-size: 0.79rem;
    }
    .vs-table thead th.center { text-align: center; }
    .vs-table tbody tr { background: #fff; }
    .vs-table tbody tr:nth-child(even) { background: #f8fafc; }
    .vs-table tbody td { padding: 8px 12px; border-bottom: 1px solid #e2e8f0; color: #1e293b; vertical-align: middle; }
    .vs-table tfoot td { padding: 9px 12px; font-weight: 700; font-size: 0.83rem; background: #f8fafc; color: #374151; border: none; border-top: 2px solid #e2e8f0; }

    /* inputs dentro de tabla */
    .vs-table input[type="text"],
    .vs-table select {
      border: 1px solid #cbd5e0;
      border-radius: 6px;
      padding: 4px 8px;
      font-size: 0.81rem;
      background: #f8fafc;
      outline: none;
      transition: border-color .15s;
      width: 100%;
    }
    .vs-table input[type="text"]:focus,
    .vs-table select:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 2px rgba(99,102,241,.15); }

    /* ── Botones de acción ─────────────────────────────────── */
    .vs-actions { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; justify-content: center; }

    /* ── Contabilizada ──────────────────────────────────────── */
    .vs-contab {
      background: #dcfce7;
      color: #15803d;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 700;
      font-size: 0.9rem;
      text-align: center;
      margin-top: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    /* ── Archivo legalización ───────────────────────────────── */
    .vs-archivo { margin-top: 16px; padding: 14px 16px; background: #fff; border-radius: 8px; box-shadow: 0 1px 4px rgba(15,23,42,.07); }
    .vs-archivo label { font-size: 0.82rem; font-weight: 600; color: #374151; }
  </style>

  <script>
    if (window.self !== window.top) {
      document.documentElement.classList.add('in-iframe');
    }
  </script>
</head>
<body>
<div class="main-container">
<div class="pd-ltr-20 xs-pd-20-10">
<div class="min-height-200px">

<?php
  include("conexion/bdd.php");

  $sql = "SELECT e.estado, s.estado as idestado, s.id, s.fecha,
                 CONCAT(t.nombre, ' ', t.apellido) as solicitante,
                 ca.cargo, s.fecha_entrega, s.id_periodo,
                 s.archivo, s.contab, s.conse, c.colegio, c.codigo
          FROM solicitudes_recursos s
          JOIN estados_pedidos e   ON e.id = s.estado
          JOIN colegios c          ON c.id = s.id_colegio
          LEFT JOIN trabajadores_colegios t ON s.solicitante = t.id
          LEFT JOIN cargos ca      ON ca.id = t.cargo
          WHERE s.id='".$_GET["id"]."'";
  $req = $bdd->prepare($sql); $req->execute();
  $solicitud = $req->fetch();

  $num = ($solicitud["id"] < 221) ? $solicitud["id"] : $solicitud["conse"];

  // Badge estado
  $estado_lower = strtolower($solicitud["estado"]);
  if     (str_contains($estado_lower, 'solicit') || str_contains($estado_lower, 'pendient')) $badge = 'pendiente';
  elseif (str_contains($estado_lower, 'aprob'))                                              $badge = 'aprobado';
  elseif (str_contains($estado_lower, 'entreg'))                                             $badge = 'entregado';
  elseif (str_contains($estado_lower, 'cobr'))                                               $badge = 'cobrado';
  elseif (str_contains($estado_lower, 'anul') || str_contains($estado_lower, 'cerr'))        $badge = 'anulado';
  elseif (str_contains($estado_lower, 'rechaz'))                                             $badge = 'rechazado';
  else                                                                                        $badge = 'default';

  $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
  $dt = date_create($solicitud["fecha"]);
  $fecha_legible = $dt ? (int)$dt->format('j') . ' de ' . $meses[(int)$dt->format('n') - 1] . ' de ' . $dt->format('Y') . ' · ' . $dt->format('g:i a') : $solicitud["fecha"];
?>

<div class="vs-wrap">

  <!-- Encabezado -->
  <div class="vs-header">
    <p class="vs-num"><i class="bi bi-file-earmark-text" style="color:#6366f1;margin-right:6px"></i>Solicitud #<?= htmlspecialchars($num) ?></p>
    <span class="vs-badge <?= $badge ?>"><?= htmlspecialchars($solicitud["estado"]) ?></span>
  </div>

  <!-- Tarjetas de información -->
  <div class="vs-info-grid">
    <div class="vs-info-card">
      <p class="vs-info-label">Colegio</p>
      <p class="vs-info-val"><?= htmlspecialchars($solicitud["colegio"]) ?></p>
    </div>
    <div class="vs-info-card">
      <p class="vs-info-label">Fecha de solicitud</p>
      <p class="vs-info-val"><?= htmlspecialchars($fecha_legible) ?></p>
    </div>
    <div class="vs-info-card">
      <p class="vs-info-label">Solicitante</p>
      <p class="vs-info-val"><?= htmlspecialchars($solicitud["solicitante"].' ('.$solicitud["cargo"].')') ?></p>
    </div>
    <div class="vs-info-card">
      <p class="vs-info-label">Fecha de entrega</p>
      <p class="vs-info-val"><?= htmlspecialchars($solicitud["fecha_entrega"]) ?></p>
    </div>
  </div>

  <!-- Áreas comprometidas -->
  <p class="vs-section-title"><i class="bi bi-grid-3x3-gap"></i> Áreas comprometidas</p>
  <div class="vs-table-wrap">
    <table class="vs-table">
      <thead>
        <tr>
          <th>Área / Materia</th>
          <th class="center">Preescolar</th>
          <th class="center">Primaria</th>
          <th class="center">Bachillerato</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT m.materia, a.preescolar, a.primaria, a.bachillerato
                  FROM areas_recursos a JOIN materias m ON a.materia=m.id
                  WHERE a.id_solicitud='".$solicitud["id"]."'";
          $req = $bdd->prepare($sql); $req->execute();
          foreach ($req->fetchAll() as $area):
        ?>
        <tr>
          <td><?= htmlspecialchars($area["materia"]) ?></td>
          <td style="text-align:center"><?= htmlspecialchars($area["preescolar"]) ?></td>
          <td style="text-align:center"><?= htmlspecialchars($area["primaria"]) ?></td>
          <td style="text-align:center"><?= htmlspecialchars($area["bachillerato"]) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Recursos solicitados -->
  <p class="vs-section-title"><i class="bi bi-box-seam"></i> Recursos solicitados</p>
  <div class="vs-table-wrap">
  <div style="overflow-x:auto;">
    <table class="vs-table">
      <thead>
        <tr>
          <th>Recurso</th>
          <th>Tipo</th>
          <th>Categoría</th>
          <th>Presupuesto</th>
          <th>Tipo recurso entregado</th>
          <th>Valor entregado</th>
          <th>Fecha entrega</th>
          <th>Legalización</th>
        </tr>
      </thead>
      <tbody>
        <?php
          if ($solicitud["idestado"] == 1) {
            echo "<form action='php/modificar_solicitud.php?tipo=1' method='POST' id='form_a'>";
          } else {
            echo "<form action='php/modificar_solicitud.php?tipo=2' method='POST' id='form_a'>";
          }

          $sql = "SELECT t.tipo, r.id, r.recurso, r.presupuesto, r.tipo_e, r.valor_e, r.fecha_e, r.legaliza, c.categoria
                  FROM recursos_solicitados r
                  JOIN tipos_recursos t     ON t.id = r.tipo
                  JOIN categoria_recursos c ON c.id = r.categoria
                  WHERE r.id_solicitud='".$solicitud["id"]."'";
          $req = $bdd->prepare($sql); $req->execute();
          $recursos = $req->fetchAll();
          $t_presup = []; $t_legaliza = [];

          foreach ($recursos as $recurso):
        ?>
        <tr>
          <td><?= htmlspecialchars($recurso["recurso"]) ?></td>
          <td><?= htmlspecialchars($recurso["tipo"]) ?></td>
          <td><?= htmlspecialchars($recurso["categoria"]) ?></td>
          <td>
            <?php if ($solicitud["idestado"] == 1): ?>
              <input type="text" id="presup<?= $recurso["id"] ?>" value="<?= $recurso["presupuesto"] ?>">
              <input type="hidden" id="i_presup<?= $recurso["id"] ?>" name="i_presup[]">
              <script>
                $('#i_presup<?= $recurso["id"] ?>').val('<?= $recurso["presupuesto"] ?>');
                $('#presup<?= $recurso["id"] ?>').keyup(function(){
                  $('#i_presup<?= $recurso["id"] ?>').val(<?= $recurso["id"] ?>+'/'+parseInt($(this).val()));
                });
              </script>
            <?php else: ?>
              $ <?= number_format($recurso["presupuesto"], 0, ',', '.') ?>
            <?php endif; ?>
          </td>
          <?php
            $can_edit = in_array($_SESSION['tipo'], [1,2,7,9]);
            if ($can_edit):
              if ($solicitud["idestado"] == 2):
                $sql2 = "SELECT id, tipo FROM tipos_recursos WHERE categoria=2 OR categoria=3";
                $req2 = $bdd->prepare($sql2); $req2->execute();
                $tipos_e = $req2->fetchAll();
          ?>
          <td>
            <select id="tipo_e<?= $recurso["id"] ?>">
              <option value="">Seleccionar</option>
              <?php foreach ($tipos_e as $te): ?>
              <option value="<?= $te["id"] ?>" <?= $recurso["tipo_e"]==$te["id"] ? 'selected' : '' ?>><?= htmlspecialchars($te["tipo"]) ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td><input type="text" id="valor_e<?= $recurso["id"] ?>" value="<?= $recurso["valor_e"] ?>"></td>
          <td><input type="date" id="fecha_e<?= $recurso["id"] ?>" value="<?= $recurso["fecha_e"] ?>"></td>
          <td></td>
          <?php elseif ($solicitud["idestado"] == 4): ?>
          <?php
                $sql2 = "SELECT tipo FROM tipos_recursos WHERE id='".$recurso["tipo_e"]."'";
                $req2 = $bdd->prepare($sql2); $req2->execute();
                $tipo_e_row = $req2->fetch() ?: [];
          ?>
          <td><?= htmlspecialchars($tipo_e_row["tipo"] ?? '') ?></td>
          <td>$ <?= number_format($recurso["valor_e"], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($recurso["fecha_e"]) ?></td>
          <td><input type="text" id="legaliza<?= $recurso["id"] ?>" value="<?= $recurso["legaliza"] ?>"></td>
          <?php else: ?>
          <?php
                $sql2 = "SELECT tipo FROM tipos_recursos WHERE id='".$recurso["tipo_e"]."'";
                $req2 = $bdd->prepare($sql2); $req2->execute();
                $tipo_e_row = $req2->fetch() ?: [];
          ?>
          <td><?= htmlspecialchars($tipo_e_row["tipo"] ?? '') ?></td>
          <td>$ <?= number_format($recurso["valor_e"], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($recurso["fecha_e"]) ?></td>
          <td>$ <?= number_format($recurso["legaliza"], 0, ',', '.') ?></td>
          <?php endif; ?>
          <?php else: ?>
          <?php
            $sql2 = "SELECT tipo FROM tipos_recursos WHERE id='".$recurso["tipo_e"]."'";
            $req2 = $bdd->prepare($sql2); $req2->execute();
            $tipo_e_row = $req2->fetch() ?: [];
          ?>
          <td><?= htmlspecialchars($tipo_e_row["tipo"] ?? '') ?></td>
          <td>$ <?= number_format($recurso["valor_e"], 0, ',', '.') ?></td>
          <td><?= htmlspecialchars($recurso["fecha_e"]) ?></td>
          <td>$ <?= number_format($recurso["legaliza"], 0, ',', '.') ?></td>
          <?php endif; ?>
        </tr>
        <?php
          $t_presup[]   = $recurso["presupuesto"];
          $t_legaliza[] = $recurso["legaliza"];
          echo "<input type='hidden' id='i_legaliza".$recurso["id"]."' name='i_legaliza[]'>";
          echo "<input type='hidden' id='i_entrega".$recurso["id"]."'  name='i_entrega[]'>";
          if ($can_edit && $solicitud["idestado"] == 2):
          echo "<script>
            $('#tipo_e".$recurso["id"]."').change(function(){
              $('#i_entrega".$recurso["id"]."').val(".$recurso["id"]."+'|'+$('#tipo_e".$recurso["id"]."').val()+'|'+parseInt($('#valor_e".$recurso["id"]."').val())+'|'+$('#fecha_e".$recurso["id"]."').val());
            });
            $('#valor_e".$recurso["id"]."').keyup(function(){
              $('#i_entrega".$recurso["id"]."').val(".$recurso["id"]."+'|'+$('#tipo_e".$recurso["id"]."').val()+'|'+parseInt($('#valor_e".$recurso["id"]."').val())+'|'+$('#fecha_e".$recurso["id"]."').val());
            });
            $('#fecha_e".$recurso["id"]."').blur(function(){
              $('#i_entrega".$recurso["id"]."').val(".$recurso["id"]."+'|'+$('#tipo_e".$recurso["id"]."').val()+'|'+parseInt($('#valor_e".$recurso["id"]."').val())+'|'+$('#fecha_e".$recurso["id"]."').val());
            });
          </script>";
          endif;
          if ($can_edit && $solicitud["idestado"] == 4):
          echo "<script>
            $('#legaliza".$recurso["id"]."').keyup(function(){
              $('#i_legaliza".$recurso["id"]."').val(".$recurso["id"]."+'|'+parseInt($(this).val()));
            });
          </script>";
          endif;
        endforeach;
        ?>
      </tbody>
      <tfoot>
        <tr>
          <td>Total</td>
          <td></td><td></td>
          <td>$ <?= number_format(array_sum($t_presup), 0, ',', '.') ?></td>
          <td></td><td></td><td></td>
          <td>$ <?= number_format(array_sum($t_legaliza), 0, ',', '.') ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
  </div>

  <!-- Botones de acción -->
  <input type="hidden" name="solicitud" value="<?= $solicitud["id"] ?>">

  <div class="vs-actions">
    <?php if ($solicitud["idestado"] == 2 && in_array($_SESSION['tipo'], [1,2,7,9])): ?>
      <button class="btn btn-primary btn-sm" id="entregar"><i class="bi bi-truck"></i> Entregar</button>
    <?php endif; ?>

    <?php if (in_array($solicitud["idestado"], [2,4]) && in_array($_SESSION['tipo'], [1,2,7,9])): ?>
      <button class="btn btn-success btn-sm"><i class="bi bi-patch-check"></i> Legalizar</button>
    <?php endif; ?>

    <?php if ($solicitud["idestado"] == 1): ?>
      <button class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i> Modificar</button>
    <?php endif; ?>

    <?php if (in_array($_SESSION['tipo'], [1,9]) && $solicitud["contab"] == 0 && $solicitud["idestado"] == 4): ?>
      <a class="btn btn-warning btn-sm" href="php/accion_solicitudes.php?solicitud=<?= $solicitud["id"] ?>&contab=1&cod_colegio=<?= $solicitud["codigo"] ?>&periodo=<?= $solicitud["id_periodo"] ?>">
        <i class="bi bi-calculator"></i> Contabilizar
      </a>
    <?php endif; ?>

    <?php if (in_array($_SESSION['tipo'], [1]) || $_SESSION['id']==19): ?>
      <?php if ($solicitud["idestado"] == 1): ?>
        <a class="btn btn-success btn-sm" href="php/accion_solicitudes.php?solicitud=<?= $solicitud["id"] ?>&aprobar=1&cod_colegio=<?= $solicitud["codigo"] ?>&periodo=<?= $solicitud["id_periodo"] ?>">
          <i class="bi bi-check-circle"></i> Aprobar
        </a>
        <a class="btn btn-danger btn-sm" href="php/accion_solicitudes.php?solicitud=<?= $solicitud["id"] ?>&rechazar=1&cod_colegio=<?= $solicitud["codigo"] ?>&periodo=<?= $solicitud["id_periodo"] ?>">
          <i class="bi bi-x-circle"></i> Rechazar
        </a>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <?php echo "</form>"; ?>

  <!-- Archivo de legalización -->
  <?php if ($solicitud["idestado"] == 4 && $solicitud["archivo"] == ""): ?>
  <div class="vs-archivo">
    <form action="php/archivo_solicitud.php" method="POST" enctype="multipart/form-data">
      <label class="control-label"><i class="bi bi-paperclip" style="color:#6366f1;margin-right:4px"></i> Archivo de legalización</label>
      <div class="d-flex gap-2 align-items-center mt-2">
        <input type="file" name="archivo" id="archivo" class="form-control form-control-sm" required/>
        <input type="hidden" name="solicitud" value="<?= $solicitud["id"] ?>">
        <button class="btn btn-warning btn-sm" style="white-space:nowrap"><i class="bi bi-upload"></i> Subir</button>
      </div>
    </form>
  </div>
  <?php elseif ($solicitud["archivo"] != ""): ?>
  <?php
    $n_archivo = explode('_', $solicitud["archivo"]);
  ?>
  <div class="vs-archivo">
    <span style="font-size:.82rem;font-weight:600;color:#374151"><i class="bi bi-paperclip" style="color:#6366f1;margin-right:4px"></i> Archivo de legalización:</span>
    <a href="adjuntos_atenc/<?= $solicitud["archivo"] ?>" download="<?= $n_archivo[1] ?>" style="margin-left:8px;font-size:.82rem">
      <?= htmlspecialchars($n_archivo[1]) ?>
    </a>
  </div>
  <?php endif; ?>

  <!-- Contabilizada -->
  <?php if ($solicitud["contab"] == 1): ?>
  <div class="vs-contab"><i class="bi bi-check2-circle"></i> Contabilizada</div>
  <?php endif; ?>

</div><!-- /.vs-wrap -->

</div></div></div>

<script src="vendors/scripts/core.js"></script>
<script src="vendors/scripts/script.min.js"></script>
<script src="vendors/scripts/process.js"></script>
<script src="vendors/scripts/layout-settings.js"></script>
<script>
  $("#entregar").click(function(){
    $("#form_a").attr("action", "php/entregar_solicitud.php");
  });
</script>
</body>
</html>
