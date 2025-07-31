<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if (isset($_GET["id_pedido"])) { ?>
      <title>Inkpulse - Muestreo pendiente</title>
    <?php }else{ ?>
      <<title>Inkpulse - Muestras entregadas</title>
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
                    <h4>Muestreo pendiente</h4>
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
                        Pendiente
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
                    
                  $sql = "SELECT pe.id, l.id, l.libro, lp.cantidad, lp.id as id_lm, l.isbn, m.materia, g.id as id_grado, g.grado  FROM muestreos pe LEFT JOIN libros_muestreos lp ON lp.cod_muestreo=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON m.id=l.id_materia LEFT JOIN grados g ON g.id=l.id_grado WHERE pe.id='".$_GET["id_pedido"]."'  GROUP BY l.id";
                  $req = $bdd->prepare($sql);
                  $req->execute();

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
                                
                  $sql = "SELECT pe.id, l.id, l.libro, lp.cantidad, lp.id as id_lm, l.isbn, m.materia, g.id as id_grado, g.grado  FROM muestreos_e pe LEFT JOIN libros_muestreos_e lp ON lp.cod_muestreo=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON m.id=l.id_materia LEFT JOIN grados g ON g.id=l.id_grado WHERE pe.id='".$_GET["id_muestras_e"]."'  GROUP BY l.id";
                  $req = $bdd->prepare($sql);
                  $req->execute();

                }
                                
                $libros = $req->fetchAll();
                                
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
                        <?php if (isset($_GET["id_pedido"])) { ?>
                          <th>Cantidad aprobada</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <script src='vendors/scripts/jquery-2.1.4.min.js'></script>
                      <form action="php/aprobar_muestreo.php" method="POST">
                      <?php 
                        foreach($libros as $libro) {
                                           
                          $total_cantidad[]=$libro["cantidad"];

                          echo'<tr class="odd gradeX">';
                          echo'<td class="">'.$libro["isbn"].'</td>';
                          echo'<td class="">'.$libro["libro"].'</td>';
                          echo'<td class="">'.$libro["materia"].'</td>';
                          echo'<td class="">'.$libro["grado"].'</td>';
                                              
                          echo'<td class="center">'.$libro["cantidad"].'</td>';
                          if (isset($_GET["id_pedido"])) {

                            echo'<td class="center"><input type="number" id="cantidad_aprob'.$libro["id_lm"].'" value="0" required></td>';
                          }

                          echo'<input type="hidden" name="libro_m[]" id="libro_m'.$libro["id_lm"].'">';
                                               

                          echo"<script>
                            $('#cantidad_aprob".$libro["id_lm"]."').keyup(function(){
                              var cant =$('#cantidad_aprob".$libro["id_lm"]."').val();

                              $('#libro_m".$libro["id_lm"]."').val(".$libro["id_lm"]."+'/'+cant);

                            })
                          </script>";
                                               
                        }

                        echo'<input type="hidden" name="id_muestreo" value="'.$pedido["id"].'">';
                                            
                        $total_c=array_sum($total_cantidad);
                      ?>
                        </tr>               
                        <td></td><td></td><td></td></td><td class="center"><b>Total:</b></td>
                        <td class="center"><b><?php echo $total_c; ?></b></td>
                                       
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
                      <button class="btn btn-success d-print-none">Aprobar</button>
                      <a class="btn btn-danger d-print-none" id="rechazar">Rechazar</a>
                    <?php } ?>  
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

    <script>

      $("#aprobar").click(function(){
        var obs=$("#observaciones").val();

        window.location="php/accion_muestreo.php?aprobar=<?php echo $_GET["id_pedido"] ?>&observaciones="+encodeURIComponent(obs);
      });

      $("#rechazar").click(function(){
        window.location="php/accion_muestreo.php?rechazar=<?php echo $_GET["id_pedido"] ?>";
      });

      $("#imprimir").click(function(){
        window.print();
      })

    </script>
    
  </body>
</html>
