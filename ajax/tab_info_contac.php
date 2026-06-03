<?php
require_once("../php/aut.php");
include("../conexion/bdd.php");

function ic_color($str) {
    $palette = ['#4361ee','#e67e22','#e91e63','#2ecc71','#9b59b6','#1abc9c','#e74c3c','#0891b2'];
    return $palette[ord(strtolower($str)[0] ?? 'a') % count($palette)];
}
function ic_initials($n, $a) {
    return strtoupper(substr(trim($n),0,1) . substr(trim($a),0,1)) ?: '?';
}
?>

<style>
  /* ── Scoped to this tab only ── */
  .ic-wrap * { box-sizing:border-box; }

  .ic-section { margin-bottom:28px; }
  .ic-section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; }
  .ic-section-title  { font-size:14px; font-weight:700; color:#111827; display:flex; align-items:center; gap:8px; margin:0; }
  .ic-level-badge    { width:26px; height:26px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; }
  .ic-level-1 { background:#eef0ff; color:#4361ee; }
  .ic-level-2 { background:#d1fae5; color:#059669; }

  .ic-btn-add { display:inline-flex; align-items:center; gap:6px; background:#4361ee; color:#fff; border:none; border-radius:7px; padding:7px 14px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; }
  .ic-btn-add:hover { background:#3451d1; color:#fff; text-decoration:none; }

  /* ── Contact card ── */
  .ic-card { background:#fff; border:1px solid #e9ecef; border-radius:9px; margin-bottom:8px; overflow:hidden; transition:box-shadow .2s; }
  .ic-card:hover { box-shadow:0 2px 8px rgba(0,0,0,.07); }
  .ic-view  { display:flex; align-items:center; gap:14px; padding:13px 16px; flex-wrap:wrap; }
  .ic-avatar { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#fff; flex-shrink:0; }
  .ic-info  { flex:1; min-width:160px; }
  .ic-name  { font-size:14px; font-weight:600; color:#111827; }
  .ic-badge { display:inline-block; padding:2px 9px; border-radius:20px; font-size:11px; font-weight:600; margin-left:6px; }
  .ic-badge-cargo { background:#eef0ff; color:#4361ee; }
  .ic-badge-area  { background:#d1fae5; color:#059669; }
  .ic-meta  { display:flex; flex-wrap:wrap; gap:14px; margin-top:3px; }
  .ic-meta span { font-size:12.5px; color:#6b7280; display:flex; align-items:center; gap:4px; }
  .ic-actions { display:flex; gap:6px; margin-left:auto; }
  .ic-btn-edit { border:1px solid #e9ecef; background:#fff; color:#4361ee; padding:5px 9px; border-radius:6px; cursor:pointer; font-size:13px; line-height:1; }
  .ic-btn-edit:hover { background:#eef0ff; border-color:#c7d2fe; }

  /* ── Inline edit form ── */
  .ic-edit-form { padding:16px; border-top:2px solid #4361ee; background:#fafbff; }
  .ic-edit-form .form-group label { font-size:12px; font-weight:600; color:#374151; margin-bottom:4px; }
  .ic-edit-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:12px; }

  /* ── Empty state ── */
  .ic-empty { text-align:center; padding:28px; color:#9ca3af; font-size:13px; background:#fafafa; border:1px dashed #e5e7eb; border-radius:8px; margin-bottom:8px; }

  /* ── Modal ── */
  .ic-modal .modal-content { border:none; border-radius:12px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,.15); }
  .ic-modal .modal-header  { background:#f8faff; border-bottom:1px solid #e9ecef; padding:16px 20px; }
  .ic-modal .modal-title   { font-size:15px; font-weight:700; color:#111827; display:flex; align-items:center; gap:8px; }
  .ic-modal .modal-title i { color:#4361ee; font-size:18px; }
  .ic-modal .modal-body    { padding:20px; }
  .ic-modal .modal-footer  { border-top:1px solid #f3f4f6; padding:12px 20px; background:#fafafa; }
  .ic-modal .form-group label { font-size:12.5px; font-weight:600; color:#374151; margin-bottom:4px; }
  .ic-modal .form-control  { border-radius:7px; font-size:13px; }
  .ic-modal .custom-select { border-radius:7px; font-size:13px; }
  .ic-modal-divider { border:none; border-top:1px dashed #c7d2fe; margin:16px 0; }
  .ic-modal .ic-extra-contact { background:#f8faff; border:1px solid #e0e7ff; border-radius:8px; padding:14px; margin-bottom:12px; }
  .ic-add-more { display:inline-flex; align-items:center; gap:6px; color:#4361ee; font-size:13px; font-weight:600; cursor:pointer; padding:6px 0; }
  .ic-add-more:hover { text-decoration:underline; }
  .ic-modal .btn-primary  { background:#4361ee; border-color:#4361ee; border-radius:7px; padding:7px 20px; font-weight:600; }
  .ic-modal .btn-light    { border-radius:7px; }
</style>

<div class="ic-wrap pd-20">

  <?php
  /* ══════════════════════════════
     NIVEL 1 — ADMINISTRATIVO
  ══════════════════════════════ */
  $adms = $bdd->prepare("SELECT * FROM trabajadores_colegios WHERE id_colegio=? AND cargo !=6");
  $adms->execute([$_GET["colegio"]]);
  $adms = $adms->fetchAll();

  $cargos_q = $bdd->prepare("SELECT * FROM cargos WHERE id !=5");
  $cargos_q->execute();
  $cargos_all = $cargos_q->fetchAll();
  $cargos_map = array_column($cargos_all, 'cargo', 'id');
  ?>

  <div class="ic-section">
    <div class="ic-section-header">
      <h6 class="ic-section-title">
        <span class="ic-level-badge ic-level-1">1</span>
        Nivel 1 — Administrativo
      </h6>
      <a href="#" class="ic-btn-add" data-toggle="modal" data-target="#modal_adm">
        <i class="bi bi-person-plus"></i> Agregar contacto
      </a>
    </div>

    <?php if (empty($adms)): ?>
    <div class="ic-empty"><i class="bi bi-people" style="font-size:22px;display:block;margin-bottom:6px"></i>Sin contactos administrativos registrados</div>
    <?php endif; ?>

    <?php foreach ($adms as $adm):
      $ini   = ic_initials($adm['nombre'], $adm['apellido']);
      $color = ic_color($adm['nombre']);
      $cargo_nombre = $cargos_map[$adm['cargo']] ?? '—';
    ?>
    <div class="ic-card">
      <!-- Vista -->
      <div class="ic-view">
        <div class="ic-avatar" style="background:<?= $color ?>">
          <?= htmlspecialchars($ini) ?>
        </div>
        <div class="ic-info">
          <div class="ic-name">
            <?= htmlspecialchars($adm['nombre'].' '.$adm['apellido']) ?>
            <span class="ic-badge ic-badge-cargo"><?= htmlspecialchars($cargo_nombre) ?></span>
          </div>
          <div class="ic-meta">
            <?php if ($adm['email']): ?>
            <span><i class="bi bi-envelope"></i> <?= htmlspecialchars($adm['email']) ?></span>
            <?php endif; ?>
            <?php if ($adm['telefono']): ?>
            <span><i class="bi bi-telephone"></i> <?= htmlspecialchars($adm['telefono']) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="ic-actions">
          <button type="button" class="ic-btn-edit" title="Editar">
            <i class="bi bi-pencil"></i>
          </button>
        </div>
      </div>

      <!-- Formulario de edición (oculto) -->
      <form action="php/modificar_adm.php" method="POST">
        <div class="ic-edit-form d-none">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label>Nombres <small style="color:red">*</small></label>
                <input type="text" class="form-control form-control-sm" name="nombre_adm"
                       value="<?= htmlspecialchars($adm['nombre']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Apellidos <small style="color:red">*</small></label>
                <input type="text" class="form-control form-control-sm" name="apellido_adm"
                       value="<?= htmlspecialchars($adm['apellido']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Correo <small style="color:red">*</small></label>
                <input type="email" class="form-control form-control-sm" name="correo_adm"
                       value="<?= htmlspecialchars($adm['email']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Teléfono <small style="color:red">*</small></label>
                <input type="text" class="form-control form-control-sm" name="telefono_adm"
                       value="<?= htmlspecialchars($adm['telefono']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Cargo <small style="color:red">*</small></label>
                <select class="form-control form-control-sm custom-select" name="cargo_adm" required>
                  <option value="">Seleccione</option>
                  <?php foreach ($cargos_all as $c):
                    $sel = $c['id'] == $adm['cargo'] ? 'selected' : '';
                    echo '<option value="'.$c['id'].'" '.$sel.'>'.$c['cargo'].'</option>';
                  endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="ic-edit-actions">
            <button type="button" class="btn btn-light btn-sm ic-btn-cancel">Cancelar</button>
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Guardar</button>
          </div>
          <input type="hidden" name="id_adm"      value="<?= $adm['id'] ?>">
          <input type="hidden" name="id_colegio"  value="<?= $_GET['colegio'] ?>">
          <input type="hidden" name="periodo"      value="<?= $_GET['periodo'] ?>">
          <input type="hidden" name="cod_colegio" value="<?= $_GET['codigo'] ?>">
        </div>
      </form>
    </div>
    <?php endforeach; ?>
  </div>

  <?php
  /* ══════════════════════════════
     NIVEL 2 — ACADÉMICO
  ══════════════════════════════ */
  $profes = $bdd->prepare("SELECT * FROM trabajadores_colegios WHERE id_colegio=? AND cargo=6");
  $profes->execute([$_GET["colegio"]]);
  $profes = $profes->fetchAll();

  $materias_q = $bdd->prepare("SELECT * FROM materias WHERE id < 16");
  $materias_q->execute();
  $materias_all = $materias_q->fetchAll();
  $materias_map = array_column($materias_all, 'materia', 'id');
  ?>

  <div class="ic-section">
    <div class="ic-section-header">
      <h6 class="ic-section-title">
        <span class="ic-level-badge ic-level-2">2</span>
        Nivel 2 — Académico
      </h6>
      <a href="#" class="ic-btn-add" style="background:#059669" data-toggle="modal" data-target="#modal_profes">
        <i class="bi bi-person-plus"></i> Agregar profesor
      </a>
    </div>

    <?php if (empty($profes)): ?>
    <div class="ic-empty"><i class="bi bi-book" style="font-size:22px;display:block;margin-bottom:6px"></i>Sin profesores registrados</div>
    <?php endif; ?>

    <?php foreach ($profes as $profe):
      $ini   = ic_initials($profe['nombre'], $profe['apellido']);
      $color = ic_color($profe['nombre']);
      $area_nombre = $materias_map[$profe['area']] ?? '—';
    ?>
    <div class="ic-card">
      <!-- Vista -->
      <div class="ic-view">
        <div class="ic-avatar" style="background:<?= $color ?>">
          <?= htmlspecialchars($ini) ?>
        </div>
        <div class="ic-info">
          <div class="ic-name">
            <?= htmlspecialchars($profe['nombre'].' '.$profe['apellido']) ?>
            <span class="ic-badge ic-badge-area"><?= htmlspecialchars($area_nombre) ?></span>
          </div>
          <div class="ic-meta">
            <?php if ($profe['email']): ?>
            <span><i class="bi bi-envelope"></i> <?= htmlspecialchars($profe['email']) ?></span>
            <?php endif; ?>
            <?php if ($profe['telefono']): ?>
            <span><i class="bi bi-telephone"></i> <?= htmlspecialchars($profe['telefono']) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="ic-actions">
          <button type="button" class="ic-btn-edit" title="Editar">
            <i class="bi bi-pencil"></i>
          </button>
        </div>
      </div>

      <!-- Formulario de edición (oculto) -->
      <form action="php/modificar_profe.php" method="POST">
        <div class="ic-edit-form d-none">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label>Nombres <small style="color:red">*</small></label>
                <input type="text" class="form-control form-control-sm" name="nombre_profe"
                       value="<?= htmlspecialchars($profe['nombre']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Apellidos <small style="color:red">*</small></label>
                <input type="text" class="form-control form-control-sm" name="apellido_profe"
                       value="<?= htmlspecialchars($profe['apellido']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Correo <small style="color:red">*</small></label>
                <input type="email" class="form-control form-control-sm" name="correo_profe"
                       value="<?= htmlspecialchars($profe['email']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Teléfono <small style="color:red">*</small></label>
                <input type="text" class="form-control form-control-sm" name="telefono_profe"
                       value="<?= htmlspecialchars($profe['telefono']) ?>" required />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label>Área <small style="color:red">*</small></label>
                <select class="form-control form-control-sm custom-select" name="area_profe" required>
                  <option value="">Seleccione</option>
                  <?php foreach ($materias_all as $m):
                    $sel = $m['id'] == $profe['area'] ? 'selected' : '';
                    echo '<option value="'.$m['id'].'" '.$sel.'>'.$m['materia'].'</option>';
                  endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="ic-edit-actions">
            <button type="button" class="btn btn-light btn-sm ic-btn-cancel">Cancelar</button>
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Guardar</button>
          </div>
          <input type="hidden" name="id_profe"    value="<?= $profe['id'] ?>">
          <input type="hidden" name="id_colegio"  value="<?= $_GET['colegio'] ?>">
          <input type="hidden" name="periodo"      value="<?= $_GET['periodo'] ?>">
          <input type="hidden" name="cod_colegio" value="<?= $_GET['codigo'] ?>">
        </div>
      </form>
    </div>
    <?php endforeach; ?>
  </div>


  <!-- ══ MODAL: Agregar administrativo ══ -->
  <div class="modal fade ic-modal" id="modal_adm" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title"><i class="bi bi-person-plus-fill"></i> Agregar trabajador administrativo</div>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <form action="php/guardar_adm.php" method="POST">
          <div class="modal-body">
            <div class="otro_adm">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nombres <small style="color:red">*</small></label>
                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_adm" id="nombre_adm" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Apellidos <small style="color:red">*</small></label>
                    <input type="text" class="form-control" placeholder="Apellido" name="apellido_adm" id="apellido_adm" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Correo <small style="color:red">*</small></label>
                    <input type="email" class="form-control" placeholder="Correo" name="correo_adm" id="correo_adm" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Teléfono <small style="color:red">*</small></label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_adm" id="telefono_adm" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Cargo <small style="color:red">*</small></label>
                    <select class="custom-select" name="cargo_adm" id="cargo_adm" required>
                      <option value="">Seleccione</option>
                      <?php foreach ($cargos_all as $c):
                        echo '<option value="'.$c['id'].'">'.$c['cargo'].'</option>';
                      endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="adm[]" id="adm">

            <?php for ($i=1; $i < 15; $i++): ?>
            <div id="agg_adm<?= $i ?>" class="ic-extra-contact d-none">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_adm" id="nombre_adm<?= $i ?>" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" class="form-control" placeholder="Apellido" name="apellido_adm" id="apellido_adm<?= $i ?>" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Correo</label>
                    <input type="email" class="form-control" placeholder="Correo" name="correo_adm" id="correo_adm<?= $i ?>" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_adm" id="telefono_adm<?= $i ?>" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Cargo</label>
                    <select class="custom-select" name="cargo_adm" id="cargo_adm<?= $i ?>">
                      <option value="">Seleccione</option>
                      <?php foreach ($cargos_all as $c):
                        echo '<option value="'.$c['id'].'">'.$c['cargo'].'</option>';
                      endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="adm[]" id="adm<?= $i ?>">
            <?php endfor; ?>

            <span class="ic-add-more" id="agregar_adm">
              <i class="bi bi-plus-circle"></i> Agregar otro trabajador
            </span>

            <input type="hidden" name="id_colegio"  value="<?= $_GET['colegio'] ?>">
            <input type="hidden" name="periodo"      value="<?= $_GET['periodo'] ?>">
            <input type="hidden" name="cod_colegio" value="<?= $_GET['codigo'] ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ══ MODAL: Agregar profesor ══ -->
  <div class="modal fade ic-modal" id="modal_profes" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title"><i class="bi bi-mortarboard"></i> Agregar profesor</div>
          <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <form action="php/guardar_profe.php" method="POST">
          <div class="modal-body">
            <div class="otro_profe">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nombres <small style="color:red">*</small></label>
                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_profe" id="nombre_profe" required />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Apellidos <small style="color:red">*</small></label>
                    <input type="text" class="form-control" placeholder="Apellido" name="apellido_profe" id="apellido_profe" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Correo <small style="color:red">*</small></label>
                    <input type="email" class="form-control" placeholder="Correo" name="correo_profe" id="correo_profe" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Teléfono <small style="color:red">*</small></label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_profe" id="telefono_profe" required />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Área <small style="color:red">*</small></label>
                    <select class="custom-select2" name="area_profe" id="area_profe" required>
                      <option value="">Seleccione</option>
                      <?php foreach ($materias_all as $m):
                        echo '<option value="'.$m['id'].'">'.$m['materia'].'</option>';
                      endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <input type="hidden" name="profe[]" id="profe">

            <?php for ($i=1; $i < 15; $i++): ?>
            <div id="agg_profe<?= $i ?>" class="ic-extra-contact d-none">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_profe" id="nombre_profe<?= $i ?>" />
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" class="form-control" placeholder="Apellido" name="apellido_profe" id="apellido_profe<?= $i ?>" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Correo</label>
                    <input type="email" class="form-control" placeholder="Correo" name="correo_profe" id="correo_profe<?= $i ?>" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_profe" id="telefono_profe<?= $i ?>" />
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Área</label>
                    <select class="custom-select2" name="area_profe" id="area_profe<?= $i ?>">
                      <option value="">Seleccione</option>
                      <?php foreach ($materias_all as $m):
                        echo '<option value="'.$m['id'].'">'.$m['materia'].'</option>';
                      endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="profe[]" id="profe<?= $i ?>">
            <?php endfor; ?>

            <span class="ic-add-more" id="agregar_profe">
              <i class="bi bi-plus-circle"></i> Agregar otro profesor
            </span>

            <input type="hidden" name="id_colegio"  value="<?= $_GET['colegio'] ?>">
            <input type="hidden" name="periodo"      value="<?= $_GET['periodo'] ?>">
            <input type="hidden" name="cod_colegio" value="<?= $_GET['codigo'] ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</div><!-- /.ic-wrap -->

<script>
// Toggle editar / cancelar en las tarjetas
$(document).on('click', '.ic-btn-edit', function () {
  var card = $(this).closest('.ic-card');
  card.find('.ic-view').hide();
  card.find('.ic-edit-form').removeClass('d-none');
});
$(document).on('click', '.ic-btn-cancel', function () {
  var card = $(this).closest('.ic-card');
  card.find('.ic-edit-form').addClass('d-none');
  card.find('.ic-view').show();
});

// ── Guardar datos en hidden fields (administrativo) ──
function syncAdm(i) {
  var sfx = i === 0 ? '' : i;
  var n = $('#nombre_adm'  + sfx).val();
  var a = $('#apellido_adm'+ sfx).val();
  var c = $('#correo_adm'  + sfx).val();
  var t = $('#telefono_adm'+ sfx).val();
  var g = $('#cargo_adm'   + sfx).val();
  $('#adm' + sfx).val(n+'/'+a+'/'+c+'/'+t+'/'+g);
}
$('#nombre_adm, #apellido_adm, #correo_adm, #telefono_adm').on('keyup', function(){ syncAdm(0); });
$('#cargo_adm').on('change', function(){ syncAdm(0); });

var mAdm = 1;
$('#agregar_adm').on('click', function () {
  if (mAdm > 13) { $(this).hide(); return; }
  $('#agg_adm' + mAdm).removeClass('d-none');
  (function(i){
    $('#nombre_adm'+i+', #apellido_adm'+i+', #correo_adm'+i+', #telefono_adm'+i).on('keyup', function(){ syncAdm(i); });
    $('#cargo_adm'+i).on('change', function(){ syncAdm(i); });
  })(mAdm);
  mAdm++;
});

// ── Guardar datos en hidden fields (profesores) ──
function syncProfe(i) {
  var sfx = i === 0 ? '' : i;
  var n = $('#nombre_profe'  + sfx).val();
  var a = $('#apellido_profe'+ sfx).val();
  var c = $('#correo_profe'  + sfx).val();
  var t = $('#telefono_profe'+ sfx).val();
  var r = $('#area_profe'    + sfx).val();
  $('#profe' + sfx).val(n+'/'+a+'/'+c+'/'+t+'/'+r);
}
$('#nombre_profe, #apellido_profe, #correo_profe, #telefono_profe').on('keyup', function(){ syncProfe(0); });
$('#area_profe').on('change', function(){ syncProfe(0); });

var mProfe = 1;
$('#agregar_profe').on('click', function () {
  if (mProfe > 13) { $(this).hide(); return; }
  $('#agg_profe' + mProfe).removeClass('d-none');
  (function(i){
    $('#nombre_profe'+i+', #apellido_profe'+i+', #correo_profe'+i+', #telefono_profe'+i).on('keyup', function(){ syncProfe(i); });
    $('#area_profe'+i).on('change', function(){ syncProfe(i); });
  })(mProfe);
  mProfe++;
});
</script>
