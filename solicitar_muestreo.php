<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if ($_GET['tp']!=2) { ?>
      <title>Inkpulse - Solicitar muestreo</title>
    <?php }else{ ?>
      <title>Inkpulse - Entregar muestras</title>
    <?php } ?>
    

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
                  <?php if ($_GET['tp']!=2) { ?>
                    <h4>Solicitar muestreo</h4>
                  <?php }else{ ?>
                    <h4>Entregar muestras</h4>
                  <?php } ?>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Muestreo
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php if ($_GET['tp']!=2) { ?>
                        Solicitar
                      <?php }else{ ?>
                        Entregar
                      <?php } ?>
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            <form action="php/crear_muestreo.php" method="POST" id="miFormulario">
            <div class="row">
              
              <div class="col-sm-10">
                <?php if (!isset($_GET['colegio'])) { ?>
                  <div class="form-group ocultar_oficina">
                    <div class="form-group col-sm-12">
                      <label  for="cole" class="control-label">Colegio:<small style="color:red;"> *</small></label>
                
                      <select name="cole" id="cole" class="form-control custom-select2" required>
                        <option value="">Seleccione</option>
                          <?php

                            if ($_SESSION["tipo"]==1 || $_SESSION["tipo"]==4 || $_SESSION["id"] == 10) {
                            $sql = "SELECT id,colegio FROM colegios WHERE colegio like'%".$colegio."%'";
                          }
                          elseif ($_SESSION["tipo"]==3) {

                            $sql = "SELECT id,colegio FROM colegios WHERE colegio like'%".$colegio."%' AND cod_zona='".$_SESSION["zona"]."'";
                          }else{
                            $sql = "SELECT id,colegio FROM colegios WHERE colegio like'%".$colegio."%' AND cod_zona='".$_SESSION["zona"]."' OR zona_madre='".$_SESSION["zona"]."'";
                          }
    
                          $req = $bdd->prepare($sql);
                          $req->execute();
                          $colegios = $req->fetchAll();

                          foreach($colegios as $colegio) {

                            echo "<option value='".$colegio["id"]."'>".$colegio["colegio"]."</option>";
      
                          }
                          ?>
                      </select>
                    </div><br>
                    
                  </div>
                </div>

              </div>

                <?php }else {

                  $sql = "SELECT codigo, colegio FROM colegios WHERE id='".$_GET['colegio']."'";

                  $req = $bdd->prepare($sql);
                  $req->execute();

                  $colegio = $req->fetch();
                  ?>
                  <h3>Colegio: <?php echo $colegio['colegio']; ?></h3><br>
                  <input type="hidden" name="cole" id="cole" value="<?php echo $_GET['colegio'] ?>">
                  <input type="hidden" name="cod_cole" id="cod_cole" value="<?php echo $colegio['codigo'] ?>">
                <?php } ?>
                <div class="otro_l">

                  <br><h4>Libro #1:</h4>
                  <div class="row">
                    <div class="form-group col-sm-4">
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
                    <div class="form-group col-sm-4">
                      <label id="l_libro" for="libro" class="control-label">Libro:<small style="color:red;"> *</small></label>
                  
                          <select name="libro" id="libro" class="form-control custom-select2"></select>
                    </div>

                    
            

                    <div class="form-group col-sm-4">
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
                        <div class="form-group col-sm-4">
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
                        <div class="form-group col-sm-4">
                          <label id="l_libro<?php echo $i;?>" for="libro<?php echo $i;?>" class="control-label">Libro:<small style="color:red;"> *</small></label>
                      
                              <select name="libro" id="libro<?php echo $i;?>" class="form-control custom-select2" width="200"></select>
                        </div>

                        <div class="form-group col-sm-4">
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
                <div class="col-sm-3 col-sm-offset-4">
                  <label for="observaciones">Observaciones:</label><br>
                  <textarea name="observaciones" id="observaciones" cols="30" rows="3"></textarea><br><br>
                  <input type="hidden" name="tp"  value="<?php echo  $_GET['tp'] ?>">
                  <button class="btn btn-primary" id="solicitar">Solicitar</button></center>
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
        var grado = $('#libro option:selected').attr('data-grado');
        

        if (grado==15 || grado==16) {
          $('#l_cantidad').addClass("d-none");
          $('#cantidad').addClass("d-none");
          
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
          $('#libro_e').val(libro+'/'+cant);
        }

        

      })

    $('#cantidad').keyup(function(){
      var cant =$('#cantidad').val();
      var libro=$('#libro').val();
      var grado = $('#libro option:selected').attr('data-grado');
          
      if (grado!=15 || grado!=16) {
        $('#libro_e').val(libro+'/'+cant);
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
        var grado = $('#libro<?php echo $i; ?> option:selected').attr('data-grado');
        

        if (grado==15 || grado==16) {
          $('#l_cantidad<?php echo $i; ?>').addClass("d-none");
          $('#cantidad<?php echo $i; ?>').addClass("d-none");
          
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
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant);
        }

        

      })

      $('#cantidad<?php echo $i; ?>').keyup(function(){
        var cant =$('#cantidad<?php echo $i; ?>').val();
        var libro=$('#libro<?php echo $i; ?>').val();
        var grado = $('#libro option:selected').attr('data-grado');
          
        if (grado!=15 || grado!=16) {
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant);
        }
        



      })

    <?php } ?>
      

      
  })
    </script>
    
  </body>
</html>
