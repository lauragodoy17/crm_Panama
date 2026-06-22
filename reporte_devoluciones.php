<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Inkpulse - Reportes de devoluciones</title>
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
            <div class="title"><h4>Reportes de devoluciones</h4></div>
          </div>
        </div>
      </div>

      <p style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:10px;">
        Devoluciones de ventas
      </p>

      <div class="sm-section">
        <div class="sm-section-head">
          <span class="sm-sec-icon"><i class="bi bi-grid-3x3-gap"></i></span>
          <span class="sm-section-title">General</span>
        </div>
        <div class="sm-section-body">
          <form action="php/devol_ventas_c_excel.php" method="POST">
            <div class="row">
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Desde <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="desde" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Hasta <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="hasta" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="sm-footer">
              <button class="btn btn-primary"><i class="bi bi-download"></i> Exportar Excel</button>
            </div>
          </form>
        </div>
      </div>

      <div class="sm-section">
        <div class="sm-section-head">
          <span class="sm-sec-icon"><i class="bi bi-book"></i></span>
          <span class="sm-section-title">Libro a libro</span>
        </div>
        <div class="sm-section-body">
          <form action="php/devoluciones_excel.php" method="POST">
            <div class="row">
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Desde <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="desde" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Hasta <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="hasta" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="sm-footer">
              <button class="btn btn-primary"><i class="bi bi-download"></i> Exportar Excel</button>
            </div>
          </form>
        </div>
      </div>

      <p style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:10px; margin-top:6px;">
        Devoluciones de muestras
      </p>

      <div class="sm-section">
        <div class="sm-section-head">
          <span class="sm-sec-icon green"><i class="bi bi-box-seam"></i></span>
          <span class="sm-section-title">Muestras</span>
        </div>
        <div class="sm-section-body">
          <form action="php/devol_m_excel.php" method="POST">
            <div class="row">
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Desde <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="desde" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Hasta <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="hasta" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="sm-footer">
              <button class="btn btn-primary"><i class="bi bi-download"></i> Exportar Excel</button>
            </div>
          </form>
        </div>
      </div>

      <p style="font-size:12px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:10px; margin-top:6px;">
        Devoluciones de proveedores
      </p>

      <div class="sm-section">
        <div class="sm-section-head">
          <span class="sm-sec-icon orange"><i class="bi bi-truck"></i></span>
          <span class="sm-section-title">Proveedores</span>
        </div>
        <div class="sm-section-body">
          <form action="php/devol_p_excel.php" method="POST">
            <div class="row">
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Desde <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="desde" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-12 mb-3">
                <label class="control-label">Hasta <small style="color:red;">*</small></label>
                <div class="input-group">
                  <input type="text" class="form-control date-picker" name="hasta" data-date-format="yyyy-mm-dd" required autocomplete="off" placeholder="Seleccionar fecha" />
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="sm-footer">
              <button class="btn btn-primary"><i class="bi bi-download"></i> Exportar Excel</button>
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
