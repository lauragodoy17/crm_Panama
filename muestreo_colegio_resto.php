<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if (isset($_GET["id_pedido"])) { ?>
      <?php if($_GET["tp"]==3) {  ?>
        <title>Inkpulse - Muestreo aprobado</title>
      <?php  }elseif($_GET["tp"]==4) { ?>
        <title>Inkpulse - Muestreo anulado</title>
      <?php  }else{ ?>
        <title>Inkpulse - Muestreo entregado</title>
      <?php  } ?>

     
    <?php }else{ ?>
      <title>Inkpulse - Muestras entregadas</title>
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
                  <?php if (isset($_GET["id_pedido"])) { ?>
                    <?php if($_GET["tp"]==3) {  ?>
                      <h4>Muestreo aprobado</h4>
                    <?php  }elseif($_GET["tp"]==4) { ?>
                      <h4>Muestreo anulado</h4>
                    <?php  }else{ ?>
                      <h4>Muestreo entregado</h4>
                    <?php  } ?>
                  <?php }else{ ?>
                    <h4>Muestras entregadas</h4>
                  <?php } ?>
                  
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Muestreo
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php if (isset($_GET["id_pedido"])) { ?>
                        <?php if($_GET["tp"]==3) {  ?>
                          Aprobado
                        <?php  }elseif($_GET["tp"]==4) { ?>
                          Anulado
                        <?php  }else{ ?>
                          Muestreo Entregado
                        <?php  } ?>
                      <?php }else{ ?>
                        Entregadas
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
                if (isset($_GET["id_muestreo"])) {
                    $_GET["id_pedido"]=$_GET["id_muestreo"];
                }

                if (isset($_GET["id_pedido"])) {

                  $sql_pedido="SELECT id FROM muestreos WHERE id='".$_GET["id_pedido"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_pedido="SELECT pe.id, pe.id_periodo, pe.id_colegio, pe.fecha,pe.observaciones, z.zona, c.colegio, c.sub_zona, c.responsable, u.nombres, u.apellidos, u.tipo, e.estado FROM muestreos pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$pedido["id"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();


                  $sql_repetido="SELECT id FROM muestreos WHERE id_periodo='".$pedido["id_periodo"]."' AND id_colegio='".$pedido["id_colegio"]."' AND estado='4'";

                  $req_repetido = $bdd->prepare($sql_repetido);
                  $req_repetido->execute();
                  $num_repetido = $req_repetido->rowCount();
                  $n_repetido = $req_repetido->fetchAll();
                    
                  $sql = "SELECT pe.id, l.id, l.libro, lp.cantidad, lp.cantidad_aprob, lp.id as id_lm, l.isbn, m.materia, g.id as id_grado, g.grado  FROM muestreos pe JOIN libros_muestreos lp ON lp.cod_muestreo=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON m.id=l.id_materia JOIN grados g ON g.id=l.id_grado WHERE pe.id='".$_GET["id_pedido"]."'  GROUP BY l.id";
                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $libros = $req->fetchAll();

                  $sql_op = "SELECT id FROM ordenes_pedidos WHERE id_muestreo='".$_GET["id_pedido"]."' AND estado!=4";

                  $req_op = $bdd->prepare($sql_op);
                  $req_op->execute();
                  $op = $req_op->rowCount();
                  $n_op = $req_op->fetch();

                  if ($op !=0) {
                    echo "<h4>OP <a href='op_pendiente.php?op=".$n_op["id"]."' target='_blank'># ".$n_op["id"]."</a></h4>";
                  }

                }else {

                  $sql_pedido="SELECT id FROM muestreos_e WHERE id='".$_GET["id_muestras_e"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_pedido="SELECT pe.id, pe.id_periodo, pe.id_colegio, pe.fecha,pe.observaciones, z.zona, c.colegio, c.sub_zona, c.responsable, u.nombres, u.apellidos, u.tipo, e.estado FROM muestreos_e pe JOIN colegios c ON pe.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=pe.estado WHERE pe.id='".$_GET["id_muestras_e"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_repetido="SELECT id FROM muestreos_e WHERE id_periodo='".$pedido["id_periodo"]."' AND id_colegio='".$_GET["id_muestras_e"]."'";

                  $req_repetido = $bdd->prepare($sql_repetido);
                  $req_repetido->execute();
                  $num_repetido = $req_repetido->rowCount();
                  $n_repetido = $req_repetido->fetchAll();
                                
                  $sql = "SELECT pe.id, l.id, l.libro, lp.cantidad, lp.cantidad_aprob, lp.id as id_lm, l.isbn, m.materia, g.id as id_grado, g.grado  FROM muestreos_e pe JOIN libros_muestreos_e lp ON lp.cod_muestreo=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON m.id=l.id_materia JOIN grados g ON g.id=l.id_grado WHERE pe.id='".$_GET["id_muestras_e"]."'  GROUP BY l.id";
                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $libros = $req->fetchAll();

                }
                                
                                
              ?>
              <table class="table table-bordered table-hover">
                  <tr>
                    <?php if (isset($_GET["id_pedido"])) { ?>
                      <td># Muestreo: <?php echo $_GET["id_pedido"] ?></td>
                    <?php }else { ?>
                      <td>#: <?php echo $_GET["id_muestras_e"] ?></td>
                    <?php } ?>
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
                              
              </table>
                          
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Isbn</th>
                        <th>Título</th>
                        <th>Materia</th>
                        <th>Grado</th>
                        <?php if (isset($_GET["id_pedido"])) { ?>
                          <th>Cantidad Solicitada</th>
                        <?php }else { ?>
                          <th>Cantidad Entregada</th>
                        <?php } ?>
                        <th>Cantidad aprobada</th>
                      </tr>
                    </thead>
                    <tbody>
                     
                      <?php 
                        foreach($libros as $libro) {
                                           
                          $total_cantidad[]=$libro["cantidad"];
                          $total_cantidad_aprob[]=$libro["cantidad_aprob"];

                          echo'<tr class="odd gradeX">';
                          echo'<td class="">'.$libro["isbn"].'</td>';
                          echo'<td class="">'.$libro["libro"].'</td>';
                          echo'<td class="">'.$libro["materia"].'</td>';
                          echo'<td class="">'.$libro["grado"].'</td>';                   
                          echo'<td class="center">'.$libro["cantidad"].'</td>';
                          echo'<td class="center">'.$libro["cantidad_aprob"].'</td>';
                                          
                        }

                        echo'<input type="hidden" name="id_muestreo" value="'.$pedido["id"].'">';
                                            
                        $total_c=array_sum($total_cantidad);
                        $total_c_aprob=array_sum($total_cantidad_aprob);
                      ?>
                        </tr>               
                        <td></td><td></td><td></td></td><td class="center"><b>Total:</b></td>
                        <td class="center"><b><?php echo $total_c; ?></b></td>
                        <td class="center"><b><?php echo $total_c_aprob; ?></b></td>
                                       
                      </tbody>
                    </table>
                  </div>
                  <input type="hidden" name="id_colegio" value="<?php echo $_GET["id_colegio"]; ?>">
                  <input type="hidden" name="periodo" value="<?php echo $_GET["periodo"]; ?>">

                  <center>
                    <?php if (isset($_GET["id_pedido"])) { ?>
                      <label for="observaciones">Observaciones:</label><br>
                      <textarea class="form-control" name="observaciones" id="observaciones"><?php echo $pedido["observaciones"] ?></textarea><br><br>
                    <?php } ?>
                    <button type="button" id="imprimir" class="btn btn-info d-print-none">Imprimir</button> <br><br>
                    <?php if (isset($_GET["id_pedido"])) { ?>
                      <?php if ($_GET["tp"]==3) { ?>
                        <?php if ($op ==0) { ?>
                          <a href="solicitar_op.php?id_muestreo=<?php echo $_GET["id_pedido"] ?>" target="_blank" class="btn btn-warning hidden-print">Solicitar OP</a>
                        <?php } ?>
                        <button class="btn btn-success hidden-print" id="entregar">Despachar</button><center>
                      <?php } ?>
                      
                    <?php } ?>  
               
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

    <script>

      $("#entregar").click(function(){
        var factura=$("#factura").val()
        window.location="php/accion_muestreo.php?entregado=<?php echo $_GET["id_pedido"] ?>&factura="+factura;
      });

      $("#imprimir").click(function(){
        window.print();
      })

    </script>
    
  </body>
</html>
