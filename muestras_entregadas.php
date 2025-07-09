<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Muestras entregadas</title>

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
                  <h4>Muestras entregadas</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Muestreo
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Muestras entregadas
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
                  
                  if ($_SESSION["tipo"] ==1) {
                    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, e.estado, c.sub_zona, c.responsable FROM muestreos_e p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=p.estado  WHERE p.estado=1 GROUP BY p.id ";
                  }else{
                    $sql = "SELECT p.id, z.zona, u.nombres, u.apellidos, u.tipo, p.fecha, c.colegio, e.estado, c.sub_zona, c.responsable FROM muestreos_e p JOIN colegios c ON p.id_colegio=c.id JOIN zonas z ON z.codigo=c.cod_zona JOIN usuarios u ON u.cod_zona=z.codigo JOIN estados_pedidos e ON e.id=p.estado  WHERE p.id_usuario='".$_SESSION["id"]."' AND p.estado=1 GROUP BY p.id ";
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
                        <th>Empresa</th>
                        <th>Zona</th>
                        <th>Responsable</th>
                        <th>Colegio</th>
                        <th>Acciones</th>                             
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        foreach($pedidos as $pedido) {
                          $promotor= $pedido["nombres"]." ".$pedido["apellidos"];

                          echo'<tr class="odd gradeX">';
                          echo'<td class="center">'.$pedido["id"].'</td>';
                          echo'<td class="center">'.$pedido["fecha"].'</td>';
                          if ($pedido['tipo']==3) {
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
                          echo'<td class="center"><a href="muestreo_colegio_resto.php?id_muestras_e='.$pedido["id"].'">'.$pedido["colegio"].'</a></td>';
                          echo'<td><a class="btn btn-xs btn-danger eliminar" href="#" id="'.$pedido["id"].'">
                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                          </a></td>';
                                                                                         
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

      $(".eliminar").click(function(e){

        e.preventDefault();
        var cod= $(this).attr('id');
        if (confirm("¿Seguro que desea eliminar estas muestras")) {
          window.location="php/eliminar_muestras_e.php?codigo="+cod
        }

      })
    </script>
    
  </body>
</html>
