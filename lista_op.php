<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Ordenes de pedido</title>

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
                  <?php if ($_GET['tp']==1) { ?>
                    <h4>Todas las OP</h4>
                  <?php }elseif($_GET['tp']==2){ ?>
                    <h4>OP pendientes</h4>
                  <?php }elseif($_GET['tp']==3){ ?>
                    <h4>OP atendidas</h4>
                  <?php }else{ ?>
                    <h4>OP anuladas</h4>
                  <?php } ?>

                  
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Ordenes de pedido
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      <?php if ($_GET['tp']==1) { ?>
                        Todas las OP
                      <?php }elseif($_GET['tp']==2){ ?>
                        OP pendientes
                      <?php }elseif($_GET['tp']==3){ ?>
                        OP atendias
                      <?php }else{ ?>
                         OP anuladas
                      <?php } ?>
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                    <tr>
                                            <!--<th>Codigo</th>-->
                      <th>OP #</th>
                      <th>Usuario</th>
                      <th>Documento</th>
                      <th>Fecha</th>
                      <th>Cliente</th>
                      <th>Estado</th>
                      <?php if ($_GET['tp']==4) { ?>
                        <th>Fecha anulada</th>
                        <th>Usuario anulación</th>
                      <?php } ?>
                      <th></th>
                    </tr>
                  </thead>
                                   
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
        $.fn.dataTable.ext.errMode = 'none';
        $('#dataTables-example').dataTable({

          processing: true,
          serverSide: true,
          ajax: "ajax/ops.php?tp=<?php echo $_GET['tp'] ?> ", // tu script PHP aquí

            columns: [
              { data: "id" },
              { data: "usuario" },
              { data: "documento" },
              { data: "fecha" },
              { data: "cliente" },
              { data: "estado" },

              <?php if ($_GET['tp']==4) {?>
                { data: "usuario_anu" },
                { data: "fecha_anu" },
              <?php } ?>
              { data: "acciones", "orderable": false },
            ],
            
              
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
                  
            });
        });
           
    </script>
    
  </body>
</html>
