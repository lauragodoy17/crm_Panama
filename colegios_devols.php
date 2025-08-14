<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Colegios devoluciones de venta</title>

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

                  if (isset($_POST["periodo"])) {
                    $sql_periodo="SELECT id FROM periodos WHERE id='".$_POST["periodo"]."'";
                  }else{
                    $sql_periodo="SELECT id FROM periodos WHERE id='".$_GET["periodo"]."'";
                  }

                  $req_periodo = $bdd->prepare($sql_periodo);
                  $req_periodo->execute();
                  $gp_periodo = $req_periodo->fetch();

                  if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) {

                    $sql = "SELECT c.id, c.dane, c.colegio, c.direccion, c.barrio,c.telefono, CONCAT(u.nombres, ' ', u.apellidos) as promotor FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio JOIN usuarios u ON u.id=p.id_usuario WHERE p.definido='1' AND p.id_periodo='".$gp_periodo["id"]."' GROUP BY c.id";

                  }elseif($_SESSION["tipo"]==3) {
                    $sql = "SELECT c.id, c.dane, c.colegio, c.direccion, c.barrio,c.telefono FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio WHERE p.id_usuario='".$_SESSION["id"]."' AND p.definido='1' AND p.id_periodo='".$gp_periodo["id"]."' GROUP BY c.id";
                  }else{

                    $sql = "SELECT c.id, c.dane, c.colegio, c.direccion, c.barrio,c.telefono FROM colegios c JOIN presupuestos p ON c.id=p.id_colegio WHERE (c.cod_zona='".$_SESSION["zona"]."' OR c.zona_madre='".$_SESSION["zona"]."') AND p.definido='1' AND p.id_periodo='".$gp_periodo["id"]."' GROUP BY c.id";

                  }

                                  
                  $req = $bdd->prepare($sql);
                  $req->execute();

                                
                                

                $colegios = $req->fetchAll();
                                
              ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                    <tr>
                      <th>Codigo</th>
                      <?php if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) { ?>
                        <th>Usuario</th>
                      <?php } ?>
                      <th>Colegio</th>
                      <th>Dirección</th>
                      <th>Barrio</th>
                      <th>Telefono</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php 
                      foreach($colegios as $colegio) {
                                              
                        $id = $colegio['id'];
                        $codigo = $colegio['dane'];
                        $nombre = $colegio['colegio'];
                        $direccion = $colegio['direccion'];
                        $barrio = $colegio['barrio'];
                        $telefono = $colegio['telefono'];
                        $periodo2 = $gp_periodo["id"];
                        echo'<tr class="odd gradeX">';
                        echo'<td class="center">'.$codigo.'</td>';
                        if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) {
                          echo'<td class="center">'.$colegio['promotor'].'</td>';
                        }
                        echo'<td class="center"><a href="solicitar_devol.php?id_colegio='.$id.'&periodo='.$periodo2.'">'.$nombre.'</a></td>';
                        echo'<td class="center">'.$direccion.'</td>';
                        echo'<td class="center">'.$barrio.'</td>';
                        echo'<td class="center">'.$telefono.'</td>';
                                                   
                                                 
                      }
                    ?>
                                        
                    </tr>
                                       
                  </tbody>
                </table>
              </div>
    
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

      $(".vista_soli" ).click(function( e ) {

        e.preventDefault();
        var url= $(this).attr("href")
        var caracteristicas = "height=700,width=1300,scrollTo,resizable=1,scrollbars=1,location=0";
        nueva=window.open(url, "Popup", caracteristicas);

      })


    </script>
    
  </body>
</html>
