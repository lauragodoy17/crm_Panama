<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if ($_GET['tp']!=2) { ?>
      <title>Inkpulse - OP pendiente</title>
    <?php }else{ ?>
      <title>Inkpulse - OP atendida</title>
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
            
            <?php 
              

              $sql = "SELECT o.id as opid, o.op_per,o.fecha, o.n_doc, o.solicitante, o.valor, o.guia, o.fecha_entrega, o.archivo, o.observaciones, o.estado, o.transportista, o.obs_envio, o.guia, o.adjunto_envio, o.ciudad_destino, o.id_pedido, o.id_pedido_dist, o.id_muestreo, o.id_devol_c, o.id_devol_p, o.id_devol_v, o.fecha_at,o.usuario_at, o.año, t.id as tid,t.tipo,t.descrip, c.id as cid, c.cliente, c.documento, c.direccion, c.telefonos, c.ciudad, CONCAT(u.nombres,' ',u.apellidos) AS usuario, e.estado AS n_estado FROM ordenes_pedidos o JOIN tipo_doc t ON o.tipo_doc=t.id JOIN clientes c ON c.id=o.cliente JOIN usuarios u ON u.id=o.usuario JOIN estados_op e ON e.id=o.estado WHERE o.id='".$_GET["op"]."'";


              $req = $bdd->prepare($sql);
              $req->execute();

              $op = $req->fetch();

              echo '<h3 style="display: inline-block;">OP # '.$op["año"].' - '.$op["opid"].'</h3> &nbsp;&nbsp;<a style="display: inline-block;" href="formato_op.php?op='.$op["opid"].'" target="_blank" class="btn btn-info btn-sm"><i class="bi bi-printer"></i></a><br><br>';

              echo "<h4 style='display:inline-block; float: left;'><b>Estado:</b> ".$op["n_estado"]."</h4>";
              echo "<h4 style='display:inline-block; float: right;'><b>Usuario:</b> ".$op["usuario"]."</h4>";

              echo "<br><br><h5 style='display:inline-block;'><b>Fecha de creación:</b> ".$op["fecha"]."</h5>";
                  
            ?>
            <form action="php/procesar_op.php" method="POST" enctype="multipart/form-data">
            <table class="table table-bordered" style="font-size: 15px;">
              <tr>
                <td><b>Tipo de documento:</b>
                    <?php if ($op["estado"] == 2) {
                        echo $op["tipo"];
                    ?>

                    <?php }else{ ?>
                      <select name="tipo_doc" id="tipo_doc" class="form-control custom-select2">
                        <?php
                          $sql = "SELECT id, tipo FROM tipo_doc";
                          $req = $bdd->prepare($sql);
                          $req->execute();
                          $tipos= $req->fetchAll();
                          foreach ($tipos as $tipo) {
                            if ($op["tid"]==$tipo["id"]) {
                              echo "<option value='".$tipo["id"]."' SELECTED>".$tipo["tipo"]."</option>";
                            }else{
                              echo "<option value='".$tipo["id"]."'>".$tipo["tipo"]."</option>";
                            }
                            
                          }
                        ?>
                      </select>
                    <?php } ?>
                </td>
                <td><b>Número de documento:</b> <?php echo $op["n_doc"]; ?></td>
                <td><b>Cliente:</b>
                  <?php if ($op["estado"] == 2) {
                    echo $op["cliente"];
                  ?>

                  <?php }else{?>
                    <select name="cliente" id="cliente" class="custom-select2">
                      <?php
                        $sql = "SELECT id, cliente FROM clientes";
                        $req = $bdd->prepare($sql);
                        $req->execute();
                        $clientes= $req->fetchAll();
                        foreach ($clientes as $cliente) {
                          if ($op["cid"]==$cliente["id"]) {
                            echo "<option value='".$cliente["id"]."' SELECTED>".$cliente["cliente"]."</option>";
                          }else{
                            echo "<option value='".$cliente["id"]."'>".$cliente["cliente"]."</option>";
                          }
                          
                        }
                      ?>
                  </select>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><b>Contacto:</b> <?php echo $op["solicitante"]; ?></td>
                <td><b>Ciudad destino:</b> <?php echo $op["ciudad_destino"]; ?></td>
                <?php if ($op["id_pedido"]==0) {?>
                  <td><b>Archivo Adjunto:</b> <a href="adjuntos/<?php echo $op["archivo"] ?>" style="cursor: pointer;" target="_blank"><?php echo $op["archivo"] ?></a></td>
                  <!--<?php if ($op["op_per"] !=0) { ?>
                    <td><b>OP personalizada:</b> <?php echo $op["op_per"]; ?></td>
                  <?php } ?>-->
                <?php } ?>

                <?php if ($op["id_pedido"]!=0) {

                    $sql = "SELECT estado FROM pedidos WHERE id='".$op["id_pedido"]."'";
                    $req = $bdd->prepare($sql);
                    $req->execute();

                    $pedido= $req->fetch();
                    if ($pedido["estado"] ==2) {
                      echo'<td><b>Pedido de venta:</b> <a href="pedido_colegio_aprobado.php?id_pedido='.$op["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido"].'</a></td>';
                    }else{
                      echo'<td>Pedido de venta <a href="pedido_colegio_entregado.php?id_pedido='.$op["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido"].'</a></td>';
                    }

                    
                                  
                  }

                ?>

                <?php if ($op["id_pedido_dist"]!=0) {

                  $sql = "SELECT estado FROM pedidos2 WHERE id='".$op["id_pedido_dist"]."'";
                  $req = $bdd->prepare($sql);
                  $req->execute();

                  $pedido= $req->fetch();
                  if ($pedido["estado"] ==2) {
                    echo'<td><b>Pedido distribuidor:</b> <a href="pedido_colegio_aprobado2.php?id_pedido='.$op["id_pedido_dist"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido_dist"].'</a></td>';
                  }else{
                    echo'<td><b>Pedido distribuidor:</b> <a href="pedido_colegio_entregado2.php?id_pedido='.$op["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido_dist"].'</a></td>';
                  }

                    
                                  
                }

                ?>

                <?php if ($op["id_muestreo"]!=0) {

                    $sql = "SELECT estado FROM muestreos WHERE id='".$op["id_muestreo"]."'";
                    $req = $bdd->prepare($sql);
                    $req->execute();

                    $pedido= $req->fetch();
                    if ($pedido["estado"] ==2) {
                      echo'<td><b>Pedido de mustras:</b> <a href="muestreo_colegio_aprobado.php?id_pedido='.$op["id_muestreo"].'" style="cursor: pointer;" target="_blank">#'.$op["id_muestreo"].'</a></td>';
                    }else{
                      echo'<td><b>Pedido de mustras:</b> <a href="muestreo_colegio_entregado.php?id_pedido='.$op["id_muestreo"].'" style="cursor: pointer;" target="_blank">#'.$op["id_muestreo"].'</a></td>';
                    }

                    
                                  
                }

                ?>

                <?php if ($op["id_devol_c"]!=0) {

                    $sql = "SELECT estado FROM devoluciones WHERE id='".$op["id_devol_c"]."'";
                    $req = $bdd->prepare($sql);
                    $req->execute();

                    $pedido= $req->fetch();
                    
                      echo'<td><b>Devolución de muestra:</b> <a href="vista_devol.php?id_devol='.$op["id_devol_c"].'&tipo=1" style="cursor: pointer;" target="_blank">#'.$op["id_devol_c"].'</a></td>';
                    
                                  
                }

                ?>

                <?php if ($op["id_devol_p"]!=0) {

                    $sql = "SELECT estado FROM devoluciones WHERE id='".$op["id_devol_p"]."'";
                    $req = $bdd->prepare($sql);
                    $req->execute();

                    $pedido= $req->fetch();
                    
                      echo'<td><b>Devolución de proveedor:</b> <a href="vista_devol.php?id_devol='.$op["id_devol_p"].'&tipo=2" style="cursor: pointer;" target="_blank">#'.$op["id_devol_p"].'</a></td>';
                    
                                  
                }

                ?>

                <?php if ($op["id_devol_v"]!=0) {

                    $sql = "SELECT estado FROM devoluciones_v WHERE id='".$op["id_devol_v"]."'";
                    $req = $bdd->prepare($sql);
                    $req->execute();

                    $pedido= $req->fetch();
                    
                      echo'<td><b>Devolución de proveedor:</b> <a href="devolucion_colegio.php?id_pedido='.$op["id_devol_v"].'" style="cursor: pointer;" target="_blank">#'.$op["id_devol_v"].'</a></td>';
                    
                                  
                }

                ?>

                <?php if ($op["id_pedido"]==0 && $op["id_pedido_dist"]==0 && $op["id_muestreo"]==0 && $op["id_devol_c"]==0 && $op["id_devol_p"]==0 && $op["id_devol_v"]==0 ) {

                    $sql_ag = "SELECT id_pedido FROM op_pedidos_agrupados WHERE op='".$op["opid"]."'";
                    $req_ag = $bdd->prepare($sql_ag);
                    $req_ag->execute();
                    $agps = $req_ag->fetchAll();

                    echo '<td><b>Pedido de venta agrupados: </b>';
                      foreach ($agps as $agp) {

                        echo'<a href="pedido_colegio_aprobado.php?id_pedido='.$agp["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$agp["id_pedido"].'</a>, ';
                      } 
                    echo '</td>';
                                                    
                }

                ?>

                
              </tr>
              <?php if ($op["estado"] == 2) {

                $sql = "SELECT CONCAT(nombres,' ',apellidos) AS usr_aten FROM usuarios WHERE id='".$op["usuario_at"]."' ";

                $req = $bdd->prepare($sql);
                $req->execute();

                $aten= $req->fetch();


              ?>
                <tr>
                  <td><b>Fecha de atendida:</b> <?php echo $op["fecha_at"]; ?></td>
                  <td><b>Usuario atendida:</b> <?php echo $aten["usr_aten"]; ?></td>
                </tr>
              <?php } ?>
            </table>
        
            <div class="row">
              
              <div class="col-sm-3 offset-sm-4">
                              
                <h5><b>Observaciones:</b> <?php echo $op["observaciones"]; ?></h5>
              </div>

              
            </div><br><br>
            <?php if ($op["estado"] == 2) { ?>
              <table class="table table-bordered" style="font-size: 15px;">
                
                <tr>
                  <td><b>Transportista:</b> <?php echo $op["transportista"]; ?></td>
                  <td><b>Guía:</b> <?php echo $op["guia"]; ?></td>
                  <td><b>Fecha de despacho:</b> <?php echo $op["fecha_entrega"]; ?></td>
                  <td><b>Valor despachado:</b> <?php echo $op["valor"]; ?></td>
                </tr>
                
              </table>

              <div class="row">
              
              <div class="col-sm-3 offset-sm-4">
                              
                <h5><b>Observaciones de despacho:</b> <?php echo $op["obs_envio"]; ?></h5>
              </div>

              
            </div><br><br>
            
            <?php }elseif($op["estado"] == 1 && ($_SESSION['tipo']==1 || $_SESSION['tipo']==2 || $_SESSION['tipo']==5 || $_SESSION['tipo']==7) ){ ?>
              <div class="row">
                <div class="col-sm-2">
                  <!-- PAGE CONTENT BEGINS -->              
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="n_doc"> Número de documento<small style="color:red;"> *</small></label>

                    <input required type="text" name="n_doc" id="n_doc" placeholder="Documento del sistema" class="form-control" />
                      
                  </div>
                </div>

                <div class="col-sm-2">
                  <!-- PAGE CONTENT BEGINS -->              
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="recoge"> Recoge<small style="color:red;"> *</small></label>

                    <select name="recoge" id="recoge" class="form-control">
                      <option value="0">Seleccione</option>
                      <option value="1">Transportista</option>
                      <option value="2">Persona</option>
                    </select>
                      
                  </div>
                </div>

                <div class="col-sm-2">
                  <!-- PAGE CONTENT BEGINS -->              
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="transportista"> Entregado a<small style="color:red;"> *</small></label>

                    <input required type="text" name="transportista" id="transportista" placeholder="Entregado a" class="form-control" />
                      
                  </div>
                </div>

                

                <div class="col-sm-2 transp">
                  <!-- PAGE CONTENT BEGINS -->              
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="guia"> Guía</label>

                    <input type="text" name="guia" id="guia" placeholder="Guía" class="form-control" />
                      
                  </div>
                </div>

                <div class="col-sm-2">
                  <!-- PAGE CONTENT BEGINS -->              
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="fecha_entrega"> Fecha despacho<small style="color:red;"> *</small></label>

                    <input required type="text" data-date-format="yyyy-mm-dd" name="fecha_entrega" id="fecha_entrega" placeholder="Fecha despacho" class="form-control date-picker" />
                      
                  </div>
                </div>

                <div class="col-sm-2">
                  <!-- PAGE CONTENT BEGINS -->              
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="valor"> Valor despachado<small style="color:red;"> *</small></label>

                    <input required type="text" name="valor" id="valor" placeholder="Valor despachado" class="form-control" />
                      
                  </div>
                </div>

              </div>

              <div class="row">
              
              <div class="col-sm-3 offset-sm-4">
                              
                <div class="form-group">
                  <label class="control-label no-padding-right" for="obs_envio"> Observaciones despacho</label>

                  <textarea cols="25" rows="5" name="obs_envio" id="obs_envio" class="form-control"></textarea>
                
                </div>
              </div>

              
            </div>
            
            
            
            <?php if ($op["estado"] ==1 && $op["adjunto_envio"] =="") { ?>
              <div class="row">
                
                <div class="col-sm-3 offset-sm-4">
                                
                  <div class="form-group">
                    <label class="control-label no-padding-right" for="adjunto_envio"> Soporte de entrega<small style="color:red;"> *</small></label>

                    <input type="file" name="adjunto_envio" id="adjunto_envio" placeholder="Adjunto" class="form-control" required="" />
                  </div>
                </div>

                
              </div>
            <?php }elseif ($op["estado"] ==2 && $op["adjunto_envio"] !="") { ?>
              <center><b>Adjunto soporte de entrega:</b> <a href="adjuntos/envio/<?php echo $op["adjunto_envio"] ?>" style="cursor: pointer;" target="_blank"><?php echo $op["adjunto_envio"] ?></a></center>
            <?php } ?>
            <input type="hidden" name="op" value="<?php echo $op["opid"] ?>">
            <?php if ($op["estado"] ==1) { ?>
              <br><center><button class="btn btn-success">Atender</button>
              
              <a class="btn btn-danger" data-toggle="modal" data-target="#ModalAnu">Anular</a></center>
            <?php }?>
          <?php } ?>          
            </form>
            <hr>
            <?php if ($op["estado"] ==2) { ?>
            <center><b>Adjunto soporte de entrega:</b> <a href="adjuntos/envio/<?php echo $op["adjunto_envio"] ?>" style="cursor: pointer;" target="_blank"><?php echo $op["adjunto_envio"] ?></a></center>
            <?php } ?>  
            <div class="modal fade" id="ModalAnu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <form class="form-horizontal" method="POST" id="form_anu" action="php/anular_op.php">
      
                  <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Anular OP</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                  
                     <div class="form-group" style="margin: 10px">
                      <label class="control-label no-padding-right" for="motivo_anu"> Motivo de anulación <small style="color:red;"> *</small></label>

                      <textarea cols="25" rows="5" name="motivo_anu" id="motivo_anu" class="form-control" required></textarea>
                      <input type="hidden" name="op" value="<?php echo $op["opid"] ?>">
                
                    </div>
          
                  </div>

                  <div class="modal-footer">
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-danger" id="anular">Anular</button>
                  </div>

                  </form>
                </div>
                </div>
            </div>

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
