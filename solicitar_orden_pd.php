<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Solicitar Orde de producción</title>
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

    <style>
      input[type=number] { -moz-appearance:textfield; }
      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
      }
      .custom-select2 {
        width:  auto !important;
      }
    </style>
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
                 
                    <h4>Solicitar orden de producción</h4>
                 
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Orden de producción
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                     
                        Solicitar
                      
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <form action="php/orden_produccion.php" method="POST" id="formul" enctype="multipart/form-data">

              <div class="row">
                <div class="col-sm-3">
                  <label  for="solicitante" class="control-label">Solicitante<small style="color:red;"> *</small></label>
                  <input type="text" class="form-control" name="solicitante" id="solicitante" required>
                </div>


                <div class="col-sm-4">
                  
                  <div class="form-group" for="cliente">
                    <label>Cliente</label>
                    <select class="form-control custom-select2" name="cliente" id="cliente" style="width: 100%;">
                      <option selected="selected" value="">Seleccionar</option>
                        <?php 

                          $sql = "SELECT * FROM clientes";

                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $clientes = $req->fetchAll();

                          foreach ($clientes as $cliente) {
                                        
                            echo '<option value="'.$cliente["id"].'">'.$cliente["cliente"].'</option>';
                          }

                        ?>
                    </select>
                  </div>

                </div>

                <div class="col-sm-3">
                  
                  <div class="form-group" for="descrip">
                    <label>Descripción pedido <small style="color:red;"> *</small></label>
                    <select class="form-control" name="descrip" id="descrip" style="width: 100%;" required>
                      <option selected="selected" value="">Seleccionar</option>
                      <option value="1">Libro estudiante</option>
                      <option value="2">Guía</option>
                      <option value="3">Otro</option>
                      
                    </select>
                  </div>

                </div>

              
              </div>

              <div class="row">
                
                <div class="col-sm-4">
                
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="archivo"> Archivo Adjunto</label>

                    <input type="file" name="archivo" id="archivo" placeholder="Adjunto" class="form-control" />
                      
                  </div>
                </div>

                <div class="col-sm-4 ">
                  <label for="fecha_ent_s">Fecha de entrega solicitada:<small style="color:red;"> *</small></label>
                  <div class="input-group">
                  <input type="text" class="form-control date-picker" name="fecha_ent_s" id="fecha_ent_s" type="text" data-date-format="yyyy-mm-dd" required="" autocomplete="off" />
                    <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>


              </div>

              

              <br>
            
            <div class="otro_l">

              <h4>Material #1:</h4>
              <div class="row">
                
                
                <div class="form-group col-sm-6">
                  <label id="l_titulo" for="titulo" class="control-label">Titulo<small style="color:red;"> *</small></label>
                  <input type="text" class="form-control" name="titulo" id="titulo">
                </div>

                <div class="form-group col-sm-6">
                  <label id="l_cantidad" for="cantidad" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                  <input type="number" class="form-control" name="cantidad" id="cantidad">
                </div>
              </div>
              
              <input type="hidden" name="libro_e[]" id="libro_e">

              <?php for ($i=1; $i < 100; $i++) { ?>

                <div id="agg_l<?php echo $i;?>" class="d-none">
                  <h4>Material #<?php echo $i+1;?>:</h4>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label id="l_titulo<?php echo $i;?>" for="titulo<?php echo $i;?>" class="control-label">Titulo<small style="color:red;"> *</small></label>
                      <input type="text" class="form-control" name="titulo" id="titulo<?php echo $i;?>">
                    </div>

                    <div class="form-group col-sm-6">
                      <label id="l_cantidad<?php echo $i;?>" for="cantidad<?php echo $i;?>" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                      <input type="number" class="form-control" name="cantidad" id="cantidad<?php echo $i;?>">
                    </div>                  
                  </div>
              
              
                <input type="hidden" name="libro_e[]" id="libro_e<?php echo $i;?>">
              </div>

              <?php } ?>

              

        
          <a id="agregar_libro" style="cursor: pointer;">Agregar otro +</a><br>

          <center>
            <div class="col-sm-3 col-sm-offset-2">
              

              <label for="observaciones">Observaciones:</label><br>
              <textarea name="observaciones" id="observaciones" cols="100" rows="9"></textarea><br><br>
              <button class="btn btn-primary">Solicitar</button></center>
            </div>


          </form>
            
          </div>
        </div>
        <?php include("template/footer.php"); ?>
    </div>

    
    <!-- js -->
    <script src="vendors/scripts/core.js"></script>
    <script src="vendors/scripts/script.min.js"></script>
    <script src="vendors/scripts/process.js"></script>
    <script src="vendors/scripts/layout-settings.js"></script>

    <script>
      $('#cantidad').keyup(function(){
        var cant =$('#cantidad').val();
        var titulo=$('#titulo').val();
        $('#libro_e').val(titulo+'/'+cant);

      })

      $('#titulo').keyup(function(){
        var cant =$('#cantidad').val();
        var titulo=$('#titulo').val();
        $('#libro_e').val(titulo+'/'+cant);

      })


    var m = 1;
    
    $("#agregar_libro").click(function(){
      if (m>98) {
        $("#agregar_libro").addClass("d-none");
      }
    
      $("#agg_l"+m).removeClass("d-none")

      m++;

      <?php for ($i=1; $i < 100; $i++) { ?>


        $('#cantidad<?php echo $i; ?>').keyup(function(){

          var cant =$('#cantidad<?php echo $i; ?>').val();
          var titulo=$('#titulo<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(titulo+'/'+cant);

        })

        $('#titulo<?php echo $i; ?>').keyup(function(){

          var cant =$('#cantidad<?php echo $i; ?>').val();
          var titulo=$('#titulo<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(titulo+'/'+cant);

        })
      

      <?php } ?>

      

    


      
  })

      /*$("#solicitar").click(function(){
        
                if (confirm("¿Está seguro de realizar el pedido? Por favor verificar")) {

                  if ($("#colegio").val()!="") {

                    $("#formul").submit();
                  }else{
                    alert("Falta colegio");
                  }

                    
                }

          })*/

    // Tamaño maximo del archivo
      const maxSize = 6000000; 

      // Obtener referencia al elemento
      const $miInput = document.querySelector("#archivo");

      $miInput.addEventListener("change", function () {
          // si no hay archivos, regresamos
          if (this.files.length <= 0) return;

          // Validamos el primer archivo únicamente
          const archivo = this.files[0];
          if (archivo.size > maxSize) {
              const tamanioEnMb = maxSize / 1000000;
              alert(`El tamaño máximo es ${tamanioEnMb} MB`);
              // Limpiar
              $miInput.value = "";
          } else {
              // Validación pasada. Envía el formulario o haz lo que tengas que hacer
          }
      });

    </script>
    
  </body>
</html>
