<?php
  require_once("../php/aut.php");
  include("../conexion/bdd.php");
?>

<style>
  /* ── Contenedor principal ─────────────────────────────────────────── */
  .pob-wrap { padding: 24px; }

  /* ── Encabezado de sección ─────────────────────────────────────────── */
  .pob-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
  }
  .pob-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 2px 0;
  }
  .pob-title i { color: #6c63ff; margin-right: 6px; }
  .pob-subtitle {
    font-size: 0.82rem;
    color: #718096;
    margin: 0;
  }
  .pob-actions { display: flex; gap: 8px; flex-wrap: wrap; }

  /* ── Mensaje período anterior ──────────────────────────────────────── */
  .pob-aviso {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 6px;
    padding: 10px 14px;
    font-size: 0.84rem;
    color: #856404;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  /* ── Tabla ─────────────────────────────────────────────────────────── */
  #tabla-pob {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    font-size: 0.83rem;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 6px rgba(0,0,0,.08);
  }
  #tabla-pob thead th {
    font-weight: 700;
    text-align: center;
    padding: 9px 6px;
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    white-space: nowrap;
  }
  #tabla-pob thead th.th-curso {
    background: #4a5568;
    color: #fff;
    text-align: left;
    padding-left: 14px;
    min-width: 90px;
  }
  #tabla-pob thead th.th-pre  { background: #f6a935; }
  #tabla-pob thead th.th-prim { background: #667eea; }
  #tabla-pob thead th.th-bach { background: #7c3aed; }
  #tabla-pob thead th.th-tot  { background: #2d3748; }

  /* filas de datos */
  #tabla-pob tbody tr { transition: background .15s; }
  #tabla-pob tbody tr:hover { background: #f0f4ff; }
  #tabla-pob tbody td {
    padding: 4px 4px;
    border: 1px solid #e2e8f0;
    text-align: center;
    vertical-align: middle;
  }
  #tabla-pob tbody td.td-curso {
    font-weight: 600;
    color: #4a5568;
    background: #f7fafc;
    text-align: center;
    font-size: 0.85rem;
  }
  #tabla-pob tbody td.td-fila-total {
    font-weight: 700;
    background: #edf2f7;
    color: #2d3748;
    min-width: 52px;
  }

  /* inputs dentro de la tabla */
  .pob-input {
    width: 46px;
    border: 1px solid #cbd5e0;
    border-radius: 5px;
    padding: 4px 2px;
    text-align: center;
    font-size: 0.83rem;
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
    outline: none;
  }
  .pob-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102,126,234,.2);
  }

  /* franja de color en inputs según nivel */
  td.col-pre  .pob-input { border-top: 2px solid #f6a935; }
  td.col-prim .pob-input { border-top: 2px solid #667eea; }
  td.col-bach .pob-input { border-top: 2px solid #7c3aed; }

  /* fila de totales */
  #tabla-pob tfoot tr td {
    padding: 8px 6px;
    font-weight: 700;
    font-size: 0.85rem;
    border: 1px solid #e2e8f0;
    text-align: center;
    background: #2d3748;
    color: #fff;
  }
  #tabla-pob tfoot td.tft-label { text-align: left; padding-left: 14px; }
  #tabla-pob tfoot td.tft-pre   { background: #f6a935; color: #fff; }
  #tabla-pob tfoot td.tft-prim  { background: #667eea; color: #fff; }
  #tabla-pob tfoot td.tft-bach  { background: #7c3aed; color: #fff; }
  #tabla-pob tfoot td.tft-tot   { background: #1a202c; color: #fff; }

  /* ── Footer acciones ───────────────────────────────────────────────── */
  .pob-footer {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
</style>

<div class="pob-wrap">

  <!-- Encabezado -->
  <div class="pob-header">
    <div>
      <h5 class="pob-title"><i class="bi bi-people-fill"></i> Población por curso / Grado</h5>
      <p class="pob-subtitle">Gestiona la cantidad de estudiantes por grado</p>
    </div>
    <div class="pob-actions">
      <button type="button" id="agregar" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Agregar paralelo
      </button>
      <button type="button" id="quitar" class="btn btn-outline-danger btn-sm d-none">
        <i class="bi bi-dash-circle"></i> Quitar paralelo
      </button>
    </div>
  </div>

  <?php
    $sql = "SELECT MAX(paralelos) as nunfila FROM `grados_paralelos`
            WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$_GET['periodo']}' AND alumnos > 0";
    $req = $bdd->prepare($sql);
    $req->execute();
    $nunfila = $req->fetch();

    $aviso = false;

    if ($nunfila['nunfila'] < 1) {
      $sql_pa = "SELECT id FROM periodos WHERE id_calendario='{$_GET['id_calendario']}' ORDER BY id DESC LIMIT 1 OFFSET 1;";
      $req_pa = $bdd->prepare($sql_pa);
      $req_pa->execute();
      $pa = $req_pa->fetch();

      $periodo_po = $pa['id'];

      $sql = "SELECT MAX(paralelos) as nunfila FROM `grados_paralelos`
              WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$pa['id']}'";
      $req = $bdd->prepare($sql);
      $req->execute();
      $nunfila = $req->fetch();

      if ($nunfila['nunfila'] < 1) {
        $nunfila['nunfila'] = 1;
      } else {
        $aviso = true;
      }
    } else {
      $periodo_po = $_GET['periodo'];
    }
  ?>

  <?php if ($aviso): ?>
  <div class="pob-aviso">
    <i class="bi bi-exclamation-triangle-fill"></i>
    Se está mostrando la población de la temporada anterior. Verifícala y haz clic en "Guardar cambios".
  </div>
  <?php endif; ?>

  <form action="php/poblacion.php" method="POST">
    <div class="table-responsive">
      <table id="tabla-pob">
        <thead>
          <tr>
            <th class="th-curso">Curso ↓ / Grado → </th>
            <th class="th-pre">PRE</th>
            <th class="th-pre">JAR</th>
            <th class="th-pre">TRA</th>
            <th class="th-prim">1</th>
            <th class="th-prim">2</th>
            <th class="th-prim">3</th>
            <th class="th-prim">4</th>
            <th class="th-prim">5</th>
            <th class="th-bach">6</th>
            <th class="th-bach">7</th>
            <th class="th-bach">8</th>
            <th class="th-bach">9</th>
            <th class="th-bach">10</th>
            <th class="th-bach">11</th>
            <th class="th-tot">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $grados_ids  = [1,2,3,4,5,6,7,8,9,10,11,12,13,14];
            $grados_cols = ['col-pre','col-pre','col-pre','col-prim','col-prim','col-prim','col-prim','col-prim','col-bach','col-bach','col-bach','col-bach','col-bach','col-bach'];

            for ($i = 0; $i < $nunfila['nunfila']; $i++):
              $p = $i + 1;
              $vals = [];
              foreach ($grados_ids as $gid) {
                $s = $bdd->prepare("SELECT alumnos FROM grados_paralelos
                                    WHERE id_colegio=? AND id_periodo=? AND id_grado=? AND paralelos=?");
                $s->execute([$_GET['colegio'], $periodo_po, $gid, $p]);
                $r = $s->fetch();
                $vals[] = $r ? (int)$r['alumnos'] : 0;
              }
          ?>
          <tr class="fila-base">
            <td class="td-curso"><?= str_pad($p, 2, '0', STR_PAD_LEFT) ?></td>
            <?php foreach ($grados_ids as $idx => $gid): ?>
            <td class="<?= $grados_cols[$idx] ?>">
              <input type="text" class="pob-input"
                     name="<?= $gid ?>-<?= $p ?>"
                     id="<?= $gid ?>-<?= $p ?>"
                     value="<?= $vals[$idx] ?>"
                     inputmode="numeric">
            </td>
            <?php endforeach; ?>
            <td class="td-fila-total">—</td>
          </tr>
          <?php endfor; ?>
        </tbody>
        <tfoot>
          <tr id="filaTotal">
            <td class="tft-label">Total</td>
            <td class="total-col tft-pre"></td>
            <td class="total-col tft-pre"></td>
            <td class="total-col tft-pre"></td>
            <td class="total-col tft-prim"></td>
            <td class="total-col tft-prim"></td>
            <td class="total-col tft-prim"></td>
            <td class="total-col tft-prim"></td>
            <td class="total-col tft-prim"></td>
            <td class="total-col tft-bach"></td>
            <td class="total-col tft-bach"></td>
            <td class="total-col tft-bach"></td>
            <td class="total-col tft-bach"></td>
            <td class="total-col tft-bach"></td>
            <td class="total-col tft-bach"></td>
            <td class="tft-tot" id="total-general"></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <input type="hidden" name="id_colegio"  value="<?= htmlspecialchars($_GET['colegio'])      ?>">
    <input type="hidden" name="periodo"      value="<?= htmlspecialchars($_GET['periodo'])      ?>">
    <input type="hidden" name="cod_colegio"  value="<?= htmlspecialchars($_GET['codigo'])       ?>">

    <div class="pob-footer">
      <button type="submit" class="btn btn-primary px-5">
        <i class="bi bi-floppy"></i> Guardar cambios
      </button>
    </div>
  </form>
</div>

<script>
(function () {
  var COLS = 14; // grados (sin contar cursor ni total)

  function calcularTotales() {
    var totCols  = Array(COLS).fill(0);
    var totGral  = 0;

    $('#tabla-pob tbody tr.fila-base').each(function () {
      var filaTot = 0;
      $(this).find('input.pob-input').each(function (i) {
        var v = parseFloat($(this).val()) || 0;
        totCols[i] += v;
        filaTot    += v;
        totGral    += v;
      });
      $(this).find('td.td-fila-total').text(filaTot || '—');
    });

    $('#filaTotal .total-col').each(function (i) {
      $(this).text(totCols[i] || '');
    });
    $('#total-general').text(totGral || '');
  }

  calcularTotales();

  // ── Agregar paralelo ──────────────────────────────────────────────────
  $('#agregar').on('click', function () {
    var ultimaFila  = $('#tabla-pob tbody tr.fila-base').last();
    var numActual   = parseInt(ultimaFila.find('td.td-curso').text(), 10);
    var numNuevo    = numActual + 1;
    var numNuevoPad = ('0' + numNuevo).slice(-2);

    var nuevaFila = ultimaFila.clone();
    nuevaFila.find('td.td-curso').text(numNuevoPad);
    nuevaFila.find('td.td-fila-total').text('—');
    nuevaFila.find('input.pob-input').each(function () {
      var name = $(this).attr('name').replace(/-\d+$/, '-' + numNuevo);
      var id   = $(this).attr('id').replace(/-\d+$/, '-' + numNuevo);
      $(this).attr('name', name).attr('id', id).val('0');
    });

    $('#tabla-pob tbody').append(nuevaFila);
    calcularTotales();
    $('#quitar').removeClass('d-none');
  });

  // ── Quitar paralelo ───────────────────────────────────────────────────
  $('#quitar').on('click', function () {
    var filas = $('#tabla-pob tbody tr.fila-base');
    if (filas.length <= 1) { alert('Debe haber al menos una fila.'); return; }

    var ultimaFila = filas.last();
    var tieneValores = false;
    ultimaFila.find('input.pob-input').each(function () {
      if (parseFloat($(this).val()) > 0) { tieneValores = true; return false; }
    });

    if (tieneValores) {
      alert('No se puede eliminar esta fila. Contiene valores mayores a 0.');
      return;
    }

    ultimaFila.remove();
    calcularTotales();
    if ($('#tabla-pob tbody tr.fila-base').length <= 1) {
      $('#quitar').addClass('d-none');
    }
  });

  // ── Seleccionar todo al entrar al campo ──────────────────────────────
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
