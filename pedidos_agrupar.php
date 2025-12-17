<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Agrupar Pedidos</title>

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
                  <h4>Agrupar Pedidos</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Agrupar
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
                               

                  $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, p.fecha, c.colegio, e.id as eid, e.estado FROM pedidos p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=p.estado WHERE p.estado='2' AND p.id_usuario='".$_POST["promotor"]."' AND p.id_periodo='".$_POST["periodo"]."' GROUP BY p.id";
                  $req = $bdd->prepare($sql);
                  $req->execute();               

                  $pedidos = $req->fetchAll();
                                
                ?>

                <div class="">
                  <form action="pedido_agrupado.php" method="POST">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Promotor</th>
                        <th>Colegio</th>
                        <th>Selec.</th>    
                      </tr>
                    </thead>
                    <tbody>
                                
                      <?php 
                        foreach($pedidos as $pedido) {

                          $sql = "SELECT id FROM ordenes_pedidos WHERE id_pedido='".$pedido["id"]."'";

                          $req = $bdd->prepare($sql);
                          $req->execute();
                          $op = $req->rowCount();
                            

                          $sql = "SELECT op FROM op_pedidos_agrupados WHERE id_pedido='".$pedido["id"]."'";

                          $req = $bdd->prepare($sql);
                          $req->execute();
                          $op_agp = $req->rowCount();
                           

                          if ($op ==0 && $op_agp==0) {

                            $promotor= $pedido["nombres"]." ".$pedido["apellidos"];

                              echo'<tr class="odd gradeX">';
                                echo'<td class="center">'.$pedido["id"].'</td>';
                                echo'<td class="center">'.$pedido["fecha"].'</td>';
                                echo'<td class="center">'.$pedido["estado"].'</td>';
                                echo'<td class="center">'.$promotor.'</td>';
                                echo'<td class="center"><a href="pedido_colegio.php?id_pedido='.$pedido["id"].'&tp=3" target="_blank">'.$pedido["colegio"].'</a></td>';
                                echo '<td><input type="checkbox" name="pedidos[]" value="'.$pedido["id"].'"></td></tr>';

                            
                            }
                                                                                        
                        }
                      ?>
                                        
                      </tbody>
                    </table>
                    <input type="hidden" name="periodo" value="<?php echo $_POST["periodo"]; ?>">
                    <center><button class="btn btn-primary">Agrupar</button></center>

                  </form>
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


    </script>
    
  </body>
</html>
