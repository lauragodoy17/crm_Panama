<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Crear colegio</title>

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
                  <h4>Crear colegio</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Zonificación
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Crear colegio
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            <form name="crear_colegio" role="form" action="php/crear_colegio.php" method="POST">
            <div class="row">
              <div class="col-sm-4">
                <!-- PAGE CONTENT BEGINS -->
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="dane"> DANE: <small style="color:red;"> *</small></label>

                    
                      <input type="text" name="dane" id="dane" class="form-control" required/>
                    
                  </div>
              </div>
              <div class="col-sm-4">
                
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="colegio"> Nombre:<small style="color:red;"> *</small> </label>

                    
                      <input required type="text" name="colegio" id="colegio" placeholder="Nombre del colegio" class="form-control" />
                    
                  </div>
              </div>

              <div class="col-sm-4">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="calendario"> Calendario:<small style="color:red;"> *</small> </label>

                    <select name="calendario" class="form-control" required>
                      <option value="">Seleccione</option>
                        <?php

                          $sql = "SELECT * FROM calendarios WHERE act=1";

                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $calendarios = $req->fetchAll();

                          foreach ($calendarios as $calendario) {
                                            
                            echo '<option value="'.$calendario["id"].'">'.$calendario["calendario"].'</option>';
                                         
                          }

                        ?>
                    </select>
                    
                  </div>
              </div>
              
            </div>

            <div class="row">
              <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="departamento"> Departamento:<small style="color:red;"> *</small> </label>

                    <select name="departamento" class="form-control custom-select2" required>
                      <option value="">Seleccione</option>
                        <?php

                          $sql = "SELECT * FROM departamentos";

                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $departamentos = $req->fetchAll();

                          foreach ($departamentos as $departamento) {
                                            
                            echo '<option value="'.$departamento["id"].'">'.$departamento["departamento"].'</option>';
                                         
                          }

                        ?>
                    </select>
                    
                  </div>
              </div>

              <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="ciudad"> Ciudad:<small style="color:red;"> *</small> </label>

                    
                      <input required type="text" name="ciudad" id="ciudad" placeholder="" class="form-control" />
                    
                  </div>
              </div>
              
      
            </div>
            
            <div class="row">
              <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="direccion"> Dirección:<small style="color:red;"> *</small> </label>

                    
                      <input required type="text" name="direccion" id="direccion" placeholder="" class="form-control" />
                    
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="telefono"> Teléfono:<small style="color:red;"> *</small> </label>

                    
                      <input required type="tel" name="telefono" id="telefono" placeholder="" class="form-control" />
                    
                  </div>
              </div>
              <div class="col-sm-3">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="barrio"> Barrio: </label>

                    
                      <input type="text" name="barrio" id="barrio" placeholder="" class="form-control" />
                    
                  </div>
              </div>
            </div>

            <div class="row">

              <div class="col-sm-4">
                
                <div class="form-group">
                  <label class="control-label no-padding-right" for="empresa"> Empresa:<small style="color:red;"> *</small> </label>

                  <select name="empresa" id="empresa" class="form-control custom-select2" required>
                    <option value="">Seleccione</option>
                    <option value="1">EUREKA</option>
                      <?php

                        $sql = "SELECT * FROM zonas WHERE zona NOT LIKE '%Eureka%' AND zona NOT LIKE '%ALEJANDRO%'";

                        $req = $bdd->prepare($sql);
                        $req->execute();

                        $zonas = $req->fetchAll();

                        foreach ($zonas as $zona) {
                                            
                          echo '<option value="'.$zona["codigo"].'">'.$zona["zona"].'</option>';
                                         
                        }

                      ?>
                    </select>
                    
                </div>

              </div>

              <div class="col-sm-4">
                
                <div class="form-group">
                  <label class="control-label no-padding-right" for="zona"> Zona:<small style="color:red;"> *</small> </label>

                  <select name="zona" id="zona" class="form-control custom-select2" required>
                    <option value="">Seleccione</option>
                  </select>
                    
                </div>

              </div>

              <div class="col-sm-4 col-responsable d-none">

                <div class="form-group">
                  <label class="control-label no-padding-right" for="responsable"> Responsable:<small style="color:red;"> *</small> </label>              
                  <input type="text" name="responsable" id="responsable" placeholder="" class="form-control" />
                    
                </div>  
              </div>
            </div>
            


            <center><button class="btn btn-primary">Crear colegio</button></center>
            </form>
            <hr>

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

    <script>
      
      $('#empresa').on('change',function(){
        var valor = $(this).val();
        
        if (valor==1) {
          $(".col-responsable").addClass("d-none");
          $(".col-responsable").addClass("d-none");
           $("#responsable").removeAttr("required");
        }else{
          $(".col-responsable").removeClass("d-none");
          $("#responsable").attr("required","required");
        }
       
        var dataString = 'empresa='+valor;
        $.ajax({

          url: "ajax/buscar_zona.php",
          type: "POST",
          data: dataString,
          success: function (resp) {
                   
            $("#zona").html(resp);                        
            console.log(resp);
            if(valor =="") {
              $("#zona").html("");
            }
          },
          error: function (jqXHR,estado,error){
            alert("error");
            console.log(estado);
            console.log(error);
          },
          complete: function (jqXHR,estado){
            console.log(estado);
          }

                          
        })
                
      });

    </script>
    
  </body>
</html>
