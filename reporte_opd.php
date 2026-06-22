<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Reporte de OPD</title>
  <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css" />
  <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
</head>
<body>

<?php include("template/nav_side.php"); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-sm-12">
            <div class="title"><h4>Reporte Órdenes de Producción</h4></div>
          </div>
        </div>
      </div>

      <div class="sm-section">
        <div class="sm-section-head">
          <span class="sm-sec-icon green"><i class="bi bi-file-earmark-arrow-down"></i></span>
          <span class="sm-section-title">Parámetros del reporte</span>
        </div>
        <div class="sm-section-body">
          <p style="font-size:13px; color:#6b7280; margin-bottom:18px;">
            Selecciona el rango de fechas para exportar las órdenes de producción en formato Excel.
          </p>
          <form action="php/opd_excel.php" method="POST">
            <div class="row">
              <div class="col-md-3 col-12 mb-3">
                <label class="control-label">Desde <span style="color:#dc2626">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="desde" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-12 mb-3">
                <label class="control-label">Hasta <span style="color:#dc2626">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="hasta" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="sm-footer">
              <button type="submit" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
              </button>
            </div>
          </form>
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
