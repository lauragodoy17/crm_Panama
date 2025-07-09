<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Muestreo</title>

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
                  <h4>Muestreo</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Muestreo
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php if($_GET["tp"]==2) {  ?>
                        Pendientes
                      <?php } else if($_GET["tp"]==3) {  ?>
                        Aprobados
                      <?php  }elseif($_GET["tp"]==4) { ?>
                        Despachados
                      <?php  }else{ ?>
                        Anulados
                      <?php  } ?>
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <?php 

              $sql_periodo="SELECT id FROM periodos ORDER BY id DESC";

              $req_periodo = $bdd->prepare($sql_periodo);
              $req_periodo->execute();
              $gp_periodo = $req_periodo->fetch();

              
              if ($_GET['tp'] == 1) {

                $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, ca.cargo, s.fecha_entrega, s.conse, c.colegio, CONCAT (u.nombres, ' ',u.apellidos) as promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id LEFT JOIN cargos ca ON ca.id=t.cargo JOIN colegios c ON c.id=s.id_colegio JOIN usuarios u ON u.id=s.usuario WHERE s.id_periodo='".$gp_periodo['id']."' ORDER BY s.id DESC";
              }elseif ($_GET['tp'] == 2) {

                if ($_SESSION['tipo'] != 10) {
                    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='1' GROUP BY p.id";
                }else{
                  $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='1' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
                }
                
              }elseif ($_GET['tp'] == 3) {

                if ($_SESSION['tipo'] != 10) {
                    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='2' GROUP BY p.id";
                }else{
                  $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='2' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
                }

              }elseif ($_GET['tp'] == 4) {
                 if ($_SESSION['tipo'] != 10) {
                    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='4' GROUP BY p.id";
                }else{
                  $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='4' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
                }

              }else{

                if ($_SESSION['tipo'] != 10) {
                    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='3' GROUP BY p.id";
                }else{
                  $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, c.sub_zona, c.responsable FROM muestreos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='3' AND (c.cod_zona='".$_SESSION['zona']."' OR c.zona_madre='".$_SESSION['zona']."') GROUP BY p.id";
                }
              }

              

              $req = $bdd->prepare($sql);
              $req->execute();
              $pedidos = $req->fetchAll();
                                
            ?>

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                 <th>#</th>
                  <th>Fecha</th>
                  <th>Empresa</th>
                  <th>Zona</th>
                  <th>Usuario</th>
                  <th>Colegio</th>    
                </thead>
                <tbody>
                        
                  <?php 
                    foreach($pedidos as $pedido) {
                      $promotor= $pedido["nombres"]." ".$pedido["apellidos"];

                      echo'<tr class="odd gradeX">';
                      echo'<td class="center">'.$pedido["id"].'</td>';
                      echo'<td class="center">'.$pedido["fecha"].'</td>';
                      if ($pedido['tipo']==3 || $pedido['tipo']==1) {
                        list($empresa,$n_zona) = explode("/", $pedido["zona"]);
                        echo'<td class="center">'.$empresa.'</td>';
                        echo'<td class="center">'.$n_zona.'</td>';
                        echo'<td class="center">'.$promotor.'</td>';

                      }else{

                        $sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$pedido["sub_zona"]."'";
                        $req_sz = $bdd->prepare($sql_sz);
                        $req_sz->execute();
                        $sub_zona = $req_sz->fetch();

                        echo'<td class="center">'.$pedido["zona"].'</td>';
                        echo'<td class="center">'.$sub_zona["sub_zona"].'</td>';
                        echo'<td class="center">'.$pedido["responsable"].'</td>';
                                                
                      }
                      if ($_GET['tp'] != 2) {
                        echo'<td class="center"><a href="muestreo_colegio_resto.php?id_pedido='.$pedido["id"].'&tp='.$_GET["tp"].'">'.$pedido["colegio"].'</a></td>';
                      }else{
                        echo'<td class="center"><a href="muestreo_colegio.php?id_pedido='.$pedido["id"].'">'.$pedido["colegio"].'</a></td>';
                      }
                      
                                                 
                                               
                    }
                  ?>
                                       
                </tbody>
              </table>
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
          order: [[0, 'desc']]
        });
      });

      $(".vista_soli" ).click(function( e ) {

        e.preventDefault();
        var url= $(this).attr("href")
        var caracteristicas = "height=700,width=1300,scrollTo,resizable=1,scrollbars=1,location=0";
        nueva=window.open(url, "Popup", caracteristicas);

      })


    </script>
    
  </body>
</html>
