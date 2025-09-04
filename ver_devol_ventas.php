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
                  <h4>Devoluciones de venta</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Devoluciones
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
                                  
                  if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) {

                    $sql = "SELECT p.id,  u.nombres, u.apellidos, p.fecha, e.estado,c.cliente, i.colegio FROM devoluciones_v p JOIN usuarios u ON u.id=p.id_usuario JOIN estados_dev e ON e.id=p.estado JOIN clientes c ON c.id=p.cliente LEFT JOIN colegios i ON i.id=p.id_colegio";

                  }elseif($_SESSION["tipo"]==3) {

                    $sql = "SELECT p.id,  u.nombres, u.apellidos, p.fecha, e.estado,c.cliente, i.colegio FROM devoluciones_v p JOIN usuarios u ON u.id=p.id_usuario JOIN estados_dev e ON e.id=p.estado JOIN clientes c ON c.id=p.cliente LEFT JOIN colegios i ON i.id=p.id_colegio WHERE p.id_usuario='".$_SESSION["id"]."'";
                    
                  }else{
                    $sql = "SELECT p.id,  u.nombres, u.apellidos, p.fecha, e.estado,c.cliente, i.colegio FROM devoluciones_v p JOIN usuarios u ON u.id=p.id_usuario JOIN estados_dev e ON e.id=p.estado JOIN clientes c ON c.id=p.cliente LEFT JOIN colegios i ON i.id=p.id_colegio WHERE i.cod_zona='".$_SESSION['zona']."' OR i.zona_madre='".$_SESSION['zona']."'";
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
                        <th>Usuario</th>
                        <th>Colegio</th>
                        <th>Cliente</th>
                        <th>Estado</th>   
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        foreach($pedidos as $pedido) {
                          $promotor= $pedido["nombres"]." ".$pedido["apellidos"];

                          echo'<tr class="odd gradeX">';
                            echo'<td class="center"><a href="devolucion_colegio.php?id_pedido='.$pedido["id"].'">'.$pedido["id"].'</a></td>';
                            echo'<td class="center">'.$pedido["fecha"].'</td>';
                            echo'<td class="center">'.$promotor.'</td>';
                            echo'<td class="center">'.$pedido["colegio"].'</td>';
                            echo'<td class="center">'.$pedido["cliente"].'</td>';
                            echo'<td class="center">'.$pedido["estado"].'</td>';
                                                   
                        }
                      ?>
                                        
                      </tr>
                      </tbody>
                    </table>
                  </div>

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
