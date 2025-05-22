<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>DeskApp - Bootstrap Admin Dashboard HTML Template</title>

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
                  <h4>blank</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Colegios
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Ver colegios
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30" style="word-break: normal; overflow-wrap: break-word;">
            
            <?php 
                   

                 
                                
                            ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <!--<th>Codigo</th>-->
                                            <th>DANE</th>
                                            <th>Colegio</th>
                                            <?php if ($_SESSION['tipo'] == 1  || $_SESSION["tipo"]==7 || $_SESSION["tipo"]==10) { ?>
                                              <th>Empresa</th>
                                              <th>Zona</th>
                                              <th>Responsable</th>
                                            <?php }elseif ($_SESSION['tipo']==6) { ?>
                                              <th>Zona</th>
                                              <th>Responsable</th>
                                            <?php } ?>
                                            <th>Departamento</th>
                                            <th>Ciudad</th>
                                            <th>Dirección</th>
                                            <th>Barrio</th>
                                            
                                             <th>Periodo</th>
                                            <th>Acciones</th>
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
                $('#dataTables-example').dataTable({

                   processing: true,
                    serverSide: true,
                    ajax: "php/colegios_tabla.php", // tu script PHP aquí

                    <?php if ($_SESSION['tipo'] == 1  || $_SESSION["tipo"]==7 || $_SESSION["tipo"]==10) { ?>
                      columns: [
                          { data: "dane" },
                          { data: "colegio" },
                          { data: "empresa" },
                          { data: "zona" },
                          { data: "responsable" },
                          { data: "departamento" },
                          { data: "ciudad" },
                          { data: "direccion" },
                          { data: "barrio" },
                          { data: "periodo", "orderable": false },
                          { data: "acciones", "orderable": false },
                      ],
                     <?php }elseif ($_SESSION['tipo']==6) { ?>

                          columns: [
                          { data: "dane" },
                          { data: "colegio" },
                          { data: "zona" },
                          { data: "responsable" },
                          { data: "departamento" },
                          { data: "ciudad" },
                          { data: "direccion" },
                          { data: "barrio" },
                          { data: "periodo", "orderable": false },
                          { data: "acciones", "orderable": false },
                      ],
                    <?php }else{ ?>
                         columns: [
                          { data: "dane" },
                          { data: "colegio" },
                          { data: "departamento" },
                          { data: "ciudad" },
                          { data: "direccion" },
                          { data: "barrio" },
                          { data: "periodo", "orderable": false },
                          { data: "acciones", "orderable": false },
                      ],
                    <?php } ?>
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
              }
                });
            });


            $('#dataTables-example').on('click', '.btn-info', function (e) {
              e.preventDefault(); // prevenir que el enlace se siga inmediatamente

              let id = $(this).data('id');
              let codigo = $(this).data('codigo');
              let periodo = $('#periodo' + id).val();

              // Redirigir con los parámetros deseados
              window.location.href = 'colegio.php?codigo=' + codigo + '&periodo=' + periodo;
          });

            $('#dataTables-example').on('click', '.linkcole', function (e) {
              e.preventDefault(); // prevenir que el enlace se siga inmediatamente

              let id = $(this).data('id');
              let codigo = $(this).data('codigo');
              let periodo = $('#periodo' + id).val();

              // Redirigir con los parámetros deseados
              window.location.href = 'colegio.php?codigo=' + codigo + '&periodo=' + periodo;
          });


            $('#dataTables-example').on('click', '.eliminar', function () {
                let cod= $(this).attr('data-codigo');
                if (confirm("¿Seguro que desea eliminar este colegio")) {
                  window.location="php/eliminar_colegio.php?codigo="+cod
              }
              // Aquí tu lógica de eliminación
            });


           
    </script>
    
  </body>
</html>
