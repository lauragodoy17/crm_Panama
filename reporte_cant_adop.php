<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Reporte cantidad de adopciones</title>

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
                  <h4>Reporte cantidad de adopciones</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Reportes
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Cantidad de adopciones
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            <form id="valoriza" action="php/cant_adop_excel.php" method="POST">
            <div class="row">
             
              <?php if ($_SESSION['tipo']==1 || $_SESSION['tipo']==2 || $_SESSION['tipo']==7) { ?>
              <div class="col-sm-3">
                <!-- PAGE CONTENT BEGINS -->
                  
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="barrio"> Promotor:<small style="color:red;"> *</small> </label>

                    <select name="promotor" id="promotor" class="form-control" required>
                      <option value="">Seleccionar</option>
                      <option value="0">Todos</option>
                       <?php 
                        $sql ="SELECT id, CONCAT(nombres, ' ', apellidos) as promotor FROM usuarios WHERE (tipo=3 || tipo=6 || tipo=1 || tipo=10) AND act=1";
              
                        $req = $bdd->prepare($sql);
                        $req->execute();
                        $promos = $req->fetchAll();
              
                        foreach($promos as $promo) {
                          
                          echo '<option value="'.$promo['id'].'">'.$promo['promotor'].'</option>';
                        }
                       ?>
                    </select><br>
                  </div>
                  
              </div>
              <?php } ?>
              <div class="col-sm-3">
                <!-- PAGE CONTENT BEGINS -->
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="periodo"> Periodo:<small style="color:red;"> *</small> </label><br>
                    <select name="periodo" id="periodo" >
                      <?php  

                        $sql ="SELECT id, periodo FROM periodos ORDER BY id DESC";

                        $req = $bdd->prepare($sql);
                        $req->execute();
                        $periodos = $req->fetchAll();

                        foreach ($periodos as $periodo) {

                          echo '<option value="'.$periodo["id"].'">'.$periodo["periodo"].'</option>';
                        }

                      ?>
                    </select>
                  </div><br>
                    
                    <!--<button class="btn btn-primary" id="ver">Ver</button>-->
                    <button class="btn btn-success" id="exportar">Exportar</button>
                  </form>
                  
                  
              </div>

              <form id="valoriza" action="php/cant_adop_excel.php" method="POST">
              <?php if ($_SESSION['tipo']==1 || $_SESSION['tipo']==2 || $_SESSION['tipo']==7) { ?>
              <h4>Por consecutivo de adopción</h4><br>
              <div class="col-sm-3">
                <!-- PAGE CONTENT BEGINS -->
                  
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="barrio"> Desde:<small style="color:red;"> *</small> </label>

                    <input type="number" name="desde" class="form-control" style="width: auto" required>
                  </div>

                  <div class="form-group">
                    <label class="control-label no-padding-right" for="barrio"> Hasta:<small style="color:red;"> *</small> </label>

                    <input type="number" name="hasta" class="form-control" style="width: auto" required>
                  </div>
                  <input type="hidden" name="promotor" value="0">
              </div>
              <?php } ?>
              <div class="col-sm-3">
                <!-- PAGE CONTENT BEGINS -->
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="periodo"> Periodo:<small style="color:red;"> *</small> </label><br>
                    <select name="periodo" id="periodo">
                      <?php  

                        $sql ="SELECT id, periodo FROM periodos ORDER BY id DESC";

                        $req = $bdd->prepare($sql);
                        $req->execute();
                        $periodos = $req->fetchAll();

                        foreach ($periodos as $periodo) {

                          echo '<option value="'.$periodo["id"].'">'.$periodo["periodo"].'</option>';
                        }

                      ?>
                    </select>
                  </div><br>
                    
                    <!--<button class="btn btn-primary" id="ver">Ver</button>-->
                    <button class="btn btn-success" id="exportar">Exportar</button>
                  </form>
                  
                  
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
