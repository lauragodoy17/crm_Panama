<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Cargar proveedores</title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <style>
    /* Sección numerada */
    .cp-section {
      background: #fff; border-radius: 14px;
      box-shadow: 0 2px 10px rgba(15,23,42,.08);
      margin-bottom: 20px; overflow: hidden;
    }
    .cp-section-head {
      display: flex; align-items: center; gap: 14px;
      padding: 18px 24px; border-bottom: 1px solid #e2e8f0;
    }
    .cp-num {
      width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
      color: #fff; font-size: .85rem; font-weight: 700;
      display: flex; align-items: center; justify-content: center;
    }
    .cp-section-title { font-size: .95rem; font-weight: 700; color: #0f172a; margin: 0; }
    .cp-section-body  { padding: 24px; }

    /* Zona de carga */
    .cp-dropzone {
      border: 2px dashed #bfdbfe; border-radius: 12px;
      background: #f8faff; padding: 32px 24px;
      display: flex; flex-direction: column; align-items: center;
      gap: 10px; text-align: center; transition: border-color .2s, background .2s;
      cursor: pointer;
    }
    .cp-dropzone:hover { border-color: #2563eb; background: #eff6ff; }
    .cp-dropzone.has-file { border-color: #16a34a; background: #f0fdf4; }
    .cp-drop-icon {
      width: 52px; height: 52px; border-radius: 12px;
      background: #dbeafe; color: #1d4ed8;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem;
    }
    .cp-drop-title { font-size: .95rem; font-weight: 700; color: #1e293b; margin: 0; }
    .cp-drop-sub   { font-size: .8rem; color: #64748b; margin: 0; }
    .cp-drop-hint  { font-size: .75rem; color: #94a3b8; margin: 0; }
    #archivo { display: none; }
    .cp-file-name {
      font-size: .82rem; font-weight: 600; color: #16a34a;
      display: none; align-items: center; gap: 6px; margin-top: 4px;
    }
    .cp-file-name.visible { display: flex; }

    /* Botón cargar */
    .cp-btn {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 26px; border-radius: 8px; font-size: .95rem; font-weight: 700;
      background: linear-gradient(135deg, #1d4ed8, #2563eb);
      color: #fff; border: none; cursor: pointer;
      transition: opacity .15s, transform .1s; margin-top: 8px;
    }
    .cp-btn:hover { opacity: .9; transform: translateY(-1px); }

    /* Imagen formato */
    .cp-format-note {
      font-size: .82rem; color: #64748b; margin: 0 0 16px;
      display: flex; align-items: center; gap: 6px;
    }
    .cp-format-note i { color: #2563eb; }
    .cp-img-wrap {
      border-radius: 10px; overflow-x: auto;
      border: 1px solid #e2e8f0;
    }
    .cp-img-wrap img { display: block; max-width: 100%; height: auto; }
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
            <div class="title"><h4>Cargar proveedores</h4></div>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Devoluciones</li>
                <li class="breadcrumb-item active">Cargar proveedores</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <!-- Sección 1: Seleccionar archivo -->
      <div class="cp-section">
        <div class="cp-section-head">
          <div class="cp-num">1</div>
          <p class="cp-section-title">Selecciona el archivo Excel</p>
        </div>
        <div class="cp-section-body">
          <form action="php/cargar_proveedores.php" method="POST" enctype="multipart/form-data">
            <div class="cp-dropzone" id="dropzone" onclick="document.getElementById('archivo').click()">
              <div class="cp-drop-icon"><i class="bi bi-file-earmark-excel"></i></div>
              <p class="cp-drop-title">Seleccionar archivo</p>
              <p class="cp-drop-sub">Selecciona el archivo Excel a subir o arrástralo directo al campo</p>
              <p class="cp-drop-hint">Formato permitido: .xls, .xlsx</p>
              <span class="cp-file-name" id="fileName"><i class="bi bi-check-circle-fill"></i> <span id="fileNameText"></span></span>
            </div>
            <input type="file" name="archivo" id="archivo" accept=".xls,.xlsx" />
            <br>
            <button type="submit" class="cp-btn">
              <i class="bi bi-upload"></i> Cargar archivo
            </button>
          </form>
        </div>
      </div>

      <!-- Sección 2: Formato requerido -->
      <div class="cp-section">
        <div class="cp-section-head">
          <div class="cp-num">2</div>
          <p class="cp-section-title">Formato requerido</p>
        </div>
        <div class="cp-section-body">
          <p class="cp-format-note">
            <i class="bi bi-info-circle-fill"></i>
            Así debe verse el archivo Excel para que el sistema lo procese y almacene correctamente.
          </p>
          <div class="cp-img-wrap">
            <img src="vendors/images/excel_clientes.png" alt="Formato requerido" class="img-responsive">
          </div>
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
<script>
document.getElementById('archivo').addEventListener('change', function () {
  var dropzone  = document.getElementById('dropzone');
  var fileName  = document.getElementById('fileName');
  var fileText  = document.getElementById('fileNameText');
  if (this.files && this.files[0]) {
    fileText.textContent = this.files[0].name;
    fileName.classList.add('visible');
    dropzone.classList.add('has-file');
  } else {
    fileName.classList.remove('visible');
    dropzone.classList.remove('has-file');
  }
});

// Drag & drop
var dz = document.getElementById('dropzone');
dz.addEventListener('dragover', function (e) {
  e.preventDefault();
  this.style.borderColor = '#2563eb';
  this.style.background  = '#eff6ff';
});
dz.addEventListener('dragleave', function () {
  this.style.borderColor = '';
  this.style.background  = '';
});
dz.addEventListener('drop', function (e) {
  e.preventDefault();
  var input = document.getElementById('archivo');
  input.files = e.dataTransfer.files;
  input.dispatchEvent(new Event('change'));
});
</script>
</body>
</html>
