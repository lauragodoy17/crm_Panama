<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Orden de producción</title>
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
    <link
      rel="stylesheet"
      type="text/css"
      href="src/plugins/datatables/css/dataTables.bootstrap4.min.css"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="src/plugins/datatables/css/responsive.bootstrap4.min.css"
    />

    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
    <link
      rel="stylesheet"
      type="text/css"
      href="vendors/styles/icon-font.min.css"
    />
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />

    <style>
      @page{
          margin: 30px;
      
      }
      @media print {
        a {display: none;}
        
        a[href]:after {
            content: none !important;
        }
        body{
          font-size: 9px;
        }
      }

      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
      }

      .dc {
        width: 60px !important;
      }

      input[type=number] { -moz-appearance:textfield; }


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
                  <h4>Orden de producción</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedido
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Pendiente
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <div class="row">
              <div class="col-sm-12">
            
                <?php 

                  $sql_pedido="SELECT o.observaciones, o.fecha, o.descripcion, o.orden_pedido, o.conse,o.año, c.cliente, o.cliente as cid, o.adjunto, u.nombres, u.apellidos, o.fecha_ent_s, o.estado, o.solicitante, o.fecha_cumplida FROM ordenes_produccion o JOIN clientes c ON o.cliente=c.id JOIN usuarios u ON u.id=o.usuario WHERE o.id='".$_GET["opd"]."'";
                  

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_cliente="SELECT cliente FROM clientes WHERE id='".$pedido["cliente"]."'";

                  $req_cliente = $bdd->prepare($sql_cliente);
                  $req_cliente->execute();
                  $cliente = $req_cliente->fetch();

                  $sql = "SELECT * FROM libros_opd WHERE opid='".$_GET['opd']."'";
                  $req = $bdd->prepare($sql);
                  $req->execute();

                                
                  $libros = $req->fetchAll();
                                
                ?>

                <table class="table table-bordered table-hover">
                <form method="POST" action="php/mod_opd.php" id="form_pedido">
                  <tr>
                   

                      <td>OPD #: <?php echo $pedido["año"]." - ".$_GET["opd"] ?></td>

                   
                                
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                    <td>
                      Descripción pedido:

                      <?php 
                        if ($pedido["descripcion"]==1) {
                          echo "Libro Suelto";
                        }elseif ($pedido["descripcion"]==2) {
                          echo "Guía";
                        }else{
                          echo "Otro";
                        }
                      ?>
                    </td>
                  </tr>
                  <tr>
                  <td>
                    Usuario: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?>
                  </td>
                                
                 
                  <td>Solicitante: <?php echo $pedido["solicitante"]; ?> </td>
                 
              
                
                    <td>
                  <?php if ($_SESSION['tipo']!=8) { ?>
                    <label for="fecha_ent_s">Fecha de entrega solicitada:<small style="color:red;"> *</small></label>
                    <div class="input-group">
                      <input type="text" class="form-control date-picker" name="fecha_ent_s" id="fecha_ent_s" type="text" data-date-format="yyyy-mm-dd" required="" autocomplete="off" value="<?php echo $pedido["fecha_ent_s"] ?>" />
                      <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                      </span>
                    </div>
                    <?php }else{ 
                      echo "Fecha de entrega solicitada: ".$pedido["fecha_ent_s"]
                    ?>

                    <?php }?>

                  </td>
                </tr>
                   
                </table>

                  Archivo Adjunto:</b> <a href="adjuntos_opd/<?php echo $pedido["adjunto"] ?>" style="cursor: pointer;" target="_blank"><?php echo  $pedido["adjunto"] ?></a><br><br>

                  <?php if ($_SESSION['tipo']!=8) { ?>
                    <div class="form-group " for="persona">
                              
                      <label>Cliente<small style="color:red;"> *</small> </label>         
                      <select class="form-control custom-select2" name="persona" id="persona" style="width: 100%;" required>
                        <option selected="selected" value="">Seleccionar</option>
                        <?php 

                          $sql = "SELECT * FROM clientes";

                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $clientes = $req->fetchAll();

                          foreach ($clientes as $cliente) {

                            if ($cliente["id"] == $pedido["cid"]) {
                                              
                              echo '<option value="'.$cliente["id"].'" SELECTED>'.$cliente["cliente"].'</option>';

                            }else{

                              echo '<option value="'.$cliente["id"].'">'.$cliente["cliente"].'</option>';

                            }
                                            
                                            
                          }

                        ?>
                      </select>
                    </div>
                    <?php }else{ ?>
                      <?php echo "<h5>Cliente: ".$pedido["cliente"]."</h5><br>";
                      echo "<input type='hidden' name='persona' value='".$pedido["cid"]."'>"?>
                    <?php } ?>
                          
                    <div class="">
                              
                      <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                                          
                          <th>#</th>
                          <th>Título</th>
                          <th>Cantidad</th>
                          <th># Click</th>
                          <th>Impresora</th>
                          <th>Entrega 1</th>
                          <th>Entrega 2</th>
                          <th>Entrega 3</th>
                          <th>Total entregas</th>
                          <th>Total Clicks</th>
                          <th>Valor</th>                                        
                        </tr>
                      </thead>
                      <tbody>
                                
                        <script src='vendors/scripts/jquery-2.1.4.min.js'></script>

                          <?php

                            $i=1;

                            $sql="SELECT * FROM impresoras_taller WHERE act=1";                      
                            $req = $bdd->prepare($sql);
                            $req->execute();
                            $impresoras = $req->fetchAll();

                            foreach($libros as $libro) {

                              $sql_ent1="SELECT e.cant_entregada, e.observacion_entrega, e.fecha FROM entregas_opd e JOIN libros_opd l ON e.id_libro_opd=l.id WHERE l.opid='".$_GET["opd"]."' AND l.id='".$libro["id"]."' LIMIT 1";
                  
                              $req_ent1 = $bdd->prepare($sql_ent1);
                              $req_ent1->execute();
                              $ent1 = $req_ent1->fetch();

                              $sql_ent2="SELECT e.cant_entregada, e.observacion_entrega, e.fecha FROM entregas_opd e JOIN libros_opd l ON e.id_libro_opd=l.id WHERE l.opid='".$_GET["opd"]."' AND l.id='".$libro["id"]."' LIMIT 1,2";
                        
                              $req_ent2 = $bdd->prepare($sql_ent2);
                              $req_ent2->execute();
                              $ent2 = $req_ent2->fetch();

                              $sql_ent3="SELECT e.cant_entregada, e.observacion_entrega, e.fecha FROM entregas_opd e JOIN libros_opd l ON e.id_libro_opd=l.id WHERE l.opid='".$_GET["opd"]."' AND l.id='".$libro["id"]."' LIMIT 2,3";
                        
                              $req_ent3 = $bdd->prepare($sql_ent3);
                              $req_ent3->execute();
                              $ent3 = $req_ent3->fetch();


                                           
                              $total_cantidad[]=$libro["cantidad"];

                              echo'<tr class="odd gradeX" id="'.$libro["id"].'">';
                              echo'<td class="">';
                                if ($_SESSION['tipo']!=8) {

                                  echo'<button type="button" class="btn btn-danger btn-sm d-print-none" id="e'.$libro["id"].'"><i class="fa fa-trash"></i></button> ';
                                }
                                                
                              echo''.$i.'</td>';
                              echo'<td class="">'.$libro["libro"].'</td>';

                              if ($_SESSION['tipo']!=8) {
                                echo'<td class=""> <input type="number" class="form-control dc" min="0" max="5000" id="cantidad'.$libro["id"].'" name="cantidad" value="'.$libro["cantidad"].'">  </td>';
                              }else{
                                echo'<td class="">'.$libro["cantidad"].'</td>';
                              }

                              echo'<td class=""> <input type="number" class="form-control dc" min="0" id="click'.$libro["id"].'" name="click" value="'.$libro["click"].'">  </td>';
                              echo'<td class="">
                                <select name="impresora" class="form-control" id="impresora'.$libro["id"].'">
                                  <option value="">Seleccione</option>';

                                  foreach ($impresoras as $impresora) {
                                    if ($libro["impresora"]==$impresora["id"]) {
                                      echo "<option value='".$impresora["id"]."' data-valor='".$impresora["valor_click"]."' SELECTED>".$impresora["impresora"]."</option>";
                                    }else{
                                      echo "<option value='".$impresora["id"]."' data-valor='".$impresora["valor_click"]."'>".$impresora["impresora"]."</option>";
                                    }
                                    
                                  }

                                echo'</select>
                              </td>';


                             

                              if ($ent1["cant_entregada"] =="") {
                                echo'<td class=""><input type="number" class="form-control dc" min="0" max="5000" id="entrega1'.$libro["id"].'" ></td>';
                              }else{
                                echo "<td>".$ent1["cant_entregada"]."</td>";
                              }

                              if ($ent2["cant_entregada"] =="") {
                                echo'<td class=""><input type="number" class="form-control dc" min="0" max="5000" id="entrega2'.$libro["id"].'" ></td>';
                              }else{
                                echo "<td>".$ent2["cant_entregada"]."</td>";
                              }

                              if ($ent3["cant_entregada"] =="") {
                                echo'<td class=""><input type="number" class="form-control dc" min="0" max="5000" id="entrega3'.$libro["id"].'" ></td>';
                              }else{
                                echo "<td>".$ent3["cant_entregada"]."</td>";
                              }


                              $total_entr=$ent1["cant_entregada"] +$ent2["cant_entregada"] +$ent3["cant_entregada"];
                              $total_click=$total_entr * $libro["click"];
                              $valor=$total_click * $libro["valor_click"];
                              $total_entregas[]=$total_entr;
                              $total_clicks[]=$total_click;
                              $total_valor[]=$valor;
                              echo "<td>".$total_entr."</td>";
                              echo "<td>".$total_click."</td>";
                              echo "<td>$ ".number_format($valor,0,",", ".")."</td>";
                              echo '<input type="hidden" name="lpid[]" value="'.$libro["id"].'" id="lpid'.$libro["id"].'">';
                              echo '<input type="hidden" name="lib_p[]" id="l'.$libro["id"].'" >';
                              echo '<input type="hidden" name="i_click[]" id="i_click'.$libro["id"].'" >';
                              echo '<input type="hidden" name="i_impresora[]" id="i_impresora'.$libro["id"].'" >';
                              echo '<input type="hidden" name="entrega1[]" id="ent1'.$libro["id"].'" >';
                              echo '<input type="hidden" name="entrega2[]" id="ent2'.$libro["id"].'" >';
                              echo '<input type="hidden" name="entrega3[]" id="ent3'.$libro["id"].'" >';

                              echo "<script>


                                $('#e".$libro["id"]."').click(function(){

                                  $('#".$libro["id"]."').remove();
                                  $('#lpid".$libro["id"]."').remove();

                                })

                                $('#cantidad".$libro["id"]."').keyup(function(){

                                  var cant =$('#cantidad".$libro["id"]."').val();
                                  

                                  $('#l".$libro["id"]."').val(".$libro["id"]."+'/'+cant);

                              
                                })

                                $('#click".$libro["id"]."').keyup(function(){

                                  var click =$('#click".$libro["id"]."').val();
                                  

                                  $('#i_click".$libro["id"]."').val(".$libro["id"]."+'/'+click);

                              
                                })

                                $('#impresora".$libro["id"]."').change(function(){

                                  var impresora =$('#impresora".$libro["id"]."').val();
                                  var valor =  $('#impresora".$libro["id"]." option:selected').data('valor');                          

                                  $('#i_impresora".$libro["id"]."').val(".$libro["id"]."+'/'+impresora+'/'+valor);

                              
                                })

                                $('#entrega1".$libro["id"]."').keyup(function(){

                                  var cant =$('#entrega1".$libro["id"]."').val();
                                  

                                  $('#ent1".$libro["id"]."').val(".$libro["id"]."+'/'+cant);

                              
                                })

                                $('#entrega2".$libro["id"]."').keyup(function(){

                                  var cant =$('#entrega2".$libro["id"]."').val();
                                  

                                  $('#ent2".$libro["id"]."').val(".$libro["id"]."+'/'+cant);

                              
                                })

                                $('#entrega3".$libro["id"]."').keyup(function(){

                                  var cant =$('#entrega3".$libro["id"]."').val();
                                  

                                  $('#ent3".$libro["id"]."').val(".$libro["id"]."+'/'+cant);

                              
                                })

                            </script>";
                                                
                                                   
                            $i++;
                          }

                          $total_c=array_sum($total_cantidad);
                          $total_entregas=array_sum($total_entregas);
                          $total_clicks=array_sum($total_clicks);
                          $total_valor=array_sum($total_valor);

                        ?>
                                        
                        </tr>                            
                      </tbody>
                      <tfoot>
                        <tr>
                          <td></td>
                          <td>Total</td>
                          <td><?php echo $total_c; ?></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td><?php echo $total_entregas; ?></td>
                          <td><?php echo $total_clicks; ?></td>
                          <td>$ <?php echo number_format($total_valor,0,",", "."); ?></td>
                        </tr>
                      </tfoot>
                                    
                    </table>
                  </div>
                  <input type="hidden" name="opd" value="<?php echo $_GET["opd"] ?>">

                  <?php for ($i=1; $i < 100; $i++) { ?>

                    <div id="agg_l<?php echo $i;?>" class="d-none">
                      <h4>Material #<?php echo $i;?>:</h4>
                      <div class="row">
                        <div class="form-group col-sm-3">
                          <label id="l_titulo<?php echo $i;?>" for="titulo<?php echo $i;?>" class="control-label">Titulo<small style="color:red;"> *</small></label>
                          <input type="text" class="form-control" name="titulo" id="titulo<?php echo $i;?>">
                        </div>

                        <div class="form-group col-sm-3">
                          <label id="l_cantidad<?php echo $i;?>" for="cantidad<?php echo $i;?>" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                          <input type="number" class="form-control" name="cantidad" id="cantidad<?php echo $i;?>">
                        </div>

                        <div class="form-group col-sm-3">
                          <label id="l_cencaratulado<?php echo $i;?>" for="encaratulado<?php echo $i;?>" class="control-label">Encaratulado<small style="color:red;"> *</small></label>
                          <input type="text" class="form-control" name="encaratulado" id="encaratulado<?php echo $i;?>">
                        </div>
                      </div>
                  
                  
                      <input type="hidden" name="libro_e[]" id="libro_e<?php echo $i;?>">
                    </div>

                  <?php } ?>
                            
                  <a id="agregar_libro" style="cursor: pointer;">Agregar otro +</a><br>
                  <br><div class="row">
                  <div class="col-sm-6">
                    <?php if ($_SESSION['tipo']!=8) { ?>
                      <label for="observaciones">Observaciones de solicitud:</label><br>
                      <textarea name="observaciones" id="observaciones" cols="70" rows="9" class="form-control" placeholder="Tipo de insumo"><?php echo $pedido["observaciones"] ?></textarea><br>
                    <?php }else{ ?>
                      <label for="observaciones">Observaciones de solicitud:</label>
                      <?php echo $pedido["observaciones"] ?>
                    <?php } ?>
                  </div>

                  <div class="col-sm-6">
                  <?php
                    if ($ent1["fecha"]!="") {

                      echo "Fecha de entrega taller 1: ".$ent1["fecha"]."<hr>"; 

                    }

                    if ($ent1["observacion_entrega"]!="") {

                      echo "Observaciones de entrega 1: ".$ent1["observacion_entrega"]."<hr>"; 
                    }

                    if ($ent2["fecha"]!="") {

                      echo "Fecha de entrega taller 2: ".$ent2["fecha"]."<hr>"; 

                    }
        
                    if ($ent2["observacion_entrega"]!="") {
                     
                      echo "Observaciones de entrega 2: ".$ent2["observacion_entrega"]."<hr>"; 
                    }

                    if ($ent3["fecha"]!="") {

                       echo "Fecha de entrega taller 3: ".$ent3["fecha"]."<hr>"; 

                    }

                    if ($ent3["observacion_entrega"]!="") {

                      echo "Observaciones de entrega 3: ".$ent3["observacion_entrega"]."<hr>"; 
                    }

                  ?>
                  <?php if ($_SESSION['tipo']!=2) { ?>
                    <br><label for="observaciones_ent">Observaciones de entrega:<small style="color: red">*</small></label><br>
                    <textarea name="observaciones_ent" id="observaciones_ent" cols="80" rows="12" class="form-control" placeholder="Tipo de insumo"></textarea><br>
                  <?php } ?>
                </div>
              </div><br>

              <center>
                <?php if ($pedido["estado"]==4) { ?>
                    <h4 style="color: #53C144">Cumplida <?php echo $pedido["fecha_cumplida"] ?></h4>
                <?php } ?>
                 <button type="button" id="imprimir" class="btn btn-info d-print-none">Imprimir</button> <br><br>

                <?php if ($_SESSION['tipo']!=2) { ?>
                  <button class="btn btn-warning d-print-none" id="entregar">Entregar</button>
                <?php } ?>

                  
                <button type="button" class="btn btn-primary d-print-none" id="modificar">Modificar</button>
                  
                  
                  <?php if ($pedido["estado"]==0) { ?>
                    <button class="btn btn-success d-print-none" id="cumplida">Cumplida</button>
                  <?php } ?>
               

              </center>
                        
              <center>
            </form>

                <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->

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
    <script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
    <script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>

    <script>
    


      $("#entregar").click(function(){
        
        $("#form_pedido").attr("action","php/entregar_opd.php");
      });

      $("#cumplida").click(function(){
        
        $("#form_pedido").attr("action","php/cumplir_opd.php");
      });

      $("#imprimir").click(function(){
        window.print();
      })

      $("#modificar").click(function(){
        $("#form_pedido").submit();
      });

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
          var enca=$('#encaratulado<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(titulo+'/'+cant+'/'+enca);

        })

        $('#titulo<?php echo $i; ?>').keyup(function(){

          var cant =$('#cantidad<?php echo $i; ?>').val();
          var titulo=$('#titulo<?php echo $i; ?>').val();
          var enca=$('#encaratulado<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(titulo+'/'+cant+'/'+enca);

        })

        $('#encaratulado<?php echo $i; ?>').keyup(function(){

          var cant =$('#cantidad<?php echo $i; ?>').val();
          var titulo=$('#titulo<?php echo $i; ?>').val();
          var enca=$('#encaratulado<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(titulo+'/'+cant+'/'+enca);

        })

        

      <?php } ?>

      
      
    


      
  })


  </script>
    
  </body>
</html>
