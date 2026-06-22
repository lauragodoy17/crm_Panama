<?php
  require_once("../php/aut.php");
  include("../conexion/bdd.php");

  // ── Máximo de paralelos ───────────────────────────────────────────────
  $aviso    = false;
  $periodo_po = $_GET['periodo'];

  $req1 = $bdd->prepare("SELECT MAX(paralelos) as n FROM grados_paralelos
                         WHERE id_colegio=? AND id_periodo=? AND alumnos > 0");
  $req1->execute([$_GET['colegio'], $_GET['periodo']]);
  $n1 = (int)$req1->fetchColumn();

  if ($n1 < 1) {
    $req_pa = $bdd->prepare("SELECT id FROM periodos
                             WHERE id_calendario=? ORDER BY id DESC LIMIT 1 OFFSET 1");
    $req_pa->execute([$_GET['id_calendario']]);
    $pa_id = $req_pa->fetchColumn();
    if ($pa_id) {
      $periodo_po = $pa_id;
      $req2 = $bdd->prepare("SELECT MAX(paralelos) as n FROM grados_paralelos
                             WHERE id_colegio=? AND id_periodo=?");
      $req2->execute([$_GET['colegio'], $pa_id]);
      $n2 = (int)$req2->fetchColumn();
      $max_par = max(1, $n2);
      if ($n2 >= 1) $aviso = true;
    } else {
      $max_par = 1;
    }
  } else {
    $max_par = $n1;
  }

  // ── Grados ────────────────────────────────────────────────────────────
  $grados_ids = [1,2,3,4,5,6,7,8,9,10,11,12,13,14];
  $nivel_map  = [
    1=>'pre', 2=>'pre', 3=>'pre',
    4=>'prim',5=>'prim',6=>'prim',7=>'prim',8=>'prim',
    9=>'bach',10=>'bach',11=>'bach',12=>'bach',13=>'bach',14=>'bach'
  ];

  $req_g = $bdd->prepare("SELECT id, grado FROM grados
                         WHERE id IN (".implode(',', $grados_ids).")
                         ORDER BY id ASC");
  $req_g->execute();
  $grados_list = $req_g->fetchAll(PDO::FETCH_ASSOC);

  // ── Valores almacenados ───────────────────────────────────────────────
  $req_v = $bdd->prepare("SELECT id_grado, paralelos, alumnos FROM grados_paralelos
                          WHERE id_colegio=? AND id_periodo=?");
  $req_v->execute([$_GET['colegio'], $periodo_po]);
  $vals = [];
  foreach ($req_v->fetchAll() as $row)
    $vals[$row['id_grado']][$row['paralelos']] = (int)$row['alumnos'];

  $letras_php = ['A','B','C','D','E','F','G','H'];
?>

<style>
  /* ── Wrapper ─────────────────────────────────────────────────── */
  .pob-wrap { padding: 24px; }

  /* ── Encabezado ──────────────────────────────────────────────── */
  .pob-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; flex-wrap: wrap;
    gap: 12px; margin-bottom: 20px;
  }
  .pob-title { font-size:1.05rem; font-weight:700; color:#0f172a; margin:0 0 2px 0; }
  .pob-title i { color:#6c63ff; margin-right:6px; }
  .pob-subtitle { font-size:0.82rem; color:#718096; margin:0; }
  .pob-actions { display:flex; gap:8px; flex-wrap:wrap; }

  /* ── Aviso ───────────────────────────────────────────────────── */
  .pob-aviso {
    background:#fff3cd; border-left:4px solid #ffc107;
    border-radius:6px; padding:10px 14px; font-size:0.84rem;
    color:#856404; margin-bottom:16px;
    display:flex; align-items:center; gap:8px;
  }

  /* ── Tarjetas resumen ────────────────────────────────────────── */
  .pob-cards {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));
    gap:14px; margin-bottom:22px;
  }
  .pob-card {
    background:#fff; border-radius:10px; padding:16px 18px;
    box-shadow:0 1px 6px rgba(15,23,42,.08);
    display:flex; align-items:center; gap:14px;
  }
  .pob-card-icon {
    width:42px; height:42px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; flex-shrink:0;
  }
  .pob-card-icon.c-pre  { background:#fff3e0; color:#e65100; }
  .pob-card-icon.c-prim { background:#e8eaf6; color:#3949ab; }
  .pob-card-icon.c-bach { background:#ede7f6; color:#6a1b9a; }
  .pob-card-icon.c-tot  { background:#e2e8f0; color:#1e293b; }
  .pob-card-label { font-size:0.73rem; color:#64748b; margin:0 0 2px 0; font-weight:600; letter-spacing:.03em; text-transform:uppercase; }
  .pob-card-val   { font-size:1.3rem; font-weight:800; color:#0f172a; margin:0 0 2px 0; }
  .pob-card-par   { font-size:0.72rem; color:#94a3b8; margin:0; font-weight:500; }

  /* ── Tabla vertical ──────────────────────────────────────────── */
  #tabla-pob {
    border-collapse:separate; border-spacing:0;
    width:100%; font-size:0.82rem;
    border-radius:10px; overflow:hidden;
    box-shadow:0 1px 8px rgba(15,23,42,.09);
  }
  #tabla-pob thead th {
    background:#f8fafc; color:#374151;
    font-weight:600; padding:9px 10px;
    text-align:center; white-space:nowrap;
    border:none; border-bottom:2px solid #e2e8f0;
    font-size:0.79rem; letter-spacing:.03em;
  }
  #tabla-pob thead th.th-grado { text-align:left; padding-left:14px; min-width:100px; }
  #tabla-pob thead th.th-total { background:#f0f4f8; }

  /* filas por nivel */
  #tabla-pob tbody tr.nivel-pre  td.td-grado { border-left:3px solid #f6a935; background:#fffbf0; }
  #tabla-pob tbody tr.nivel-prim td.td-grado { border-left:3px solid #667eea; background:#f5f7ff; }
  #tabla-pob tbody tr.nivel-bach td.td-grado { border-left:3px solid #7c3aed; background:#faf5ff; }

  #tabla-pob tbody tr { background:#fff; transition:background .12s; }
  #tabla-pob tbody tr:hover { background:#f1f5fd; }
  #tabla-pob tbody td {
    padding:5px 8px; border:1px solid #e2e8f0;
    text-align:center; vertical-align:middle;
  }
  #tabla-pob tbody td.td-grado {
    text-align:left; font-weight:600; color:#374151;
    padding-left:12px; font-size:0.83rem;
  }
  #tabla-pob tbody td.td-fila-total {
    font-weight:700; background:#f7fafc; color:#1e293b;
    min-width:52px;
  }

  /* inputs */
  .pob-input {
    width:50px; border:1px solid #cbd5e0; border-radius:5px;
    padding:4px 3px; text-align:center; font-size:0.82rem;
    background:#fff; outline:none; transition:border-color .15s, box-shadow .15s;
  }
  .pob-input:focus { border-color:#667eea; box-shadow:0 0 0 2px rgba(102,126,234,.18); }

  /* input accent por nivel */
  tr.nivel-pre  .pob-input { border-top:2px solid #f6a935; }
  tr.nivel-prim .pob-input { border-top:2px solid #667eea; }
  tr.nivel-bach .pob-input { border-top:2px solid #7c3aed; }

  /* tfoot */
  #tabla-pob tfoot td {
    padding:8px 8px; font-weight:700; font-size:0.83rem;
    border:none; border-top:2px solid #e2e8f0;
    text-align:center; background:#f8fafc; color:#374151;
  }
  #tabla-pob tfoot td.tft-label { text-align:left; padding-left:14px; background:#f0f4f8; }
  #tabla-pob tfoot td.tft-tot   { background:#f0f4f8; }

  /* ── Footer acciones ─────────────────────────────────────────── */
  .pob-footer { display:flex; justify-content:center; margin-top:20px; }
</style>

<div class="pob-wrap">

  <!-- Encabezado -->
  <div class="pob-header">
    <div>
      <h5 class="pob-title"><i class="bi bi-people-fill"></i> Población por curso / Grado</h5>
      <p class="pob-subtitle">Gestiona la cantidad de estudiantes por grado y paralelo</p>
    </div>
    <div class="pob-actions">
      <button type="button" id="agregar" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Agregar paralelo
      </button>
      <button type="button" id="quitar" class="btn btn-outline-danger btn-sm<?= $max_par <= 1 ? ' d-none' : '' ?>">
        <i class="bi bi-dash-circle"></i> Quitar paralelo
      </button>
    </div>
  </div>

  <?php if ($aviso): ?>
  <div class="pob-aviso">
    <i class="bi bi-exclamation-triangle-fill"></i>
    Se está mostrando la población de la temporada anterior. Verifícala y haz clic en "Guardar cambios".
  </div>
  <?php endif; ?>

  <!-- Tarjetas de resumen -->
  <div class="pob-cards">
    <div class="pob-card">
      <div class="pob-card-icon c-pre"><i class="bi bi-building"></i></div>
      <div>
        <p class="pob-card-label">Preescolar</p>
        <p class="pob-card-val" id="pob-total-pre">—</p>
        <p class="pob-card-par" id="pob-par-pre">— paralelos</p>
      </div>
    </div>
    <div class="pob-card">
      <div class="pob-card-icon c-prim"><i class="bi bi-book"></i></div>
      <div>
        <p class="pob-card-label">Primaria</p>
        <p class="pob-card-val" id="pob-total-prim">—</p>
        <p class="pob-card-par" id="pob-par-prim">— paralelos</p>
      </div>
    </div>
    <div class="pob-card">
      <div class="pob-card-icon c-bach"><i class="bi bi-mortarboard"></i></div>
      <div>
        <p class="pob-card-label">Bachillerato</p>
        <p class="pob-card-val" id="pob-total-bach">—</p>
        <p class="pob-card-par" id="pob-par-bach">— paralelos</p>
      </div>
    </div>
    <div class="pob-card">
      <div class="pob-card-icon c-tot"><i class="bi bi-people-fill"></i></div>
      <div>
        <p class="pob-card-label">Total estudiantes</p>
        <p class="pob-card-val" id="pob-total-gen">—</p>
        <p class="pob-card-par" id="pob-par-gen">— paralelos</p>
      </div>
    </div>
  </div>

  <form action="php/poblacion.php" method="POST">
    <div class="table-responsive">
      <table id="tabla-pob">
        <thead>
          <tr>
            <th class="th-grado">Grado</th>
            <?php for ($p = 1; $p <= $max_par; $p++): ?>
            <th class="th-par"><?= $letras_php[$p - 1] ?? 'P'.$p ?></th>
            <?php endfor; ?>
            <th class="th-total">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($grados_list as $g):
            $gid   = (int)$g['id'];
            $nivel = $nivel_map[$gid] ?? 'prim';
          ?>
          <tr class="fila-grado nivel-<?= $nivel ?>" data-gid="<?= $gid ?>" data-nivel="<?= $nivel ?>">
            <td class="td-grado"><?= htmlspecialchars($g['grado']) ?></td>
            <?php for ($p = 1; $p <= $max_par; $p++):
              $v = $vals[$gid][$p] ?? 0;
            ?>
            <td class="col-par">
              <input type="text" class="pob-input"
                     name="<?= $gid ?>-<?= $p ?>"
                     value="<?= $v ?>"
                     inputmode="numeric">
            </td>
            <?php endfor; ?>
            <td class="td-fila-total">—</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr id="filaTotal">
            <td class="tft-label">Total</td>
            <?php for ($p = 1; $p <= $max_par; $p++): ?>
            <td class="tft-dyn"></td>
            <?php endfor; ?>
            <td class="tft-tot" id="total-general"></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <input type="hidden" name="id_colegio" value="<?= htmlspecialchars($_GET['colegio']) ?>">
    <input type="hidden" name="periodo"    value="<?= htmlspecialchars($_GET['periodo']) ?>">
    <input type="hidden" name="cod_colegio" value="<?= htmlspecialchars($_GET['codigo']) ?>">

    <div class="pob-footer">
      <button type="submit" class="btn btn-primary px-5">
        <i class="bi bi-floppy"></i> Guardar cambios
      </button>
    </div>
  </form>
</div>

<script>
(function () {
  var letras      = ['A','B','C','D','E','F','G','H'];
  var currentCols = <?= $max_par ?>;

  // ── Calcular totales ──────────────────────────────────────────────────
  function calcularTotales() {
    var totCols   = [];
    var totNivel  = { pre: 0, prim: 0, bach: 0 };
    var parNonZero = { pre: 0, prim: 0, bach: 0 };
    var parTotal   = { pre: 0, prim: 0, bach: 0 };
    var totGral   = 0;

    $('#tabla-pob tbody tr.fila-grado').each(function () {
      var nivel   = $(this).data('nivel');
      var filaSum = 0;

      $(this).find('td.col-par input.pob-input').each(function (i) {
        var v = parseFloat($(this).val()) || 0;
        totCols[i] = (totCols[i] || 0) + v;
        filaSum   += v;
        totGral   += v;
        parTotal[nivel]++;
        if (v > 0) parNonZero[nivel]++;
      });

      totNivel[nivel] = (totNivel[nivel] || 0) + filaSum;
      $(this).find('td.td-fila-total').text(filaSum > 0 ? filaSum : '—');
    });

    $('#filaTotal td.tft-dyn').each(function (i) {
      $(this).text(totCols[i] > 0 ? totCols[i] : '');
    });
    $('#total-general').text(totGral > 0 ? totGral : '');

    $('#pob-total-pre').text(totNivel.pre  || 0);
    $('#pob-total-prim').text(totNivel.prim || 0);
    $('#pob-total-bach').text(totNivel.bach || 0);
    $('#pob-total-gen').text(totGral || 0);

    var totalNonZero = parNonZero.pre + parNonZero.prim + parNonZero.bach;
    var totalPar     = parTotal.pre  + parTotal.prim  + parTotal.bach;

    $('#pob-par-pre').text(parNonZero.pre  + ' paralelos');
    $('#pob-par-prim').text(parNonZero.prim + ' paralelos');
    $('#pob-par-bach').text(parNonZero.bach + ' paralelos');
    $('#pob-par-gen').text(totalNonZero + ' paralelos');
  }

  calcularTotales();

  // ── Agregar paralelo (columna) ────────────────────────────────────────
  $('#agregar').on('click', function () {
    if (currentCols >= 8) { alert('Máximo 8 paralelos permitidos.'); return; }
    currentCols++;
    var letra = letras[currentCols - 1] || ('P' + currentCols);

    // Header
    $('#tabla-pob thead tr th.th-total').before(
      '<th class="th-par">' + letra + '</th>'
    );

    // Celdas por grado
    $('#tabla-pob tbody tr.fila-grado').each(function () {
      var gid   = $(this).data('gid');
      var nivel = $(this).data('nivel');
      $(this).find('td.td-fila-total').before(
        '<td class="col-par">' +
        '<input type="text" class="pob-input nivel-' + nivel + '"' +
        ' name="' + gid + '-' + currentCols + '" value="0" inputmode="numeric">' +
        '</td>'
      );
    });

    // Footer
    $('#filaTotal td.tft-tot').before('<td class="tft-dyn"></td>');

    calcularTotales();
    $('#quitar').removeClass('d-none');
    if (currentCols >= 8) $(this).prop('disabled', true);
  });

  // ── Quitar paralelo (columna) ─────────────────────────────────────────
  $('#quitar').on('click', function () {
    if (currentCols <= 1) return;

    var tieneValores = false;
    $('#tabla-pob tbody tr.fila-grado').each(function () {
      if (parseFloat($(this).find('td.col-par').last().find('input').val()) > 0) {
        tieneValores = true;
        return false;
      }
    });
    if (tieneValores) {
      alert('No se puede eliminar: el paralelo tiene valores mayores a 0.');
      return;
    }

    $('#tabla-pob thead tr th.th-par').last().remove();
    $('#tabla-pob tbody tr.fila-grado').each(function () {
      $(this).find('td.col-par').last().remove();
    });
    $('#filaTotal td.tft-dyn').last().remove();

    currentCols--;
    calcularTotales();
    if (currentCols <= 1) $('#quitar').addClass('d-none');
    $('#agregar').prop('disabled', false);
  });

  // ── Seleccionar todo al enfocar ───────────────────────────────────────
  $('#tabla-pob').on('focus', 'input.pob-input', function () {
    $(this).select();
  });

  // ── Recalcular al escribir ────────────────────────────────────────────
  $('#tabla-pob').on('input', 'input.pob-input', function () {
    var v = $(this).val().replace(/[^0-9]/g, '');
    $(this).val(v === '' ? '0' : v);
    calcularTotales();
  });
})();
</script>
