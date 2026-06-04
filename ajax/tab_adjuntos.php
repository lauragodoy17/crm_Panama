<?php
  require_once("../php/aut.php");
  include("../conexion/bdd.php");

  $sql = "SELECT id, adjunto, nombre FROM adjuntos WHERE id_colegio='".$_GET['colegio']."' AND id_periodo='".$_GET['periodo']."' AND tipo!=1";
  $req = $bdd->prepare($sql);
  $req->execute();
  $adjuntos = $req->fetchAll();
?>

<style>
  .adj-wrap * { box-sizing: border-box; }

  /* ── Encabezado de sección ── */
  .adj-section-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
  .adj-section-title  { font-size:14px; font-weight:700; color:#111827; display:flex; align-items:center; gap:8px; margin:0; }
  .adj-section-title i { color:#4361ee; font-size:16px; }

  /* ── Formulario de carga ── */
  .adj-upload-box {
    background:#f8faff;
    border:1px solid #e0e7ff;
    border-radius:10px;
    padding:18px 20px;
    margin-bottom:22px;
  }
  .adj-upload-box .adj-upload-title {
    font-size:13px; font-weight:700; color:#374151;
    display:flex; align-items:center; gap:6px; margin-bottom:14px;
  }
  .adj-upload-box .adj-upload-title i { color:#4361ee; }
  .adj-form-row { display:flex; gap:14px; flex-wrap:wrap; align-items:flex-end; }
  .adj-form-group { display:flex; flex-direction:column; gap:4px; flex:1; min-width:180px; }
  .adj-form-group label { font-size:12px; font-weight:600; color:#374151; }
  .adj-form-group .form-control {
    border-radius:7px; font-size:13px; border:1px solid #d1d5db;
    padding:7px 10px; transition:border-color .15s;
  }
  .adj-form-group .form-control:focus { border-color:#4361ee; box-shadow:0 0 0 3px rgba(67,97,238,.1); outline:none; }

  /* ── Botón subir ── */
  .adj-btn-upload {
    display:inline-flex; align-items:center; gap:6px;
    background:#4361ee; color:#fff; border:none; border-radius:7px;
    padding:8px 18px; font-size:13px; font-weight:600; cursor:pointer;
    white-space:nowrap; align-self:flex-end;
  }
  .adj-btn-upload:hover { background:#3451d1; }

  /* ── Tarjetas de adjuntos ── */
  .adj-card {
    background:#fff; border:1px solid #e9ecef; border-radius:9px;
    margin-bottom:8px; overflow:hidden; transition:box-shadow .2s;
    display:flex; align-items:center; gap:14px; padding:12px 16px;
  }
  .adj-card:hover { box-shadow:0 2px 8px rgba(0,0,0,.07); }

  .adj-icon {
    width:40px; height:40px; border-radius:8px;
    background:#eef0ff; color:#4361ee;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; flex-shrink:0;
  }
  .adj-info { flex:1; min-width:0; }
  .adj-name { font-size:13.5px; font-weight:600; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .adj-file { font-size:12px; color:#6b7280; margin-top:2px; display:flex; align-items:center; gap:4px; }
  .adj-file a { color:#4361ee; text-decoration:none; font-weight:500; }
  .adj-file a:hover { text-decoration:underline; }

  .adj-btn-delete {
    border:1px solid #fee2e2; background:#fff; color:#ef4444;
    padding:5px 9px; border-radius:6px; cursor:pointer; font-size:13px;
    line-height:1; flex-shrink:0;
  }
  .adj-btn-delete:hover { background:#fee2e2; }

  /* ── Estado vacío ── */
  .adj-empty {
    text-align:center; padding:30px 20px; color:#9ca3af; font-size:13px;
    background:#fafafa; border:1px dashed #e5e7eb; border-radius:8px;
  }
  .adj-empty i { font-size:28px; display:block; margin-bottom:8px; color:#d1d5db; }

  /* ── Modal de confirmación ── */
  .adj-overlay {
    position:fixed; inset:0; background:rgba(15,23,42,.45);
    z-index:99998; display:flex; align-items:center; justify-content:center;
    opacity:0; pointer-events:none; transition:opacity .2s;
  }
  .adj-overlay.open { opacity:1; pointer-events:all; }
  .adj-confirm-box {
    background:#fff; border-radius:14px; padding:28px 32px;
    max-width:360px; width:90%; text-align:center;
    box-shadow:0 10px 40px rgba(15,23,42,.2);
    transform:scale(.95); transition:transform .2s;
  }
  .adj-overlay.open .adj-confirm-box { transform:scale(1); }
  .adj-confirm-icon  { font-size:2.2rem; color:#ef4444; margin-bottom:10px; }
  .adj-confirm-title { font-size:1rem; font-weight:700; color:#0f172a; margin:0 0 6px; }
  .adj-confirm-msg   { font-size:0.85rem; color:#64748b; margin:0 0 22px; }
  .adj-confirm-btns  { display:flex; gap:10px; justify-content:center; }
  .adj-confirm-btns .btn-cancel { background:#f1f5f9; color:#475569; border:none; border-radius:8px; padding:8px 22px; font-size:0.85rem; cursor:pointer; }
  .adj-confirm-btns .btn-ok     { background:#ef4444; color:#fff; border:none; border-radius:8px; padding:8px 22px; font-size:0.85rem; cursor:pointer; font-weight:600; }
</style>

<div class="adj-wrap pd-20">

  <?php if ($_SESSION["tipo"] != 2): ?>
  <!-- Formulario de carga -->
  <div class="adj-upload-box">
    <p class="adj-upload-title"><i class="bi bi-cloud-arrow-up"></i> Subir nuevo documento</p>
    <form action="php/adjuntos.php" method="POST" enctype="multipart/form-data">
      <div class="adj-form-row">
        <div class="adj-form-group">
          <label for="nombre">Nombre del documento <span style="color:#ef4444">*</span></label>
          <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej. Contrato firmado" required>
        </div>
        <div class="adj-form-group">
          <label for="lista">Archivo <span style="color:#ef4444">*</span></label>
          <input type="file" class="form-control" name="lista" id="lista" required>
        </div>
        <input type="hidden" name="colegio"     value="<?= htmlspecialchars($_GET['colegio']) ?>">
        <input type="hidden" name="periodo"     value="<?= htmlspecialchars($_GET['periodo']) ?>">
        <input type="hidden" name="cod_colegio" value="<?= htmlspecialchars($_GET['codigo'])  ?>">
        <button type="submit" class="adj-btn-upload">
          <i class="bi bi-upload"></i> Subir
        </button>
      </div>
    </form>
  </div>
  <?php endif; ?>

  <!-- Listado de adjuntos -->
  <div class="adj-section-header">
    <p class="adj-section-title"><i class="bi bi-paperclip"></i> Documentos adjuntos</p>
    <span style="font-size:12px;color:#6b7280;font-weight:600;"><?= count($adjuntos) ?> archivo<?= count($adjuntos) != 1 ? 's' : '' ?></span>
  </div>

  <?php if (empty($adjuntos)): ?>
    <div class="adj-empty">
      <i class="bi bi-folder2-open"></i>
      No hay documentos adjuntos para este período.
    </div>
  <?php else: ?>
    <?php foreach ($adjuntos as $adjunto):
      list($antes, $archivo) = explode("_", $adjunto["adjunto"], 2);
      $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
      $icon = match($ext) {
        'pdf'        => 'bi-file-earmark-pdf',
        'doc','docx' => 'bi-file-earmark-word',
        'xls','xlsx' => 'bi-file-earmark-excel',
        'jpg','jpeg','png','gif','webp' => 'bi-file-earmark-image',
        default      => 'bi-file-earmark'
      };
    ?>
    <div class="adj-card">
      <div class="adj-icon"><i class="bi <?= $icon ?>"></i></div>
      <div class="adj-info">
        <div class="adj-name"><?= htmlspecialchars($adjunto["nombre"]) ?></div>
        <div class="adj-file">
          <i class="bi bi-link-45deg"></i>
          <a href="adjuntos/<?= htmlspecialchars($adjunto["adjunto"]) ?>" target="_blank" download="<?= htmlspecialchars($archivo) ?>">
            <?= htmlspecialchars($archivo) ?>
          </a>
        </div>
      </div>
      <?php if ($_SESSION["tipo"] != 2): ?>
      <button class="adj-btn-delete eliminar_ad" data-adj="<?= $adjunto["id"] ?>" title="Eliminar">
        <i class="bi bi-trash3"></i>
      </button>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>

</div>

<!-- Modal de confirmación -->
<div class="adj-overlay" id="adj-overlay">
  <div class="adj-confirm-box">
    <div class="adj-confirm-icon"><i class="bi bi-trash3-fill"></i></div>
    <p class="adj-confirm-title">¿Eliminar documento?</p>
    <p class="adj-confirm-msg">Esta acción no se puede deshacer.</p>
    <div class="adj-confirm-btns">
      <button class="btn-cancel" id="adj-btn-cancel">Cancelar</button>
      <button class="btn-ok"     id="adj-btn-ok">Sí, eliminar</button>
    </div>
  </div>
</div>

<script>
  var _adjDeleteUrl = null;

  $(".eliminar_ad").click(function(e){
    e.preventDefault();
    var adj = $(this).attr('data-adj');
    _adjDeleteUrl = "php/eliminar_adjuntos.php?id_ad=" + adj +
      "&cod_colegio=<?= htmlspecialchars($_GET['codigo']) ?>" +
      "&periodo=<?= htmlspecialchars($_GET['periodo']) ?>";
    $("#adj-overlay").addClass("open");
  });

  $("#adj-btn-cancel").click(function(){
    $("#adj-overlay").removeClass("open");
    _adjDeleteUrl = null;
  });

  $("#adj-btn-ok").click(function(){
    if (_adjDeleteUrl) window.location = _adjDeleteUrl;
  });
</script>
