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
  <style>
    .ft-date-range { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .ft-date-label { font-size: .82rem; font-weight: 600; color: #64748b; white-space: nowrap; }
  </style>
</head>
<body>

<?php include("template/nav_side.php"); ?>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">

      <div class="page-header">
        <div class="row align-items-center">
          <div class="col-sm-12">
            <div class="title">
              <h4><i class="bi bi-file-earmark-arrow-down mr-2" style="color:#16a34a"></i>Reporte Órdenes de Producción</h4>
            </div>
          </div>
        </div>
      </div>

      <div class="modern-card" style="padding: 28px 32px">
        <p style="font-size:.82rem; color:#64748b; margin-bottom:20px;">
          Selecciona el rango de fechas para exportar las órdenes de producción en formato Excel.
        </p>
        <form action="php/opd_excel.php" method="POST">
          <div class="row align-items-end">
            <div class="col-md-3 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="desde">Desde <span style="color:#dc2626">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="desde" id="desde" data-date-format="yyyy-mm-dd" required autocomplete="off">
                  <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="hasta">Hasta <span style="color:#dc2626">*</span></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="hasta" id="hasta" data-date-format="yyyy-mm-dd" required autocomplete="off">
                  <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
                </div>
              </div>
            </div>
            <div class="col-md-2 col-sm-6">
              <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">
                  <i class="bi bi-file-earmark-excel mr-1"></i> Exportar
                </button>
              </div>
            </div>
          </div>
        </form>
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
