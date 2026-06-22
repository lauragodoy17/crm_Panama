<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Reportes de OP</title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
  <style>
    .op-actions { display:flex; gap:12px; flex-wrap:wrap; }
    .op-actions .btn { display:inline-flex; align-items:center; gap:6px; padding:10px 24px; font-size:14px; font-weight:600; border-radius:8px; }
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
            <div class="title"><h4>Reportes de OP</h4></div>
          </div>
        </div>
      </div>

      <div class="sm-section">
        <div class="sm-section-head">
          <span class="sm-sec-icon"><i class="bi bi-file-earmark-excel"></i></span>
          <span class="sm-section-title">Exportar órdenes de producción</span>
        </div>
        <div class="sm-section-body">
          <p style="font-size:13px; color:#6b7280; margin-bottom:18px;">
            Selecciona el estado de las órdenes que deseas exportar en formato Excel.
          </p>
          <div class="op-actions">
            <a href="php/oppend_excel.php" class="btn btn-warning">
              <i class="bi bi-hourglass-split"></i> Pendientes
            </a>
            <a href="php/opaten_excel.php" class="btn btn-success">
              <i class="bi bi-check-circle"></i> Atendidas
            </a>
            <a href="php/opanu_excel.php" class="btn btn-danger">
              <i class="bi bi-x-circle"></i> Anuladas
            </a>
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
</body>
</html>
