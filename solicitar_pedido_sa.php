<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />

      <title>Inkpulse - Solicitar pedido sin adopción</title>
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
                  <h4>Solicitar pedido sin adopción</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos sin adopción
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
            <form action="php/pedido_sa.php" method="POST" id="miFormulario" enctype="multipart/form-data">
            <div class="row">
              
              
              <div class="col-sm-6">
                <label  for="colegio" class="col-sm-3 control-label">Colegio<small style="color:red;"> *</small></label>
                <input type="text" class="form-control" name="colegio" id="colegio" required>
              </div>
              <div class="form-group col-sm-6">
                <label for="fac_rem" class="control-label">Factura o Remisión:<small style="color:red;"> *</small></label>
                <select name="fac_rem" id="fac_rem" class="form-control" required>
                  <option value="0">Seleccionar</option>
                  <option value="1">Factura</option>
                  <option value="2">Remisión</option>
                    
                </select>
              </div>

              <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="archivo"> Archivo Adjunto</label>

                    <input type="file" name="archivo" id="archivo" placeholder="Adjunto" class="form-control" />
                    
                  </div>
                </div>

            </div>
           
                <div class="otro_l">

                  <br><h4>Libro #1:</h4>
                  <div class="row">
                    <div class="form-group col-sm-3">
                      <label id="l_materia" for="materia" class="control-label">Materia:<small style="color:red;"> *</small></label>
                      <select name="materia[]" id="materia" class="form-control">
                        <option value="">Seleccionar</option>
                        <?php 
                          $sql = "SELECT id, materia FROM materias";

                          $req = $bdd->prepare($sql);
                          $req->execute();
                          $colegios = $req->fetchAll();

                          foreach($colegios as $colegio) {
                              $id = $colegio['id'];
                              $nom = $colegio['materia'];
                              echo '<option value="'.$id.'">'.$nom.'</option>';
                          }
                        ?>
                      </select>
                    </div>

                    <div class="form-group col-sm-3">
                      <label id="l_libro" for="libro" class="control-label">Libro:<small style="color:red;"> *</small></label>
                  
                          <select name="libro" id="libro" class="form-control custom-select2"></select>
                    </div>

                    <div class="form-group col-sm-3">
                      <label id="l_descuento" for="descuento" class="control-label">Descuento %<small style="color:red;"> *</small></label>
                      <input type="number" class="form-control" name="descuento" id="descuento">
                    </div>
            

                    <div class="form-group col-sm-3">
                      <label id="l_cantidad" for="cantidad" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                      <input type="number" class="form-control cantidad" name="cantidad" id="cantidad">
                    </div>
                  </div>
                  <di id="ls_pri_sec"></di> 
                  <input type="hidden" name="libro_e[]" id="libro_e">

                  <?php for ($i=1; $i < 100; $i++) { ?>

                    <div id="agg_l<?php echo $i;?>" class="d-none">
                      <h4>Libro #<?php echo $i+1;?>:</h4>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label id="l_materia<?php echo $i;?>" for="materia<?php echo $i;?>" class="control-label">Materia:<small style="color:red;"> *</small></label>
                          <select name="materia[]" id="materia<?php echo $i;?>" class="form-control">
                            <option value="">Seleccionar</option>
                            <?php 
                              $sql = "SELECT id, materia FROM materias";

                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $colegios = $req->fetchAll();

                              foreach($colegios as $colegio) {
                                  $id = $colegio['id'];
                                  $nom = $colegio['materia'];
                                  echo '<option value="'.$id.'">'.$nom.'</option>';
                              }
                            ?>
                          </select>
                        </div>
                        <div class="form-group col-sm-3">
                          <label id="l_libro<?php echo $i;?>" for="libro<?php echo $i;?>" class="control-label">Libro:<small style="color:red;"> *</small></label>
                      
                              <select name="libro" id="libro<?php echo $i;?>" class="form-control custom-select2" width="200"></select>
                        </div>

                        <div class="form-group col-sm-3">
                          <label id="l_descuento<?php echo $i;?>" for="descuento" class="control-label">Descuento %<small style="color:red;"> *</small></label>
                          <input type="number" class="form-control" name="descuento" id="descuento<?php echo $i;?>">
                        </div>

                        <div class="form-group col-sm-3">
                          <label id="l_cantidad<?php echo $i;?>" for="cantidad1" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                          <input type="number" class="form-control cantidad" name="cantidad" id="cantidad<?php echo $i;?>">
                        </div>
                      </div>
                      <di id="ls_pri_sec<?php echo $i;?>"></di>
                  
                      <input type="hidden" name="libro_e[]" id="libro_e<?php echo $i;?>">
                    </div>
                  <?php } ?>
                </div>

                <a id="agregar_libro" style="cursor: pointer;">Agregar libro +</a><br>

                <center>
            <div class="col-sm-3">
              <label for="fecha_r">Fecha de Recogida:<small style="color:red;"> *</small></label>
              <div class="input-group">
                <input type="text" class="form-control date-picker" name="fecha_r" id="fecha_r" type="text" data-date-format="yyyy-mm-dd" required="" autocomplete="off" />
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div><br>
            </div>
            <div class="col-sm-6">
              <label for="observaciones">Observaciones:</label><br>
              <textarea name="observaciones" id="observaciones" cols="100" rows="9" class="form-control"></textarea><br><br>
            </div>
             
              <button class="btn btn-primary" id="solicitar">Vista previa</button></center>
                </div>


            
                

              </div><!-- /.col -->
            </div><!-- /.row -->
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

    <script>
      $('#materia').on('change',function(){
      var valor = $(this).val();
      //alert(valor);
      var dataString = 'mat_gra='+valor;
              
      $.ajax({

        url: "ajax/buscar_l_eureka_sp.php",
        type: "POST",
        data: dataString,
        dataType: "html",
        success: function (resp) {
                   
            $("#libro").html(resp);                        
            //console.log(resp);
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

      $('#libro').on('change',function(){
        var cant =$('#cantidad').val();
        var libro=$('#libro').val();
        var desc=$('#descuento').val();
        var grado = $('#libro option:selected').attr('data-grado');
        

        if (grado==15 || grado==16) {
          $('#l_cantidad').addClass("d-none");
          $('#cantidad').addClass("d-none");
          $('#l_descuento').addClass("d-none");
          $('#descuento').addClass("d-none");
          
          var dataString = 'pri_sec='+libro;
                  
          $.ajax({

              url: "ajax/buscar_pri_sec.php",
              type: "POST",
              data: dataString,
              dataType: "html",
              success: function (resp) {
                  $("#ls_pri_sec").html('');
                  $("#ls_pri_sec").append(resp);                       
                  console.log(resp);
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

        }else{
        
          $('#libro_e').val(libro+'/'+cant+'/'+desc);
        }

        

      })

    $('#cantidad').keyup(function(){
      var cant =$('#cantidad').val();
      var libro=$('#libro').val();
      var desc=$('#descuento').val();
      var grado = $('#libro option:selected').attr('data-grado');
          
      if (grado!=15 || grado!=16) {
        $('#libro_e').val(libro+'/'+cant+'/'+desc);
      }
    
    })

    $('#descuento').keyup(function(){
      var cant =$('#cantidad').val();
      var libro=$('#libro').val();
      var desc=$('#descuento').val();
      var grado = $('#libro option:selected').attr('data-grado');
          
      if (grado!=15 || grado!=16) {
        $('#libro_e').val(libro+'/'+cant+'/'+desc);
      }
    
    })

  

  var m = 1;

  $("#agregar_libro").click(function(){
    if (m>98) {
      $("#agregar_libro").addClass("d-none");
    }
    
    $("#agg_l"+m).removeClass("d-none")

    m++;
    <?php for ($i=1; $i < 100; $i++) { ?>

      $('#materia<?php echo $i; ?>').on('change',function(){
          var valor = $(this).val();
          //alert(valor);
          var dataString = 'mat_gra='+valor;
                  
          $.ajax({

              url: "ajax/buscar_l_eureka_sp.php",
              type: "POST",
              data: dataString,
              dataType: "html",
              success: function (resp) {
                     
                  $("#libro<?php echo $i; ?>").html(resp);                        
                  //console.log(resp);
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

    

      $('#libro<?php echo $i; ?>').on('change',function(){
        var cant =$('#cantidad<?php echo $i; ?>').val();
        var libro=$('#libro<?php echo $i; ?>').val();
        var desc=$('#descuento<?php echo $i; ?>').val();
        var grado = $('#libro<?php echo $i; ?> option:selected').attr('data-grado');
        

        if (grado==15 || grado==16) {
          $('#l_cantidad<?php echo $i; ?>').addClass("d-none");
          $('#cantidad<?php echo $i; ?>').addClass("d-none");
          $('#l_descuento<?php echo $i; ?>').addClass("d-none");
          $('#descuento<?php echo $i; ?>').addClass("d-none");
          
          var dataString = 'pri_sec='+libro;
                  
          $.ajax({

              url: "ajax/buscar_pri_sec.php",
              type: "POST",
              data: dataString,
              dataType: "html",
              success: function (resp) {
                  $("#ls_pri_sec<?php echo $i; ?>").html('');
                  $("#ls_pri_sec<?php echo $i; ?>").append(resp);                       
                  console.log(resp);
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

        }else{
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant+'/'+desc);
        }

        

      })

      $('#cantidad<?php echo $i; ?>').keyup(function(){
        var cant =$('#cantidad<?php echo $i; ?>').val();
        var libro=$('#libro<?php echo $i; ?>').val();
        var desc=$('#descuento<?php echo $i; ?>').val();
        var grado = $('#libro option:selected').attr('data-grado');
          
        if (grado!=15 || grado!=16) {
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant+'/'+desc);
        }    

      })

      $('#descuento<?php echo $i; ?>').keyup(function(){
        var cant =$('#cantidad<?php echo $i; ?>').val();
        var libro=$('#libro<?php echo $i; ?>').val();
        var desc=$('#descuento<?php echo $i; ?>').val();
        var grado = $('#libro option:selected').attr('data-grado');
          
        if (grado!=15 || grado!=16) {
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant+'/'+desc);
        }    

      })

    <?php } ?>
      

      
  })
    </script>
    
  </body>
</html>
