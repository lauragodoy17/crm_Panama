<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Solicitar OP</title>
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
                 
                    <h4>Solicitar OP</h4>
                 
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Ordenes de pedido
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
            
            <div class="row">

              <?php

                if (isset($_GET['id_pedido'])){

                  $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, z.zona, c.colegio, u.nombres, u.apellidos FROM pedidos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo WHERE pe.id='".$_GET['id_pedido']."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                }

                if (isset($_GET['id_pedido_dist'])){

                  $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.colegio, u.nombres, u.apellidos FROM pedidos2 pe  JOIN usuarios u ON u.id=pe.id_usuario WHERE pe.id='".$_GET['id_pedido_dist']."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                }

                if (isset($_GET['id_muestreo'])){

                  $sql_pedido="SELECT pe.fecha,pe.observaciones, z.zona, c.colegio, u.nombres, u.apellidos FROM muestreos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo WHERE pe.id='".$_GET['id_muestreo']."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                }

                if (isset($_GET['id_devol_c'])){

                  $sql_pedido="SELECT pe.fecha,pe.observaciones, c.cliente FROM devoluciones pe JOIN clientes c ON pe.persona=c.id WHERE pe.id='".$_GET['id_devol_c']."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                }

                if (isset($_GET['id_devol_p'])){

                  $sql_pedido="SELECT pe.fecha,pe.observaciones, c.cliente FROM devoluciones_prov pe JOIN clientes c ON pe.persona=c.id WHERE pe.id='".$_GET['id_devol_p']."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                }

                if (isset($_GET['id_devol_v'])){

                  $sql_pedido="SELECT pe.fecha,pe.observaciones, c.cliente FROM devoluciones_v pe JOIN clientes c ON pe.cliente=c.id WHERE pe.id='".$_GET['id_devol_v']."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                }

                if (isset($_POST['pedidos_agp'])){
                  
                  foreach ($_POST['pedidos_agp'] as $pedido_agp) {

                    $sql_pedido="SELECT u.nombres, u.apellidos, z.zona FROM pedidos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo WHERE pe.id='".$pedido_agp."'";

                    $req_pedido = $bdd->prepare($sql_pedido);
                    $req_pedido->execute();
                    $pedido = $req_pedido->fetch();

                    break;
                  }
                  
                }

              ?>

              <?php if (isset($_GET['id_pedido'])) { ?>
                <h4>OP para pedido de venta:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Pedido: <?php echo $_GET["id_pedido"] ?></td>
                    <td>Colegio: <?php echo $pedido["colegio"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
                  <tr>
                    <td>Zona: <?php echo $pedido["zona"] ?></td>
                    <td>Promotor: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                    <td>Fecha de recogida: <?php echo $pedido["fecha_r"];?></td>
                  </tr>
                </table>
              <?php } ?>

              <?php if (isset($_GET['id_pedido_dist'])) { ?>
                <h4>OP para pedido de distribuidor:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Pedido distribuidor: <?php echo $_GET["id_pedido_dist"] ?></td>
                    <td>Colegio: <?php echo $pedido["colegio"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
                  <tr>
                    <td>Distribuidor: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                      <td>Fecha de recogida: <?php echo $pedido["fecha_r"];?></td>
                  </tr>
                </table>
              <?php } ?>

              <?php if (isset($_GET['id_muestreo'])) { ?>
                <h4>OP para muestras:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Muestras: <?php echo $_GET["id_muestreo"] ?></td>
                    <td>Colegio: <?php echo $pedido["colegio"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
                  <tr>
                    <td>Zona: <?php echo $pedido["zona"] ?></td>
                    <td>Promotor: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                  </tr>
                </table>
              <?php } ?>

              <?php if (isset($_GET['id_devol_c'])) { ?>

                <h4>OP para Devoluciones de cliente:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Devolucion de cliente: <?php echo $_GET["id_devol_c"] ?></td>
                    <td>Cliente: <?php echo $pedido["cliente"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
           
                </table>
              <?php } ?>

              <?php if (isset($_GET['id_devol_p'])) { ?>

                <h4>OP para devoluciones de proveedor:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Devolucion de proveedor: <?php echo $_GET["id_devol_p"] ?></td>
                    <td>Cliente: <?php echo $pedido["cliente"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
           
                </table>
              <?php } ?>

              <?php if (isset($_GET['id_devol_v'])) { ?>

                <h4>OP para devoluciones de ventas:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Devolucion de venta: <?php echo $_GET["id_devol_v"] ?></td>
                    <td>Cliente: <?php echo $pedido["cliente"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
           
                </table>
              <?php } ?>

              <?php if (isset($_POST['pedidos_agp'])) { ?>
                <h4>OP para pedido de venta agrupado:</h4>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Pedidos:
                      <?php

                        foreach ($_POST['pedidos_agp'] as $pedido_agp) {

                          echo $pedido_agp.", ";

                        }


                      ?>
                  
                    </td>
                    <td>Zona: <?php echo $pedido["zona"] ?></td>
                    <td>Promotor: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                       
                  </tr>
                </table>
              <?php } ?>



              <div class="col-sm-4">
                <!-- PAGE CONTENT BEGINS -->
                <form name="crear_colegio" role="form" action="php/crear_op.php" method="POST" enctype="multipart/form-data">
                  <div class="form-group" for="tipo_doc">
                    <label>Tipo de documento<small style="color:red;"> *</small> </label>
                    <select class="form-control custom-select2" name="tipo_doc" id="tipo_doc" style="width: 100%;" required>
                      <option selected="selected" value="">Seleccionar</option>
                        <?php 
                                   
                          $sql = "SELECT * FROM tipo_doc WHERE act=1";

                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $tipo_docs = $req->fetchAll();

                          foreach ($tipo_docs as $tipo_doc) {
                                        
                            echo '<option value="'.$tipo_doc["id"].'">'.$tipo_doc["tipo"].' ('.$tipo_doc["descrip"].')</option>';
                          }

                        ?>
                      </select>
                  </div>
              </div>

              
              <div class="col-sm-4">
                
                  <div class="form-group" for="cliente">
                    <label>Cliente<small style="color:red;"> *</small> </label>
                    <select class="form-control custom-select2" name="cliente" id="cliente" style="width: 100%;" required>
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

              <div class="col-sm-4">
                <!-- PAGE CONTENT BEGINS -->              
                <div class="form-group">
                  <label class="control-label no-padding-right" for="solicitante"> Contacto</label>

                  <input type="text" name="solicitante" id="solicitante" placeholder="Contacto" class="form-control" />
                    
                </div>
              </div>

          

            </div>

            <div class="row">
              
              <?php if (!isset($_GET['id_pedido']) && !isset($_GET['id_pedido_dist']) && !isset($_GET['id_devol_c']) && !isset($_GET['id_devol_p']) && !isset($_GET['id_devol_v']) && !isset($_GET['id_muestreo']) && !isset($_POST['pedidos_agp']) ) { ?>
              <div class="col-sm-4">
                
                <div class="form-group">
                  <label class="control-label no-padding-right" for="archivo"> Archivo Adjunto</label>

                  <input type="file" name="archivo" id="archivo" placeholder="Adjunto" class="form-control" />
                    
                </div>
              </div>
              <?php } ?>

              <!--<div class="col-sm-4 hidden" id="div_per">
                <div class="form-group">
                  <label class="control-label no-padding-right" for="op_per"> #OP Personalizado</label>
                  <input type="tel" name="op_per" id="op_per" placeholder="#OP Personalizado" class="form-control"/>
                              
                </div>
              </div>-->

              <div class="col-sm-4">
                <!-- PAGE CONTENT BEGINS -->              
                <div class="form-group">
                  <label class="control-label no-padding-right" for="ciudad_d"> Ciudad destino <small style="color:red;"> *</small></label>

                  <input required type="text" name="ciudad_d" id="ciudad_d" placeholder=" Ciudad destino" class="form-control" required />
                    
                </div>
              </div>
              
              <div class="col-sm-4 ">
                              
                <div class="form-group">
                  <label class="control-label no-padding-right" for="observaciones"> Observaciones</label>

                  <textarea cols="25" rows="5" name="observaciones" id="observaciones" class="form-control"></textarea>
                
                </div>
              </div>

              
            </div>

            <input type="hidden" name="cod_zona" value="<?php echo $zona["codigo"] ?>">

            <?php if (isset($_GET['id_pedido'])) { ?>
              <input type="hidden" name="id_pedido" value="<?php echo $_GET['id_pedido'] ?>">

            <?php } ?>

            <?php if (isset($_GET['id_pedido_dist'])) { ?>
              <input type="hidden" name="id_pedido_dist" value="<?php echo $_GET['id_pedido_dist'] ?>">

            <?php } ?>

            <?php if (isset($_GET['id_muestreo'])) { ?>
              <input type="hidden" name="id_muestreo" value="<?php echo $_GET['id_muestreo'] ?>">

            <?php } ?>

            <?php if (isset($_GET['id_devol_c'])) { ?>
              <input type="hidden" name="id_devol_c" value="<?php echo $_GET['id_devol_c'] ?>">

            <?php } ?>

            <?php if (isset($_GET['id_devol_p'])) { ?>
              <input type="hidden" name="id_devol_p" value="<?php echo $_GET['id_devol_p'] ?>">

            <?php } ?>

            <?php if (isset($_GET['id_devol_v'])) { ?>
              <input type="hidden" name="id_devol_v" value="<?php echo $_GET['id_devol_v'] ?>">

            <?php } ?>

            <?php if (isset($_POST['pedidos_agp'])) { ?>

              <?php foreach ($_POST['pedidos_agp'] as $pedidoa) { ?>
                              <input type="hidden" name="pedidos_agp[]" value="<?php echo $pedidoa ?>">
                            <?php } ?>

            <?php } ?>


            
            <!--<center><h3>Te quedan<div id="time"></div> Segundos</h3><br>-->
            <center><button class="btn btn-primary">Guardar</button></center>
            </form>
            <hr>

          </div>
            
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

    // Tamaño maximo del archivo
      const maxSize = 10000000; 

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
