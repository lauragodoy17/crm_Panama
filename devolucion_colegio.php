<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Devoluciones de venta</title>

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
        width: 40px !important;
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
                  <h4>Devolucione de venta</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Devolución
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Venta
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
                  
                  $sql_pedido="SELECT id FROM pedidos WHERE id='".$_GET["id_pedido"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_pedido="SELECT pe.fecha,pe.tipo as petipo,pe.observaciones, pe.cliente,z.codigo as codzona, z.zona, c.id as cid, c.colegio, c.sub_zona, c.responsable, u.nombres, u.apellidos, u.tipo, e.id as eid,e.estado FROM devoluciones_v pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_dev e ON e.id=pe.estado WHERE pe.id='".$pedido["id"]."' AND id_colegio > 0";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                  $n_cole = $req_pedido->rowCount();
                  
                  if ($n_cole > 0) {
        

                    $sql="SELECT pe.id, l.id as libroid, l.id_grado, l.libro, l.precio, l.isbn, m.materia, lp.cantidad, p.cod_area, p.descuento,p.descuento_d, p.tasa_compra_d, lp.cod_pedido, lp.id as lpid FROM devoluciones_v pe LEFT JOIN libros_devol_v lp ON lp.cod_pedido=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON l.id_materia=m.id LEFT JOIN presupuestos p ON p.id_colegio=pe.id_colegio AND p.id_libro=lp.id_libro AND lp.cod_area=p.cod_area AND pe.id_periodo=p.id_periodo WHERE pe.id='".$_GET["id_pedido"]."'  GROUP BY l.id,p.cod_area;";

                    $sql_cliente="SELECT cliente FROM clientes WHERE id='".$pedido["cliente"]."'";

                    $req_cliente = $bdd->prepare($sql_cliente);
                    $req_cliente->execute();
                    $cliente = $req_cliente->fetch();

                  }else{
                    $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.precio, l.isbn, m.materia, lp.cantidad, lp.id as lpid FROM devoluciones_v pe JOIN libros_devol_v lp ON lp.cod_pedido=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$_GET["id_pedido"]."'";

                    $sql_cliente="SELECT c.cliente FROM clientes c JOIN devoluciones_v d ON c.id=d.cliente WHERE d.id='".$_GET["id_pedido"]."'";

                    $req_cliente = $bdd->prepare($sql_cliente);
                    $req_cliente->execute();
                    $cliente = $req_cliente->fetch();

                    $sql_cliente="SELECT estado, observaciones FROM devoluciones_v d WHERE id='".$_GET["id_pedido"]."'";

                    $req_cliente = $bdd->prepare($sql_cliente);
                    $req_cliente->execute();
                    $observaciones = $req_cliente->fetch();
                  }
                                  
                  $req = $bdd->prepare($sql);
                  $req->execute();

                                
                  $libros = $req->fetchAll();

                  $sql = "SELECT id, estado FROM ordenes_pedidos WHERE id_devol_v='".$_GET["id_pedido"]."' AND estado!=4";

                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $op = $req->rowCount();
                  $n_op = $req->fetch();

                  if ($op !=0) {
                      echo "<h4>OP <a href='op_pendiente.php?op=".$n_op["id"]."' target='_blank'># ".$n_op["id"]."</a></h4>";
                  }
                                
                ?>
                <table class="table table-bordered table-hover">
                  <tr>
                    <td>Devolución venta #: <?php echo $_GET["id_pedido"] ?></td>
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
                      <td>Cliente: <?php echo $cliente["cliente"] ?></td>
                      <td>
                        Tipo: <?php 

                        if ($pedido["petipo"] ==1) {
                          echo "Libros sueltos";
                        }else{
                          echo "Paquetes";
                        }
                                    
                        ?>
                      </td>            
                    </tr>

                    </table>
                      <div class="">
                        <form method="POST" action="php/aprobar_pedido.php" id="form_pedido">
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
                                <th>Valor</th>  
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

                                  $sql = "SELECT l.id_tipo, l.lugar, u.piso, u.ubicacion FROM lugares l JOIN ubicaciones u ON l.id=u.id_lugar JOIN libros_ubicaciones lu ON u.id=lu.ubicacion WHERE lu.id_libro='".$libro["libroid"]."'";
                                  $req = $bdd->prepare($sql);
                                  $req->execute();

                                  $ubicaciones = $req->fetchAll();

                                  $ubi="";

                                  foreach ($ubicaciones as $ubicacion) {
                                    if ($ubicacion["id_tipo"] ==1) {
                                      $ubi.=$ubicacion["lugar"]."".$ubicacion["piso"]." Pallet ".$ubicacion["ubicacion"].", ";
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
                                                                                           
                                  }
                                  $total_v=array_sum($total_venta);
                                  $total_c=array_sum($total_cantidad);
                                ?>
                                        
                                </tr>
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
                  <?php if ($n_cole > 0) { ?>
                  <textarea name="observaciones" id="observaciones" cols="100" rows="12" class="form-control"><?php echo $pedido["observaciones"] ?></textarea><br><br>
                 <?php }else { ?>

                  <textarea name="observaciones" id="observaciones" cols="100" rows="12" class="form-control"><?php echo $observaciones["observaciones"] ?></textarea><br><br>
                 <?php } ?>
                
                 <div id="entregado" class="pull-left"></div>
                 <div id="recibido" class="pull-right"></div>
                
                 
                 <?php 
                  if ($pedido["eid"]==1 && $n_op["estado"]!=2) {
                     if ($_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==2) {
                      echo '<h3>'.$pedido["estado"].'</h3><br>';
                      echo'<button class="btn btn-danger d-print-none" id="rechazar" type="button">Anular</button> <br><br>';
                      echo '<button class="btn btn-success d-print-none" id="aprobar" type="button">Recibir</button> <br><br>';
                    }
                   }elseif ($pedido["cid"]==0 && $observaciones["estado"]!=2) {
                      echo '<h3>'.$pedido["estado"].'</h3><br>';
                      echo'<button class="btn btn-danger d-print-none" id="rechazar" type="button">Anular</button> <br><br>';
                      echo '<button class="btn btn-success d-print-none" id="aprobar" type="button">Recibir</button> <br><br>';
                   }

                   elseif ($pedido["eid"]==2 && $n_op["estado"]!=2) {
                    if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==24) {
                      echo '<h3>'.$pedido["estado"].'</h3><br>';
                      echo'<button class="btn btn-danger d-print-none" id="rechazar" type="button">Anular</button> <br><br>';
                      echo '<button class="btn btn-success d-print-none" id="proceso" type="button">En proceso</button> <br><br>';
                    }
                   }elseif ($pedido["cid"]==0 && $observaciones["estado"]==1) {
                      echo '<h3>'.$pedido["eid"].'</h3><br>';
                      echo'<button class="btn btn-danger d-print-none" id="rechazar" type="button">Anular</button> <br><br>';
                      echo '<button class="btn btn-success d-print-none" id="proceso" type="button">En proceso</button> <br><br>';
                   }

                   elseif ($pedido["eid"]==4 && $n_op["estado"]!=2) {
                    echo '<h3>'.$pedido["estado"].'</h3><br>';
                   }elseif ($pedido["eid"]==3 && $n_op["estado"]!=2) {
                    echo '<h3>'.$pedido["estado"].'</h3><br>';
                   }

                   elseif ($n_op["estado"]==2) {
                    echo '<h3>Atendida</h3>';

                   }


                  
                 ?>

                
                
                <?php
                  if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) {
                        if ($op ==0) {
                          
                          echo '<a href="solicitar_op.php?id_devol_v='.$_GET["id_pedido"].'" target="_blank" class="btn btn-warning d-print-none">Solicitar OP</a>';
                          
                        }
                    }

                  

                ?>
                 <button type="button" id="imprimir" class="btn btn-info d-print-none">Imprimir</button>

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

        $("#rechazar").click(function(){

        if (confirm("¿Seguro desea anular?")) {
          window.location="php/accion_devol_v.php?rechazar=<?php echo $_GET["id_pedido"] ?>&tipo=<?php echo $_GET["tipo"] ?>";
        }
        
      });

      $("#aprobar").click(function(){

        if (confirm("¿Seguro desea recibir?")) {
          window.location="php/accion_devol_v.php?aprobar=<?php echo $_GET["id_pedido"] ?>&tipo=<?php echo $_GET["tipo"] ?>";
        }
        
      });

      $("#proceso").click(function(){

        if (confirm("¿Seguro desea poner en proceso?")) {
          window.location="php/accion_devol_v.php?proceso=<?php echo $_GET["id_pedido"] ?>&tipo=<?php echo $_GET["tipo"] ?>";
        }
        
      });

      $("#imprimir").click(function(){
        window.print();
      })

      <?php if($_SESSION["tipo"]==1 || $_SESSION["tipo"]==2)   { ?>

        window.addEventListener("beforeprint", function(event) {
            $("#impre").html("<h4>Fecha recibido bodega: <?php echo date("Y-m-d H:i") ?></h4>");

            $("#entregado").html("<h4>Entregado por: ___________________________  </h4>");

            $("#recibido").html("<h4>Recibido por: ___________________________</h4>");
                
            var dataString = 'feid='+"<?php echo date("Y-m-d H:i:s") ?>"+'/'+"<?php echo $_GET["id_pedido"] ?>";
                  
            $.ajax({

                url: "ajax/fecha_impre_devol.php",
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

      <?php } ?>

      
    </script>
    
  </body>
</html>
