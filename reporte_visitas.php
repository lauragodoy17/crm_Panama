<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Reporte de visitas</title>

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
                  <h4>Reporte de visitas</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Reportes
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Visitas
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            <?php if ($_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==2 || $_SESSION["tipo"] ==5 || $_SESSION["id"] ==73) {?>
            <form action="php/visitas_general.php" method="POST">
            <?php }else{ ?>
              <form action="php/visitas_general2.php" method="POST">
            <?php } ?>
              <div class="row">
                <?php if ($_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==2 || $_SESSION["tipo"] ==5 || $_SESSION["id"] ==73) {?>
                <div class="col-sm-4">
                  
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="promo"> Usuario:<small style="color:red;"> *</small> </label>

                    <select name="promo" id="promo" class="form-control custom-select2" required>
                      <option value="">Seleccionar</option>
                      <option value="todos">Todos</option>
                      
                       <?php 
                        $sql ="SELECT id, CONCAT(nombres, ' ', apellidos) as promotor FROM usuarios WHERE (tipo=3 || tipo=4) AND act=1";
              
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
                <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="desde"> Desde:<small style="color:red;"> *</small> </label>
                      
                      <div class="input-group">
                        <input type="text" class="form-control date-picker" name="desde" id="desde" type="text" data-date-format="yyyy-mm-dd" required autocomplete="off" />
                        <span class="input-group-text">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label no-padding-right" for="hasta"> Hasta:<small style="color:red;"> *</small> </label>
                      
                      <div class="input-group">
                        <input type="text" class="form-control date-picker" name="hasta" id="hasta" type="text" data-date-format="yyyy-mm-dd" required autocomplete="off" />
                        <span class="input-group-text">
                          <i class="fa fa-calendar bigger-110"></i>
                        </span>
                      </div>
                    
                    </div>
                  </div>

              </div>       
              <button class="btn btn-primary">Exportar excel</button>
            </form>

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
