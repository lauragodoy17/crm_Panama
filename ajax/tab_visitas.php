<?php
  require_once("../php/aut.php");
  include("../conexion/bdd.php");

  $sql = "SELECT pt.id, pt.start, pt.end, pt.resultado,
                 o.objetivo,
                 CONCAT(u.nombres, ' ', u.apellidos) AS promotor,
                 z.zona,
                 v.observaciones, v.fecha_llegada, v.fecha AS fecha_salida, v.efectiva
          FROM plan_trabajo pt
          LEFT JOIN objetivos o ON o.id = pt.id_objetivo
          LEFT JOIN usuarios  u ON u.id = pt.id_promotor
          LEFT JOIN zonas     z ON z.codigo = u.cod_zona
          LEFT JOIN visitas   v ON v.id_plan_trabajo = pt.id
          WHERE pt.id_colegio = '".$_GET['colegio']."'
          ORDER BY pt.start DESC";
  $req = $bdd->prepare($sql);
  $req->execute();
  $visitas = $req->fetchAll();

  $anios = [];
  foreach ($visitas as $v) {
    $ts = strtotime($v['start']);
    $anio_v = $ts !== false ? (int) date('Y', $ts) : 0;
    if ($anio_v >= 2000) {
      $anios[$anio_v] = true;
    }
  }
  $anios = array_keys($anios);
  rsort($anios);
  $anio_default = $anios[0] ?? date('Y');
?>

<style>
  .vis-wrap * { box-sizing: border-box; }

  .vis-header {
    display:flex; justify-content:space-between; align-items:center;
    background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px;
    padding:10px 16px; margin-bottom:16px; flex-wrap:wrap; gap:10px;
  }
  .vis-title { font-size:14px; font-weight:700; color:#111827; display:flex; align-items:center; gap:8px; margin:0; }
  .vis-title i { color:#4361ee; font-size:16px; }
  .vis-count {
    background:#e0e7ff; color:#4361ee; font-size:12px; font-weight:700;
    padding:2px 9px; border-radius:20px;
  }
  .vis-year { display:flex; align-items:center; gap:8px; font-size:13px; color:#374151; }
  .vis-year select {
    border:1px solid #d1d5db; border-radius:6px; padding:5px 10px; font-size:13px; color:#111827;
  }

  .vis-table-wrap { overflow-x:auto; }
  table.vis-table { width:100%; border-collapse:collapse; font-size:13px; }
  table.vis-table thead th {
    text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase;
    background:#f8fafc; padding:10px 14px; border-bottom:1px solid #e5e7eb; white-space:nowrap;
  }
  table.vis-table tbody td {
    padding:12px 14px; border-bottom:1px solid #f1f5f9; vertical-align:middle;
  }
  table.vis-table tbody tr:hover { background:#fafbff; }
  .vis-zona { font-weight:700; color:#374151; font-size:12.5px; }
  .vis-fecha { color:#6b7280; }
  .vis-obs {
    max-width:260px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    color:#374151; display:inline-block; vertical-align:middle;
  }

  .vis-badge {
    display:inline-flex; align-items:center; gap:4px;
    font-size:11.5px; font-weight:700; padding:4px 11px; border-radius:20px; white-space:nowrap;
  }
  .vis-badge.ok      { background:#dcfce7; color:#16a34a; }
  .vis-badge.pending { background:#f3f4f6; color:#6b7280; }

  .vis-btn-detalle {
    border:1px solid #4361ee; background:#fff; color:#4361ee;
    padding:5px 12px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:600;
    white-space:nowrap;
  }
  .vis-btn-detalle:hover { background:#4361ee; color:#fff; }

  .vis-empty {
    text-align:center; padding:30px 20px; color:#9ca3af; font-size:13px;
    background:#fafafa; border:1px dashed #e5e7eb; border-radius:8px;
  }
  .vis-empty i { font-size:28px; display:block; margin-bottom:8px; color:#d1d5db; }

  /* Modal de detalle */
  .vis-overlay {
    position:fixed; inset:0; background:rgba(15,23,42,.45);
    z-index:99998; display:flex; align-items:center; justify-content:center;
    opacity:0; pointer-events:none; transition:opacity .2s;
  }
  .vis-overlay.open { opacity:1; pointer-events:all; }
  .vis-modal {
    background:#fff; border-radius:14px; padding:24px 28px;
    max-width:460px; width:92%; box-shadow:0 10px 40px rgba(15,23,42,.2);
    transform:scale(.95); transition:transform .2s;
  }
  .vis-overlay.open .vis-modal { transform:scale(1); }
  .vis-modal-title { font-size:15px; font-weight:700; color:#0f172a; margin:0 0 14px; display:flex; align-items:center; gap:8px; }
  .vis-modal-row { margin-bottom:10px; }
  .vis-modal-label { font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; }
  .vis-modal-val { font-size:13.5px; color:#111827; margin-top:2px; }
  .vis-modal-close {
    margin-top:16px; width:100%; background:#f1f5f9; color:#475569; border:none;
    border-radius:8px; padding:9px; font-size:13px; cursor:pointer; font-weight:600;
  }
</style>

<div class="vis-wrap pd-20">

  <div class="vis-header">
    <p class="vis-title">
      <i class="bi bi-signpost-2"></i> Trazabilidad de visitas
      <span class="vis-count" id="vis-count"><?= count($visitas) ?></span>
    </p>
    <?php if (!empty($anios)): ?>
    <div class="vis-year">
      <span>Año:</span>
      <select id="vis-year-filter">
        <?php foreach ($anios as $a): ?>
          <option value="<?= $a ?>" <?= $a == $anio_default ? 'selected' : '' ?>><?= $a ?></option>
        <?php endforeach; ?>
        <option value="todos">Todos</option>
      </select>
    </div>
    <?php endif; ?>
  </div>

  <?php if (empty($visitas)): ?>
    <div class="vis-empty">
      <i class="bi bi-signpost-2"></i>
      No hay visitas registradas para este período.
    </div>
  <?php else: ?>
  <div class="vis-table-wrap">
    <table class="vis-table">
      <thead>
        <tr>
          <th>Zona</th>
          <th>Fecha planificada</th>
          <th>Objetivo</th>
          <th>Resultado</th>
          <th>Promotor</th>
          <th>Observaciones</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($visitas as $visita):
          $ejecutada = $visita['resultado'] == 1;
          $ts_row    = strtotime($visita['start']);
          $anio_row  = ($ts_row !== false && (int) date('Y', $ts_row) >= 2000) ? (int) date('Y', $ts_row) : 0;
          $fecha_txt = $ts_row !== false ? date('d/m/Y H:i', $ts_row) : 'Sin fecha';
          $detalle = [
            'zona'       => $visita['zona'] ?: '—',
            'fecha'      => $fecha_txt,
            'objetivo'   => $visita['objetivo'] ?: 'Sin objetivo',
            'resultado'  => $ejecutada ? 'Ejecutada' : 'Pendiente',
            'promotor'   => $visita['promotor'] ?: '—',
            'llegada'    => $visita['fecha_llegada'] ? date('d/m/Y H:i', strtotime($visita['fecha_llegada'])) : '—',
            'salida'     => $visita['fecha_salida'] ? date('d/m/Y H:i', strtotime($visita['fecha_salida'])) : '—',
            'observaciones' => $visita['observaciones'] ?: 'Sin observaciones registradas',
          ];
        ?>
        <tr class="vis-row" data-anio="<?= $anio_row ?>">
          <td class="vis-zona"><?= htmlspecialchars($visita['zona'] ?: '—') ?></td>
          <td class="vis-fecha"><?= htmlspecialchars($fecha_txt) ?></td>
          <td><?= htmlspecialchars($visita['objetivo'] ?: 'Sin objetivo') ?></td>
          <td>
            <span class="vis-badge <?= $ejecutada ? 'ok' : 'pending' ?>">
              <i class="bi <?= $ejecutada ? 'bi-check-circle-fill' : 'bi-clock-history' ?>"></i>
              <?= $ejecutada ? 'Ejecutada' : 'Pendiente' ?>
            </span>
          </td>
          <td><?= htmlspecialchars($visita['promotor'] ?: '—') ?></td>
          <td><span class="vis-obs" title="<?= htmlspecialchars($detalle['observaciones']) ?>"><?= htmlspecialchars($detalle['observaciones']) ?></span></td>
          <td>
            <button type="button" class="vis-btn-detalle vis-ver-detalle" data-detalle='<?= htmlspecialchars(json_encode($detalle), ENT_QUOTES) ?>'>
              <i class="bi bi-eye"></i> Ver detalle
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

</div>

<!-- Modal de detalle -->
<div class="vis-overlay" id="vis-overlay">
  <div class="vis-modal">
    <p class="vis-modal-title"><i class="bi bi-signpost-2 text-primary"></i> Detalle de la visita</p>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Zona</div>
      <div class="vis-modal-val" id="vis-m-zona">—</div>
    </div>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Fecha planificada</div>
      <div class="vis-modal-val" id="vis-m-fecha">—</div>
    </div>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Objetivo</div>
      <div class="vis-modal-val" id="vis-m-objetivo">—</div>
    </div>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Resultado</div>
      <div class="vis-modal-val" id="vis-m-resultado">—</div>
    </div>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Promotor</div>
      <div class="vis-modal-val" id="vis-m-promotor">—</div>
    </div>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Llegada / Salida</div>
      <div class="vis-modal-val" id="vis-m-horas">—</div>
    </div>
    <div class="vis-modal-row">
      <div class="vis-modal-label">Observaciones</div>
      <div class="vis-modal-val" id="vis-m-obs">—</div>
    </div>
    <button class="vis-modal-close" id="vis-modal-close">Cerrar</button>
  </div>
</div>

<script>
  function visAplicarFiltro() {
    var anio = $('#vis-year-filter').val();
    var visibles = 0;
    $('.vis-row').each(function () {
      var mostrar = (anio === 'todos' || $(this).data('anio') == anio);
      $(this).toggle(mostrar);
      if (mostrar) visibles++;
    });
    $('#vis-count').text(visibles);
  }

  $('#vis-year-filter').on('change', visAplicarFiltro);
  visAplicarFiltro();

  $('.vis-ver-detalle').on('click', function () {
    var d = $(this).data('detalle');
    $('#vis-m-zona').text(d.zona);
    $('#vis-m-fecha').text(d.fecha);
    $('#vis-m-objetivo').text(d.objetivo);
    $('#vis-m-resultado').text(d.resultado);
    $('#vis-m-promotor').text(d.promotor);
    $('#vis-m-horas').text(d.llegada + '  →  ' + d.salida);
    $('#vis-m-obs').text(d.observaciones);
    $('#vis-overlay').addClass('open');
  });

  $('#vis-modal-close, #vis-overlay').on('click', function (e) {
    if (e.target === this) $('#vis-overlay').removeClass('open');
  });
</script>
