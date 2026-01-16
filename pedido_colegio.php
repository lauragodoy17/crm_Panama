<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if ($_GET['tp']==2) { ?>
      <title>Inkpulse - Pedido pendiente</title>
    <?php }elseif ($_GET['tp']==3) { ?>
      <title>Inkpulse - Pedido aprobado</title>
    <?php }elseif ($_GET['tp']==4) { ?>
      <title>Inkpulse - Pedido entregado</title>
    <?php }else { ?>
      <title>Inkpulse - Pedido anulado</title>
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
      input[type=number].form-control {
        width: 50px !important;
      }

      #dataTables-example_info{
        display: none !important;
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
                  <?php if ($_GET['tp']==2) { ?>
                    <h4>Pedido pendiente</h4>
                  <?php }elseif ($_GET['tp']==3) { ?>
                    <h4>Pedido aprobado</h4>
                  <?php }elseif ($_GET['tp']==4) { ?>
                    <h4>Pedido entregado</h4>
                  <?php }else { ?>
                    <h4>Pedido anulado</h4>
                  <?php } ?>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                    
                      <?php if ($_GET['tp']==2) { ?>
                        Pendiente
                      <?php }elseif ($_GET['tp']==3) { ?>
                        Aprobado
                      <?php }elseif ($_GET['tp']==4) { ?>
                        Entregado
                      <?php }else { ?>
                        Anulado
                      <?php } ?>
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
                  
                  include("conexion/bdd.php");

                  $sql_pedido="SELECT id FROM pedidos WHERE id='".$_GET["id_pedido"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.cliente, pe.fac_rem,pe.dir_ent,z.codigo as codzona, z.zona, c.colegio, c.sub_zona, c.responsable, u.nombres, u.apellidos, u.tipo, e.estado, e.id as eid, pe.tipo as petipo FROM pedidos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$pedido["id"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_cliente="SELECT cliente FROM clientes WHERE id='".$pedido["cliente"]."'";

                  $req_cliente = $bdd->prepare($sql_cliente);
                  $req_cliente->execute();
                  $cliente = $req_cliente->fetch();

                  $sql = "SELECT pe.id, l.id as libroid, l.id_grado, l.libro, l.precio, l.isbn, m.materia, lp.cantidad, p.cod_area, p.descuento,p.descuento_d, p.tasa_compra_d, lp.cod_pedido,lp.cantidad_aprob, lp.descuento_aprob,lp.id as lpid,lp.plataforma FROM pedidos pe LEFT JOIN libros_pedidos lp ON lp.cod_pedido=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON l.id_materia=m.id LEFT JOIN presupuestos p ON p.id_colegio=pe.id_colegio AND p.id_libro=lp.id_libro AND lp.cod_area=p.cod_area AND pe.id_periodo=p.id_periodo WHERE pe.id='".$_GET["id_pedido"]."' AND p.definido=1 AND lp.cantidad!=0 GROUP BY l.id,p.cod_area";
                  $req = $bdd->prepare($sql);
                  $req->execute();
          
                  $libros = $req->fetchAll();

                  $sql = "SELECT id,año FROM ordenes_pedidos WHERE id_pedido='".$_GET["id_pedido"]."' AND estado!=4";

                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $op = $req->rowCount();
                  $n_op = $req->fetch();

                  if ($op !=0) {
                    echo "<h4>OP <a href='op_pendiente.php?op=".$n_op["id"]."' target='_blank'># ".$n_op["año"]." - ".$n_op["id"]."</a></h4>";
                  }

                  $sql = "SELECT op FROM op_pedidos_agrupados WHERE id_pedido='".$_GET["id_pedido"]."'";

                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $op_agp = $req->rowCount();
                  $n_op_agp = $req->fetch();

                  if ($op_agp !=0) {
                    echo "<h4>OP <a href='op_pendiente.php?op=".$n_op_agp["op"]."' target='_blank'># ".$n_op_agp["op"]."</a></h4>";
                  }
                                
                ?>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Pedido: <?php echo $_GET["id_pedido"] ?></td>
                    <td>Colegio: <?php echo $pedido["colegio"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
                  <tr>
                    <?php if ($pedido["tipo"]==3) {
                      list($empresa,$n_zona) = explode("/", $pedido["zona"]);
                    ?>

                      <td>Empresa: <?php echo $empresa ?></td>
                      <td>Zona: <?php echo $n_zona ?></td>
                      <td>Responsable: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                    <?php }else{
                      $sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$pedido["sub_zona"]."'";
                      $req_sz = $bdd->prepare($sql_sz);
                      $req_sz->execute();
                      $sub_zona = $req_sz->fetch();
                    ?>
                      <td>Empresa: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                      <td>Zona: <?php echo $sub_zona["sub_zona"] ?></td>
                      <td>Responsable: <?php echo $pedido["responsable"] ?></td>
                    <?php } ?>
                                

                  </tr>
                  <tr>
                    <td>Fecha de recogida: <?php echo $pedido["fecha_r"];?></td>
                    <td>Cliente: <?php echo $cliente["cliente"] ?></td>
                      <?php if ($pedido["fac_rem"] ==1) { ?>
                        <td>Factura</td>
                      <?php }else{ ?>
                        <td>Remisión</td>
                      <?php } ?>
                  </tr>
                  <tr>
                  <?php if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656' || $pedido["tipo"]==10) { ?>
                      <?php if ($pedido["petipo"] ==1) { ?>
                        <td>Tipo de pedido: Libros sueltos</td>
                      <?php }else{ ?>
                        <td>Tipo de pedido: Paquete</td>
                      <?php } ?>
                  <?php } ?>
                  </tr>
                </table>
                <center id="impre"></center>
                <input type="hidden" id="fecha_impre">
                <form method="POST" action="php/aprobar_pedido.php" id="form_pedido">        
                <div class="">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                      <tr>
                        <th>Isbn</th>
                        <th>Título</th>
                        <th>Ubicación</th>
                        <th class="d-print-none">Materia</th>
                        <th>Grado</th>
                        <th>PVP</th>
                        <th>Desc.</th>
                        <th>Precio Fact.</th>
                        <th>Cant.</th>
                        <th>Valor Venta</th>
                        <?php if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { ?>
                          <th>Plataforma</th>
                        <?php } ?>
                          <th class="d-print-none">Desc. Aprobado</th>
                          <th class="d-print-none">Cant. Aprobada</th>
                      </tr>
                      </thead>
                        <tbody>
                        <script src='vendors/scripts/jquery-2.1.4.min.js'></script>
                        <?php
                                          
                        foreach($libros as $libro) {
                                           
                          if ($libro["descuento_d"]==="0.0000") {
                            $descuento=$libro["descuento"] * 100;
                            $precio_fact=$libro["precio"] -($libro["precio"] * $libro["descuento"]);
                          }else{
                            $descuento=$libro["descuento_d"] * 100;
                            $precio_fact=$libro["precio"] -($libro["precio"] * $libro["descuento_d"]);
                          }
     
                          $v_venta=$precio_fact * $libro["cantidad"];
                          $total_venta[]=$v_venta;
                          $total_cantidad[]=$libro["cantidad"];

                          $sql = "SELECT l.id_tipo, l.lugar, u.piso, u.ubicacion, lu.posicion FROM lugares l JOIN ubicaciones u ON l.id=u.id_lugar JOIN libros_ubicaciones lu ON u.id=lu.ubicacion WHERE lu.id_libro='".$libro["libroid"]."'";
                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $ubicaciones = $req->fetchAll();

                          $ubi="";

                        foreach ($ubicaciones as $ubicacion) {
                          if ($ubicacion["id_tipo"] ==1) {
                            $ubi.=$ubicacion["lugar"]."".$ubicacion["piso"]." Pallet ".$ubicacion["ubicacion"]." ".$ubicacion["posicion"].", ";
                          }else{
                            $ubi.=$ubicacion["lugar"]." Bandeja ".$ubicacion["ubicacion"].", ";
                          }
                        }
                                          
                        echo'<tr class="odd gradeX">';
                        echo'<td class="">'.$libro["isbn"].'</td>';
                        echo'<td class="">'.$libro["libro"].'</td>';
                        echo'<td class="">'.$ubi.'</td>';
                        echo'<td class="center d-print-none">'.$libro["materia"].'</td>';
                        if ($libro["cod_area"] == "") {

                          $sql_g = "SELECT grado FROM grados WHERE id='".$libro["id_grado"]."'";
                          $req_g = $bdd->prepare($sql_g);
                          $req_g->execute();
                          $grado= $req_g->fetch();
                                                  
                        }else{
                                                  
                          $sql = "SELECT id_grado_otro FROM areas_objetivas WHERE codigo='".$libro["cod_area"]."'";
                          $req = $bdd->prepare($sql);
                          $req->execute();

                          $go = $req->fetch();

                          $sql_g = "SELECT grado FROM grados WHERE id='".$go["id_grado_otro"]."'";
                          $req_g = $bdd->prepare($sql_g);
                          $req_g->execute();
                          $grado= $req_g->fetch();

                          
                        }
                        echo'<td class="center">'.$grado["grado"].'</td>';
                        echo'<td class="center">$ '.number_format($libro["precio"],0,",", ".").'</td>';
                        echo'<td class="center">'.$descuento.' %</td>';
                        echo'<td class="center">$ '.number_format($precio_fact,0,",", ".").'</td>';
                        echo'<td class="center">'.$libro["cantidad"].'</td>';
                        echo'<td class="center">$ '.number_format($v_venta,0,",", ".").'</td>';
                                               
                        if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { 

                          if ($libro["plataforma"]==0) {
                            echo'<td class="center">No</td>';
                          }else{
                            echo'<td class="center">Si</td>';
                          }
                                               
                                                
                        }

                        echo'<td class="center d-print-none"><input type="number" id="d'.$libro["lpid"].'" name="cantidad_a" value="'.$libro["descuento_aprob"].'" class="form-control" size="5"></td>';
                        echo'<td class="center d-print-none"><input type="number" id="c'.$libro["lpid"].'" name="cantidad_a" value="'.$libro["cantidad_aprob"].'" class="form-control" size="5"></td>';
                        echo '<input type="hidden" name="lib_p[]" id="l'.$libro["lpid"].'" >';
                                               
                        echo "<script>

                          $('#c".$libro["lpid"]."').keyup(function(){
                            var cant =$(this).val();
                            var desc=$('#d".$libro["lpid"]."').val();
                            $('#l".$libro["lpid"]."').val(cant+'/'+".$libro["lpid"]."+'/'+desc);

                          })

                          $('#d".$libro["lpid"]."').keyup(function(){
                            var desc =$(this).val();
                            var cant=$('#c".$libro["lpid"]."').val();
                            $('#l".$libro["lpid"]."').val(cant+'/'+".$libro["lpid"]."+'/'+desc);

                          })
                        </script>";

      
                                                                                           
                      }
                      if (!empty($total_venta)) {
                        $total_v=array_sum($total_venta);
                      }else{
                        $total_v=0;
                      }

                      if (!empty($total_cantidad)) {
                        $total_c=array_sum($total_cantidad);
                      }else{
                        $total_c=0;
                      }
                      
                    ?>

                  </tbody>
                                   
                    <td class="center"></td>
                    <td class="center d-print-none"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"><b>Total:</b></td>
                    <td class="center"><b><?php echo $total_c; ?></b></td>
                    <td class="center"><b>$ <?php echo number_format($total_v,0,",", "."); ?></b></td>
                                    
                </table>
              </div>
              <input type="hidden" name="id_colegio" value="<?php echo $_GET["id_colegio"]; ?>">
              <input type="hidden" name="periodo" value="<?php echo $_GET["periodo"]; ?>">

              <br><center>
                  <label for="observaciones">Observaciones:</label><br>
                 <textarea name="observaciones" id="observaciones" cols="100" rows="9" class="form-control"><?php echo $pedido["observaciones"] ?></textarea><br><br>
                 <h5><label for="">Direción de entrega:</label>
                <?php echo $pedido["dir_ent"]; ?><br><br></h5>
                 <h3><?php echo $pedido["estado"]; ?></h3><br>
                 <input type="hidden" name="pedido" value="<?php echo $_GET["id_pedido"] ?>">
                 </form>
                 <button type="button" id="imprimir" class="btn btn-info d-print-none">Imprimir</button>
                 <?php if ($_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==2 ||  $_SESSION["id"]==21 || $_SESSION["tipo"]==10 ) {

                    if ($pedido["eid"]==1) {
                      echo '<button class="btn btn-success d-print-none" id="aprobar">Aprobar</button> <button class="btn btn-danger d-print-none" id="rechazar">Rechazar</button>';
                    }elseif ($pedido["eid"]==2) {
                      if ($op ==0 && $op_agp ==0) {
                        echo '<a href="solicitar_op.php?id_pedido='.$_GET["id_pedido"].'" target="_blank" class="btn btn-warning d-print-none">Solicitar OP</a> ';
                      }

                      echo '<button type="button" class="btn btn-danger d-print-none" id="rechazar">Anular</button> <button class="btn btn-success d-print-none" id="entregar" type="button">Entregar</button> <button type="button" class="btn btn-primary d-print-none" id="modificar">Modificar</button>';
                    }

                  ?>

                    
                  <?php } ?>
                </center>
                        

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
    <script src="src/plugins/datatables/js/natural.js"></script>

    <script>
      
      $(document).ready(function () {
        $('#dataTables-example').dataTable({
          "language": {
            "lengthMenu": "Display _MENU_ registros por página",
            "zeroRecords": "Nada encontrado, lo siento",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros en total )",
            "search": "Buscar&nbsp;:",
            paginate: {
              first:"Primero",
              previous:"Anterior",
                next:"Siguiente",
                last:"Último"
            }
          },
          "paging": false,
          "searching": false,
          order: [[4, 'asc']],
          columnDefs: [
            { type: 'natural', targets: 4 }
          ],
        });
      });

      window.addEventListener("beforeprint", function(event) {
        $("#impre").html("<h5><?php echo date("Y-m-d H:i") ?></h5>");
          
        var dataString = 'feid='+"<?php echo date("Y-m-d H:i:s") ?>"+'/'+"<?php echo $_GET["id_pedido"] ?>";
              
          $.ajax({

            url: "ajax/fecha_impre.php",
            type: "POST",
            data: dataString,
            dataType: "html",
            success: function (resp) {

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

      $("#rechazar").click(function(){
        window.location="php/accion_pedidos.php?rechazar=<?php echo $_GET["id_pedido"] ?>";
      });

      $("#entregar").click(function(){
        window.location="php/accion_pedidos.php?entregado=<?php echo $_GET["id_pedido"] ?>";
      });

      $("#imprimir").click(function(){
        window.print();
      })

      $("#modificar").click(function(){
        $("#form_pedido").attr("action","php/mod_pedido.php");
        $("#form_pedido").submit();
      });

    </script>
    
  </body>
</html>
