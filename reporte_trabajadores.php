<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Reporte trabajadores colegios</title>

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
                  <h4>Reporte trabajadores colegio</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Reportes
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Trabajadores colegio
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <div class="row">
              

              <!--<div class="col-sm-6">
               
                  <form action="php/trabajadores_excel.php" method="POST">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="barrio"> Por zona:<small style="color:red;"> *</small> </label>

                    <select name="zona" id="zona" class="form-control materia custom-select2" required>
                      <option value="">Seleccionar</option>
                       <?php 
                        $sql = "SELECT codigo, zona FROM zonas";
              
                        $req = $bdd->prepare($sql);
                        $req->execute();
                        $zonas = $req->fetchAll();
              
                        foreach($zonas as $zona) {
                          $codigo = $zona['codigo'];
                          $nom = $zona['zona'];
                          echo '<option value="'.$codigo.'">'.$nom.'</option>';
                        }
                       ?>
                    </select><br>
                    <center><button class="btn btn-primary">Exportar excel</button></center>
                  </div>
                  </form>
              </div>-->
              <div class="col-sm-6">
              <br><center><label>General</label><br>
                <a href="php/trabajadores_excel2.php" class="btn btn-primary">Exportar excel</a></center>
            </div>
            </div>
            

          </div>
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
