<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Pedido sin adopción</title>

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
          margin: 0;
      
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
      .dataTables_info{
        display: none;
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
                  <h4>Pedido sin adopción</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos sin adopción
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Ver
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

                  $sql_pedido="SELECT id FROM pedidos2 WHERE id='".$_GET["id_pedido"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                                  
                  $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.colegio, u.nombres, u.apellidos, e.estado FROM pedidos2 pe JOIN usuarios u ON u.id=pe.id_usuario JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$pedido["id"]."'";


                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.precio, m.materia, lp.cantidad, lp.cantidad_aprob, lp.descuento_aprob, lp.descuento FROM pedidos2 pe JOIN libros_pedidos2 lp ON lp.cod_pedido=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$_GET["id_pedido"]."' AND lp.cantidad!=0 GROUP BY l.id ORDER BY lp.id;";
                  $req = $bdd->prepare($sql);
                  $req->execute();

                                  
                  $libros = $req->fetchAll();

                  $sql = "SELECT id FROM ordenes_pedidos WHERE id_pedido_dist='".$_GET["id_pedido"]."' AND estado!=4";


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
                          
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Título</th>
                      <th>Materia</th>
                      <th>Grado</th>
                      <th>PVP</th>
                      <th>Descuento</th>
                      <th>Precio Facturación</th>
                      <th>Cantidad</th>
                      <th>Descuento Aprobado</th>
                      <th>Cantidad Aprobada</th>
                      <th>Valor Venta</th>
                    </tr>
                  </thead>
                  <tbody>
                                
                    <?php
                      $i=1;
                      foreach($libros as $libro) {
                                           
                        $descuento=$libro["descuento"];
                                            
                        if ($libro["descuento_aprob"]!="") {
                          $precio_fact=$libro["precio"] -($libro["precio"] * ($libro["descuento_aprob"] / 100) );
                        }else {
                          $precio_fact=$libro["precio"] -($libro["precio"] * ($libro["descuento"] / 100) );
                        }

                        if ($libro["cantidad_aprob"]!="") {
                          $v_venta=$precio_fact * $libro["cantidad_aprob"];
                        }else{
                          $v_venta=$precio_fact * $libro["cantidad"];
                          $libro["cantidad_aprob"]=$libro["cantidad"];

                        }


                        $total_venta[]=$v_venta;
                        $total_cantidad[]=$libro["cantidad"];
                        $total_cantidad_aprob[]=$libro["cantidad_aprob"];

                        echo'<tr class="odd gradeX">';
                          echo'<td class="center">'.$i.'</td>';
                          echo'<td class="">'.$libro["libro"].'</td>';
                          echo'<td class="center">'.$libro["materia"].'</td>';
                          if (empty($libro["cod_area"])) {

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
                          echo'<td class="center">'.$libro["descuento_aprob"].' %</td>';
                          echo'<td class="center">'.$libro["cantidad_aprob"].'</td>';
                          echo'<td class="center">$ '.number_format($v_venta,0,",", ".").'</td>';
                                               
                          $i++;
                                               
                        }

                        $total_v=array_sum($total_venta);
                        $total_c=array_sum($total_cantidad);
                        $total_c_aprob=array_sum($total_cantidad_aprob);
                      ?>
                                        
                      </tr>
                      <td class="center"></td>
                      <td class="center"></td>
                      <td class="center"></td>
                      <td class="center"></td>
                      <td class="center"></td>
                      <td class="center"></td>
                      <td class="center"><b>Total:</b></td>
                      <td class="center"><b><?php echo $total_c; ?></b></td>
                      <td class="center"></td>
                      <?php if ($libro["cantidad_aprob"]!="") { ?>
                        <td class="center"><b><?php echo $total_c_aprob; ?></b></td>
                      <?php }else{ ?>
                        <td></td>
                      <?php } ?>

                      <td class="center"><b>$ <?php echo number_format($total_v,0,",", "."); ?></b></td>
                    </tbody>
                  </table>
                </div>
                <input type="hidden" name="id_colegio" value="<?php echo $_GET["id_colegio"]; ?>">
                <input type="hidden" name="periodo" value="<?php echo $_GET["periodo"]; ?>">

              <center>
                 <label for="observaciones">Observaciones:</label><br>
                 <textarea name="observaciones" id="observaciones" cols="40" rows="3" disabled><?php echo $pedido["observaciones"]; ?></textarea><br><br>
                 <h3><?php echo $pedido["estado"]; ?></h3><br>
                 <button type="button" id="imprimir" class="btn btn-info hidden-print">Imprimir</button>
              </center>
            </form>

              
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
          order: [[2, 'asc']],
          columnDefs: [
            { type: 'natural', targets: 2 }
          ],
        });
      });

      $("#imprimir").click(function(){
        window.print();
      })


    </script>
    
  </body>
</html>
