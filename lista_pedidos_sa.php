<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if ($_GET['tp']==2) { ?>
      <title>Inkpulse - Pedidos sin adopción pendientes</title>
    <?php }elseif ($_GET['tp']==3) { ?>
      <title>Inkpulse - Pedidos sin adopción aprobados</title>
    <?php }elseif ($_GET['tp']==4) { ?>
      <title>Inkpulse - Pedidos sin adopción entregados</title>
    <?php }else { ?>
      <title>Inkpulse - Pedidos sin adopción anulados</title>
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
                    <h4>Pedidos sin adopción pendientes</h4>
                  <?php }elseif ($_GET['tp']==3) { ?>
                    <h4>Pedidos sin adopción aprobados</h4>
                  <?php }elseif ($_GET['tp']==4) { ?>
                    <h4>Pedidos sin adopción entregados</h4>
                  <?php }else { ?>
                    <h4>Pedidos sin adopción anulados</h4>
                  <?php } ?>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos sin adopción
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      
                      <?php if ($_GET['tp']==2) { ?>
                        Pendientes
                      <?php }elseif ($_GET['tp']==3) { ?>
                        Aprobados
                      <?php }elseif ($_GET['tp']==4) { ?>
                        Entregados
                      <?php }else { ?>
                        Anulados
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
                  

                  if ($_GET['tp']==2) {
                    
                    $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p  JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='1' AND p.verify='1' GROUP BY p.id";

                  }elseif ($_GET['tp']==3) {
                    
                    $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p  JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='2' GROUP BY p.id";


                  }elseif ($_GET['tp']==4) {
                    
                    $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p  JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='4' GROUP BY p.id";

                  }elseif ($_GET['tp']==5) {
                    
                    $sql = "SELECT p.id, u.nombres, u.apellidos, p.fecha, p.colegio FROM pedidos2 p  JOIN usuarios u ON u.id=p.id_usuario WHERE p.estado='3' GROUP BY p.id";

                  }
                  
                                  
                  $req = $bdd->prepare($sql);
                  $req->execute();

                  $pedidos = $req->fetchAll();
                                
                ?>
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Distribuidor</th>
                        <th>Colegio</th>   
                      </tr>
                      </thead>
                      <tbody>
                      <?php 
                        foreach($pedidos as $pedido) {
                          $promotor= $pedido["nombres"]." ".$pedido["apellidos"];

                          echo'<tr class="odd gradeX">';
                          echo'<td class="center">'.$pedido["id"].'</td>';
                          echo'<td class="center">'.$pedido["fecha"].'</td>';
                          echo'<td class="center">'.$promotor.'</td>';
                          echo'<td class="center"><a href="pedido_colegio_sa.php?id_pedido='.$pedido["id"].'&tp='.$_GET["tp"].'">'.$pedido["colegio"].'</a></td>';
                                                 
                        }
                      ?>
                                        
                      </tr>
                                       
                    </tbody>
                  </table>
                </div>
               
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
          order: [[0, 'desc']]
        });
      });

    


    </script>
    
  </body>
</html>
