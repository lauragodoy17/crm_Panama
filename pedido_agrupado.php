<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Pedido agrupado</title>

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
                  <h4>Pedido agrupado</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Pedido agrupado
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <div class="row">
              <div class="col-sm-12">
              
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Pedidos:

                      <?php
                        foreach ($_POST['pedidos'] as $pedidoa) {
                          echo $pedidoa.", ";

                          $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.cliente, pe.fac_rem,pe.dir_ent,z.codigo as codzona, z.zona, c.colegio, u.nombres, u.apellidos, u.tipo,u.id as uid, e.estado, pe.tipo as petipo FROM pedidos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$pedidoa."'";

                          $req_pedido = $bdd->prepare($sql_pedido);
                          $req_pedido->execute();
                          $pedido = $req_pedido->fetch();

                          $sql_cliente="SELECT cliente FROM clientes WHERE id='".$pedido["cliente"]."'";

                          $req_cliente = $bdd->prepare($sql_cliente);
                          $req_cliente->execute();
                          $cliente = $req_cliente->fetch();
                        }

                      ?>

                    </td>
                    <td>Promotor: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                                
                  </tr>
                  <tr>
    
                  </tr>
                  <tr>
                    <td>Cliente: <?php echo $cliente["cliente"] ?></td>
                    <?php if ($pedido["fac_rem"] ==1) { ?>
                      <td>Factura</td>
                    <?php }else{ ?>
                      <td>Remisión</td>
                    <?php } ?>

                    <?php if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { ?>
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
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                    <tr>
                      <th>Isbn</th>
                      <th>Título</th>
                      <th>Ubicación</th>
                      <th class="hidden-print">Materia</th>
                      <th>Grado</th>
                      <th>PVP</th>
                      <th>Desc.</th>
                      <th>Precio Fact.</th>
                      <th>Cant.</th>
                      <th>Valor Venta</th>
                      <?php if ($pedido["tipo"]==3 || $pedido["codzona"]=='5656') { ?>
                        <th>Plataforma</th>
                      <?php } ?>
                                           
                    </tr>
                  </thead>
                  <tbody>

                    <?php 
      
                      foreach ($_POST['pedidos'] as $pedidoa) {

                        $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.cliente, pe.fac_rem,pe.dir_ent,z.codigo as codzona, z.zona, c.colegio, u.nombres, u.apellidos, u.tipo,u.id as uid, e.estado, pe.tipo as petipo FROM pedidos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$pedidoa."'";

                        $req_pedido = $bdd->prepare($sql_pedido);
                        $req_pedido->execute();
                        $pedido = $req_pedido->fetch();

                        $sql_cliente="SELECT cliente FROM clientes WHERE id='".$pedido["cliente"]."'";

                        $req_cliente = $bdd->prepare($sql_cliente);
                        $req_cliente->execute();
                        $cliente = $req_cliente->fetch();

                        $sql = "SELECT pe.id, l.id as libroid, l.id_grado, l.libro, l.precio, l.isbn, m.materia, lp.cantidad, p.cod_area, p.descuento_d, p.tasa_compra_d, lp.id as lpid,lp.plataforma FROM pedidos pe JOIN libros_pedidos lp ON lp.cod_pedido=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON l.id_materia=m.id JOIN presupuestos p ON p.id_colegio=pe.id_colegio AND p.id_libro=lp.id_libro AND pe.id_periodo=p.id_periodo AND lp.cantidad!=0 WHERE pe.id='".$pedidoa."' AND p.definido=1 GROUP BY l.id,p.cod_area";
                        $req = $bdd->prepare($sql);
                        $req->execute();

                                
                        $libros = $req->fetchAll();

                        foreach ($libros as $libro) {
                          $libros_a[]=$libro["libroid"];
                        }
                                                     
                      }
                            
                      $libros_a=array_unique($libros_a);
                                  
                    ?>
                             
                                    
                    <?php
                                          
                      foreach($libros_a as $libro_a) {
                                            
                        $sql = "SELECT pe.id, l.id as libroid, l.id_grado, l.libro, l.precio, l.isbn, m.materia, lp.cantidad, p.cod_area, p.descuento_d, p.tasa_compra_d, lp.id as lpid,lp.plataforma FROM pedidos pe JOIN libros_pedidos lp ON lp.cod_pedido=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON l.id_materia=m.id JOIN presupuestos p ON p.id_colegio=pe.id_colegio AND p.id_libro=lp.id_libro AND pe.id_periodo=p.id_periodo WHERE lp.id_libro='".$libro_a."' AND p.id_periodo='".$_POST['periodo']."' AND pe.id_usuario='".$pedido["uid"]."' AND p.definido=1 AND lp.cantidad!=0 GROUP BY l.id,p.cod_area";
                        $req = $bdd->prepare($sql);
                        $req->execute();

                        $libro = $req->fetch();
                                           
                        $descuento=$libro["descuento_d"] * 100;
                        $precio_fact=$libro["precio"] -($libro["precio"] * $libro["descuento_d"]);
                         
                        //$total_cantidad[]=$libro["cantidad"];

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
                          echo'<td class="center hidden-print">'.$libro["materia"].'</td>';
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

                          if ($_GET["id_pedido"] >500) {
                            
                            $sql_c = "SELECT cantidad FROM libros_pedidos WHERE cod_area='".$libro["cod_area"]."'";
                            $req_c = $bdd->prepare($sql_c);
                            $req_c->execute();
                            $cantidad= $req_c->fetch();
                            $libro["cantidad"]=$cantidad["cantidad"];

                          }
  
                        }
                        echo'<td class="center">'.$grado["grado"].'</td>';
                        echo'<td class="center">$ '.number_format($libro["precio"],0,",", ".").'</td>';
                        echo'<td class="center">'.$descuento.' %</td>';
                        echo'<td class="center">$ '.number_format($precio_fact,0,",", ".").'</td>';

                        echo'<td class="center">';
                          foreach ($_POST['pedidos'] as $pedidoa) {

                            $sql = "SELECT lp.cantidad, lp.cantidad_aprob FROM libros_pedidos lp JOIN pedidos p ON lp.cod_pedido=p.codigo WHERE p.id='".$pedidoa."' AND lp.id_libro='".$libro["libroid"]."' ";
                            $req = $bdd->prepare($sql);
                            $req->execute();

                            $cantidades = $req->fetchAll();

                            foreach ($cantidades as $cantidad) {

                              if ($cantidad["cantidad_aprob"]>0) {
                                $cantidad_sum[$libro["libroid"]]+=$cantidad["cantidad_aprob"];
                              }else{
                                $cantidad_sum[$libro["libroid"]]+=$cantidad["cantidad"];
                              }
                              
                              
                              
                            }
                          
                          }
                          echo $cantidad_sum[$libro["libroid"]];

                          $v_venta=$precio_fact * $cantidad_sum[$libro["libroid"]];
                          $total_venta[]=$v_venta;

                          $total_cantidad[]=$cantidad_sum[$libro["libroid"]];
                          //echo $cantidades_l[$libro["libroid"]];
                        echo '</td>';
                        //echo'<td class="center">'.$libro["cantidad"].'</td>';
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
                    ?>
                                        
                  </tr>
                </tbody>
                <td class="center"></td>
                <td class="center hidden-print"></td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center"></td>
                <td class="center"><b>Total:</b></td>
                <td class="center"><b><?php echo $total_c; ?></b></td>
                <td class="center"><b>$ <?php echo number_format($total_v,0,",", "."); ?></b></td>
                                   
              </table>
               
              <form action="solicitar_op.php" method="POST">
                <?php foreach ($_POST['pedidos'] as $pedidoa) { ?>
                  <input type="hidden" name="pedidos_agp[]" value="<?php echo $pedidoa ?>">
                <?php } ?>
                <br><center> <button type="button" id="imprimir" class="btn btn-info hidden-print">Imprimir</button> <button  class="btn btn-warning hidden-print">Solicitar OP</button></center>
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
          paging: false,
          order: [[0, 'desc']]
        });
      });

      $(".vista_soli" ).click(function( e ) {

        e.preventDefault();
        var url= $(this).attr("href")
        var caracteristicas = "height=700,width=1300,scrollTo,resizable=1,scrollbars=1,location=0";
        nueva=window.open(url, "Popup", caracteristicas);

      })

      $("#imprimir").click(function(){
        window.print();
      })


    </script>
    
  </body>
</html>
