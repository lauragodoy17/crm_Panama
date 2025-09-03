<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Cargar proveedores</title>

    <!-- Site favicon -->
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="vendors/images/apple-touch-icon.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="vendors/images/favicon-32x32.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="vendors/images/favicon-16x16.png"
    />

    <!-- Mobile Specific Metas -->
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1"
    />

    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link
      rel="stylesheet"
      type="text/css"
      href="vendors/styles/icon-font.min.css"
    />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

    
  </head>
  <body>
    
    <?php include("template/nav_side.php"); ?>
    <div class="main-container">
      <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
          <div class="page-header">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="title">
                  <h4>Cargar proveedores</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Devoluciones
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Cargar proveedores
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
              
            <div class="row">
                  <form action="php/cargar_proveedores.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group" for="archivo">
                    <label class="control-label no-padding-right" for="archivo"> Archivo Excel</label>

                    <input type="file" name="archivo" id="archivo" placeholder="Adjunto" class="form-control" />
                  </div>
              </div>
              <br>
          
          <button class="btn btn-primary">Cargar arhivo</button>
          <br><h4 align="center">Formato</h4>
          <img src="vendors/images/excel_clientes.png" alt="" width="1700" class="img-responsive">
          </div><!-- /.page-content -->
           

          </div>
        
        <?php include("template/footer.php"); ?>
      </div>
    </div>
    
    <!-- js -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>
    
  </body>
</html>
