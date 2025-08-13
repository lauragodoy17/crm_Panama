<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Pedido de venta</title>

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
                  <h4>Pedido de venta</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos
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
                                  

                  $sql_pedido="SELECT id FROM pedidos WHERE id='".$_GET["id_pedido"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();
                                  
                  $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.cliente, pe.fac_rem,pe.dir_ent, z.codigo as codzona,z.zona, c.colegio, c.sub_zona, c.responsable,u.nombres, u.apellidos, u.tipo, e.estado, pe.tipo as petipo FROM pedidos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$pedido["id"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();


                  $sql_cliente="SELECT cliente FROM clientes WHERE id='".$pedido["cliente"]."'";

                  $req_cliente = $bdd->prepare($sql_cliente);
                  $req_cliente->execute();
                  $cliente = $req_cliente->fetch();

                  $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.precio, m.materia, lp.cantidad, p.cod_area, p.descuento_d, p.tasa_compra_d, lp.cod_pedido, lp.cantidad_aprob, lp.descuento_aprob,lp.plataforma FROM pedidos pe JOIN libros_pedidos lp ON lp.cod_pedido=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON l.id_materia=m.id JOIN presupuestos p ON p.id_colegio=pe.id_colegio AND p.id_libro=lp.id_libro AND lp.cod_area=p.cod_area AND pe.id_periodo=p.id_periodo WHERE pe.id='".$_GET["id_pedido"]."'  AND p.definido=1 AND lp.cantidad!=0 GROUP BY l.id,p.cod_area";
                  
                  $req = $bdd->prepare($sql);
                  $req->execute();

                  $libros = $req->fetchAll();

                  $sql = "SELECT id FROM ordenes_pedidos WHERE id_pedido='".$_GET["id_pedido"]."' AND estado!=4";


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
                    <?php if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { ?>
                      <?php if ($pedido["petipo"] ==1) { ?>
                        <td>Tipo de pedido: Libros sueltos</td>
                      <?php }else{ ?>
                        <td>Tipo de pedido: Paquete</td>
                      <?php } ?>
                    <?php } ?>
                    </tr>
                  </table>
                          
                  <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                          <tr>
                            <th>Título</th>
                            <th>Materia</th>
                            <th>Grado</th>
                            <th>PVP</th>
                            <th>Desc.</th>
                            <th>Precio Fact.</th>
                            <th>Cant.</th>
                            <th>Desc. Aprobado</th>
                            <th>Cant. Aprobada</th>
                            <th>Valor Venta</th>
                            <?php if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { ?>
                              <th>Plataforma</th>
                            <?php } ?>
                          </tr>
                        </thead>
                        <tbody>
                                
                        <?php
                                          
                          foreach($libros as $libro) {

                            if ($libro["descuento_d"]==="0.0000") {
                              $descuento=$libro["descuento"] * 100;
                            }else{
                              $descuento=$libro["descuento_d"] * 100;
                            }
                                            
                            if ($libro["descuento_aprob"]!="") {
                              $precio_fact=$libro["precio"] -($libro["precio"] * ($libro["descuento_aprob"] / 100) );
                            }else {
                              if ($libro["descuento_d"]==="0.0000") {
                                $precio_fact=$libro["precio"] -($libro["precio"] * $libro["descuento"]);
                              }else{
                                $precio_fact=$libro["precio"] -($libro["precio"] * $libro["descuento_d"]);
                              }
                                              
                              $libro["descuento_aprob"]=$descuento;
                            }

                            if ($_GET["id_pedido"] >500) {

                              if ($libro["cod_area"] != "") {

                                $sql_c = "SELECT cantidad, cantidad_aprob FROM libros_pedidos WHERE cod_area='".$libro["cod_area"]."' AND cod_pedido='".$libro["cod_pedido"]."'";
                                $req_c = $bdd->prepare($sql_c);
                                $req_c->execute();
                                $cantidad= $req_c->fetch();

                                $libro["cantidad"]=$cantidad["cantidad"];

                                if ($cantidad["cantidad_aprob"]!="") {
                                  $libro["cantidad_aprob"]=$libro["cantidad_aprob"];
                                }

                              }
                            
                          
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
                            echo'<td class="">'.$libro["libro"].'</td>';
                            echo'<td class="center">'.$libro["materia"].'</td>';
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
                            echo'<td class="center">'.$libro["descuento_aprob"].' %</td>';
                            echo'<td class="center">'.$libro["cantidad_aprob"].'</td>';
                            echo'<td class="center">$ '.number_format($v_venta,0,",", ".").'</td>';

                            if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { 

                              if ($libro["plataforma"]==0) {
                                echo'<td class="center">No</td>';
                              }else{
                                echo'<td class="center">Si</td>';
                              }
                                               
                                                
                            }
                                                                                          
                          }

                          $total_v=array_sum($total_venta);
                          $total_c=array_sum($total_cantidad);
                          $total_c_aprob=array_sum($total_cantidad_aprob);
                        ?>
                                        

                                      
                        </tbody>
                        <tfoot>
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
                        </tfoot>
                      </table>
                    </div>
                    <input type="hidden" name="id_colegio" value="<?php echo $_GET["id_colegio"]; ?>">
                    <input type="hidden" name="periodo" value="<?php echo $_GET["periodo"]; ?>">

                    <br><center>
                      <label for="observaciones">Observaciones:</label>
                      <?php echo $pedido["observaciones"]; ?><br><br>
                      <label for="">Direción de entrega:</label>
                      <?php echo $pedido["dir_ent"]; ?><br><br>
                      <h3><?php echo $pedido["estado"]; ?></h3><br>
                      <button type="button" id="imprimir" class="btn btn-info hidden-print">Imprimir</button>
                    </center>
                </form>

               
              </div>
            </div>

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
