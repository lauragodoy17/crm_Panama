<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Atenciones a clientes</title>

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
                  <h4>Atenciones a clientes</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Inicio
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Atenciones a clientes
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

                $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, ca.cargo, s.fecha_entrega, s.conse, c.colegio, CONCAT (u.nombres, ' ',u.apellidos) as promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id LEFT JOIN cargos ca ON ca.id=t.cargo JOIN colegios c ON c.id=s.id_colegio JOIN usuarios u ON u.id=s.usuario WHERE s.id_periodo='".$gp_periodo['id']."' AND s.estado=1 ORDER BY s.id DESC";
                
              }elseif ($_GET['tp'] == 3) {
                $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, ca.cargo, s.fecha_entrega, s.conse, c.colegio, CONCAT (u.nombres, ' ',u.apellidos) as promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id LEFT JOIN cargos ca ON ca.id=t.cargo JOIN colegios c ON c.id=s.id_colegio JOIN usuarios u ON u.id=s.usuario WHERE s.id_periodo='".$gp_periodo['id']."' AND s.estado=2 ORDER BY s.id DESC";
              }elseif ($_GET['tp'] == 4) {
                 $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, ca.cargo, s.fecha_entrega, s.contab, s.conse, c.colegio, CONCAT (u.nombres, ' ',u.apellidos) as promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id LEFT JOIN cargos ca ON ca.id=t.cargo JOIN colegios c ON c.id=s.id_colegio JOIN usuarios u ON u.id=s.usuario WHERE s.id_periodo='".$gp_periodo['id']."' AND s.estado=4 ORDER BY s.id DESC";

              }else{

                $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, ca.cargo, s.fecha_entrega, s.conse, c.colegio, CONCAT (u.nombres, ' ',u.apellidos) as promotor FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id LEFT JOIN cargos ca ON ca.id=t.cargo JOIN colegios c ON c.id=s.id_colegio JOIN usuarios u ON u.id=s.usuario WHERE s.id_periodo='".$gp_periodo['id']."' AND s.estado=3 ORDER BY s.id DESC";
              }

              

              $req = $bdd->prepare($sql);
              $req->execute();
              $solicitudes = $req->fetchAll();
                                
            ?>

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <th>#</th>
                  <th>Fecha</th>
                  <th>Usuario</th>
                  <th>Colegio</th>
                  <th>Solicitante (Cargo)</th>
                  <th>Fecha de entrega</th>
                  <th>Valor de la solicitud</th>
                  <th>Estado</th>
                  <?php if ($_GET['tp'] == 4) { ?>
                    <th>Contabilizada</th>
                  <?php } ?>
                </thead>
                <tbody>
                <?php 
                                          
                  foreach ($solicitudes as $solicitud) {

                    $sql = "SELECT SUM(presupuesto) as sum_solici FROM recursos_solicitados WHERE id_solicitud='".$solicitud["id"]."'";

                    $req = $bdd->prepare($sql);
                    $req->execute();
                    $total = $req->fetch();

                    echo "<tr>";
                      if ($solicitud["id"] < 221) {
                        echo "<td><a href='vista_solicitud.php?id=".$solicitud["id"]."' class='vista_soli'>".$solicitud["id"]."</a></td>";
                      }else{
                        echo "<td><a href='vista_solicitud.php?id=".$solicitud["id"]."' class='vista_soli'>".$solicitud["conse"]."</a></td>";
                      }
                                              
                      echo "<td>".$solicitud["fecha"]."</td>";
                      echo "<td>".$solicitud["promotor"]."</td>";
                      echo "<td>".$solicitud["colegio"]."</td>";
                      echo "<td>".$solicitud["solicitante"]." (".$solicitud["cargo"].")</td>";
                      echo "<td>".$solicitud["fecha_entrega"]."</td>";
                      echo "<td>$ ".number_format($total["sum_solici"],0,",", ".")."</td>";
                      echo "<td>".$solicitud["estado"]."</td>";
                      if ($_GET['tp'] == 4) {
                        if ($solicitud["contab"]==0) {
                          echo "<td>No</td>";
                          }else{
                            echo "<td>Si</td>";
                          }
                      }
                    echo "</tr>";
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
