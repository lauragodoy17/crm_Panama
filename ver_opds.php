<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Ver OPD'S</title>
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
                  <h4>Ver OPD'S</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      OPD'S
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      
                      Ver OPD'S

                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <div class="row">
              <div class="col-sm-12">
                <!-- PAGE CONTENT BEGINS -->
                <!--<div class="alert alert-info">
                  <button class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                  </button>

                  <i class="ace-icon fa fa-hand-o-right"></i>
                  Please note that demo server is not configured to save the changes, therefore you may see an error message.
                </div>-->

                <?php 
                                  

                                  
                  $sql = "SELECT o.id as opid, o.usuario, o.fecha, o.solicitante, o.estado, o.conse, c.*, CONCAT(u.nombres,' ',u.apellidos) AS usuario FROM ordenes_produccion o JOIN clientes c ON c.id=o.cliente JOIN usuarios u ON u.id=o.usuario WHERE estado !=3";

                  $req = $bdd->prepare($sql);
                  $req->execute();

                  $ops = $req->fetchAll();

                            ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; OPD # &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Usuario&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <th>Cliente</th>
                                            <th>Títulos</th>
                                            <th>Cumplida</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        
                                        <?php 
                                          foreach($ops as $op) {

                                            echo'<tr class="odd gradeX">';

                                            echo'<td class="center"><a href="opd_solicitada.php?opd='.$op["opid"].'">'.date("y").' - '.$op["opid"].'</a></td>';
                                            echo'<td class="center">'.$op["usuario"].'</td>';
                                            echo'<td class="center">'.$op["fecha"].'</td>';

                                            echo'<td class="center">'.$op["cliente"].'</td>';

                                            $sql = "SELECT libro FROM libros_opd WHERE opid='".$op["opid"]."'";

                                            $req = $bdd->prepare($sql);
                                            $req->execute();

                                            $titulos = $req->fetchAll();

                                            echo "<td>";

                                              foreach ($titulos as $titulo) {

                                                echo "- ".$titulo["libro"]." ";
                                              }

                                            echo "</td>";

                                            if ($op["estado"]==4) {
                                              echo'<td class="center" style="color: #24B910">Si</td>';
                                            }else{
                                              echo'<td class="center" style="color: #EA0000">No</td>';
                                            }


                                                    
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
          order: [[2, 'desc']],
          
        });
      });

    


    </script>
    
  </body>
</html>
