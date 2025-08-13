<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Solicitar pedido</title>

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
      input[type=number] { -moz-appearance:textfield; }
      input[type=number]::-webkit-inner-spin-button, 
      input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
      }
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
                  <h4>Solicitar pedidos</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedidos
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                    Solicitar
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
                  $sql_periodo="SELECT id FROM periodos ORDER BY id DESC";

                  $req_periodo = $bdd->prepare($sql_periodo);
                  $req_periodo->execute();
                  $gp_periodo = $req_periodo->fetch();

                  $sql = "SELECT l.id, l.id_grado, l.libro, p.tasa_compra,p.tasa_compra_d, m.materia, p.cod_area FROM libros l JOIN presupuestos p ON l.id=p.id_libro JOIN materias m ON l.id_materia=m.id WHERE p.id_colegio='".$_GET["id_colegio"]."' AND p.id_periodo='".$_GET["periodo"]."' AND p.definido='1' ";
                  $req = $bdd->prepare($sql);
                  $req->execute();

                                
                  $libros = $req->fetchAll();
                                
                ?>
                <form action="php/pedido.php" method="POST">
                  <div class="row">
                    <div class="col-sm-4">
                
                      <div class="form-group" for="cliente">
                        <label>Cliente <small style="color:red;"> *</small> </label>
                        <select class="form-control custom-select2" name="cliente" id="cliente" style="width: 100%;" required>
                          <option selected="selected" value="">Seleccionar</option>
                          <?php 

                            $sql = "SELECT * FROM clientes";
                            $req = $bdd->prepare($sql);
                            $req->execute();
                            $clientes = $req->fetchAll();

                            foreach ($clientes as $cliente) {
                                          
                              echo '<option value="'.$cliente["id"].'">'.$cliente["cliente"].'</option>';
                            }

                          ?>
                        </select>
                      </div>
                    </div>
                    <?php if ($_SESSION['tipo']==3 || $_SESSION['zona']=='5656' || $_SESSION['tipo']==10) { ?>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="tipo" class="control-label">Tipo de pedido:<small style="color:red;"> *</small></label>
                        <select name="tipo" id="tipo" class="form-control" required>
                          <option value="">Seleccionar</option>
                          <option value="1">Libros sueltos</option>
                          <option value="2">Paquetes</option>
                          
                        </select>
                      </div>
                    </div>
                  <?php } ?>
                
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="fac_rem" class="control-label">Factura o Remisión:<small style="color:red;"> *</small></label>
                      <select name="fac_rem" id="fac_rem" class="form-control" required>
                        <option value="0">Seleccionar</option>
                        <option value="1">Factura</option>
                        <option value="2">Remisión</option>
                        
                      </select>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                      <tr>
                        <th>Título</th>
                        <th>Materia</th>
                        <th>Grado</th>
                        <th>Compradores Activos</th>
                        <th>Cantidad<small style="color:red;"> *</small></th>
                          <?php if ($_SESSION['tipo']==3 || $_SESSION['zona']=='5656') { ?>
                            <th>Plataforma<small style="color:red;"> *</small></th>
                          <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <script src='vendors/scripts/jquery-2.1.4.min.js'></script>
                                
                        <?php 
                          foreach($libros as $libro) {
                                              
                            $sql_go = "SELECT id_grado_otro FROM areas_objetivas WHERE codigo='".$libro["cod_area"]."'";

                            $req_go = $bdd->prepare($sql_go);
                            $req_go->execute();
                            $go = $req_go->fetch();

                            $sql_go = "SELECT lp.plataforma FROM libros_pedidos lp JOIN pedidos p ON p.codigo=lp.cod_pedido WHERE lp.id_libro='".$libro["id"]."' AND p.id_periodo='".$_GET["periodo"]."' ";

                            $req_go = $bdd->prepare($sql_go);
                            $req_go->execute();
                            $plataf = $req_go->fetch();
                      
                            if ($go["id_grado_otro"] == 0) {

                              $sql_grado = "SELECT grado FROM grados WHERE id='".$libro["id_grado"]."'";

                              $req_grado = $bdd->prepare($sql_grado);
                              $req_grado->execute();
                              $grado = $req_grado->fetch();
                              
                              $sql_alm = "SELECT SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_grado='".$libro["id_grado"]."' AND id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["id_colegio"]."'";

                              $req_alm = $bdd->prepare($sql_alm);
                              $req_alm->execute();
                              $alm = $req_alm->fetch();
                              
                            }else {

                              $sql_grado = "SELECT grado FROM grados WHERE id='".$go["id_grado_otro"]."'";

                              $req_grado = $bdd->prepare($sql_grado);
                              $req_grado->execute();
                              $grado = $req_grado->fetch();
                              
                              $sql_alm = "SELECT SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_grado='".$go["id_grado_otro"]."' AND id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["id_colegio"]."'";

                              $req_alm = $bdd->prepare($sql_alm);
                              $req_alm->execute();
                              $alm = $req_alm->fetch();
                            }
                            if ($libro["tasa_compra_d"] == 0.00) {
                              $comp_act= $alm["alumnos"] * $libro["tasa_compra"];
                            }else{
                              $comp_act= $alm["alumnos"] * $libro["tasa_compra_d"];
                            }
                        

                            $comp_act=floor($comp_act);

                            echo'<tr class="odd gradeX">';
                            echo'<td class="center">'.$libro["libro"].'</td>';
                            echo'<td class="center">'.$libro["materia"].'</td>';
                            echo'<td class="center">'.$grado["grado"].'</td>';
                            if ($libro["cod_area"] !="") {
                              echo'<td class="center" id="act'.$libro["cod_area"].'">'.$comp_act.'</td>';
                            }else{
                              echo'<td class="center" id="act'.$libro["id"].'">'.$comp_act.'</td>';
                            }
                                                
                            if ($libro["cod_area"] !="") {
                              echo'<td class="center"><input type="number" id="cantidad'.$libro["cod_area"].'" value="0" required size="2"></td>';

                              echo'<input type="hidden" name="libro[]" id="libro'.$libro["cod_area"].'">';
                            }else{
                              echo'<td class="center"><input type="number" id="cantidad'.$libro["id"].'" value="0" required size="2"></td>';
                              echo'<input type="hidden" name="libro[]" id="libro'.$libro["id"].'">';
                            }
                                                  

                            if ($_SESSION['tipo']==3 || $_SESSION['zona']=='5656') { 
                              if ($libro["cod_area"] !="") {

                                if ($plataf["plataforma"] ==0) {

                                  echo'<td class="center"><input type="checkbox" id="plat'.$libro["cod_area"].'" name="plataforma"></td>';

                                }else{
                                  echo'<td class="center"><input type="checkbox" id="plat'.$libro["cod_area"].'" name="plataforma" checked></td>';
                                }
                                                  
                              }else{
                                if ($plataf["plataforma"] ==0) {
                                  echo'<td class="center"><input type="checkbox" id="plat'.$libro["id"].'" name="plataforma"></td>';
                                }else{
                                  echo'<td class="center"><input type="checkbox" id="plat'.$libro["id"].'" name="plataforma" checked></td>';
                                }
                                                  
                              }

                                                
                            }
                                                 
                                                 
                            if ($libro["cod_area"] !="") {
                              echo"<script>
                                $('#cantidad".$libro["cod_area"]."').keyup(function(){

                                  if($('#cantidad".$libro["cod_area"]."').val() > parseInt($('#act".$libro["cod_area"]."').text()) ) {

                                    alert('La cantidad solicitada no puede ser mayor a los compradores activos');
                                    $('#cantidad".$libro["cod_area"]."').val('0')

                                  }else{

                                    var cant =$('#cantidad".$libro["cod_area"]."').val();
                                    var plat=0
                                    
                                    if( $('#plat".$libro["cod_area"]."').prop('checked') ) {
                                      plat=1;
                                    }else{
                                      plat=0;
                                    }

                                    $('#libro".$libro["cod_area"]."').val(".$libro["id"]."+'/'+cant+'/'+plat+'/'+".$libro["cod_area"].");

                                  }   

                                })

                                $('#plat".$libro["cod_area"]."').click(function(){
                                  var cant =$('#cantidad".$libro["cod_area"]."').val();
                                  var plat=0;
                                  
                                  if( $('#plat".$libro["cod_area"]."').prop('checked') ) {    
                                    plat=1;   
                                  }else{
                                    plat=0;
                                  }

                                  $('#libro".$libro["cod_area"]."').val(".$libro["id"]."+'/'+cant+'/'+plat+'/'+".$libro["cod_area"].");

                                                
                                })

                              </script>";
                            }else{

                              echo"<script>
                                $('#cantidad".$libro["id"]."').keyup(function(){

                                  if($('#cantidad".$libro["id"]."').val() > parseInt($('#act".$libro["id"]."').text()) ) {

                                    alert('La cantidad solicitada no puede ser mayor a los compradores activos');

                                    $('#cantidad".$libro["id"]."').val('0')

                                  }else{
                                    var cant =$('#cantidad".$libro["id"]."').val();
                                    var plat=0
                                    if( $('#plat".$libro["id"]."').prop('checked') ) {
                                        plat=1;
                                    }else{
                                      plat=0;
                                    }
                                    

                                    $('#libro".$libro["id"]."').val(".$libro["id"]."+'/'+cant+'/'+plat);
                                  }

                                })

                                $('#plat".$libro["id"]."').click(function(){

                                  var cant =$('#cantidad".$libro["id"]."').val();
                                  var plat=0;
                                  if( $('#plat".$libro["id"]."').prop('checked') ) {    
                                    plat=1;   
                                  }else{
                                    plat=0;
                                  }
                                  $('#libro".$libro["id"]."').val(".$libro["id"]."+'/'+cant+'/'+plat);
                                
                                })
                              </script>";

                            }
                                               
                          }
                        ?>
                                        
  
                        </tbody>
                      </table>
                    </div>
                    <input type="hidden" name="id_colegio" value="<?php echo $_GET["id_colegio"]; ?>">
                    <input type="hidden" name="periodo" value="<?php echo $_GET["periodo"]; ?>">

                    <br><center>
                      <div class="row">
                        <div class="col-sm-4 col-sm-offset-2">
                          <label for="fecha_r">Fecha de Recogida:<small style="color:red;"> *</small></label>
                          <div class="input-group">
                            <input type="text" class="form-control date-picker" name="fecha_r" id="fecha_r" type="text" data-date-format="yyyy-mm-dd" required="" autocomplete="off" />
                            <span class="input-group-addon">
                              <i class="fa fa-calendar bigger-110"></i>
                            </span>
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <label for="dir_ent">Dirección de entrega:<small style="color:red;"> *</small></label>
                          
                          <input type="text" class="form-control" name="dir_ent" id="dir_ent" type="text" required="" />
                          
                        </div>
                      </div>
                
                     <br><label for="observaciones">Observaciones:</label><br>
                     <textarea name="observaciones" id="observaciones" cols="70" rows="8"></textarea><br><br>
                      <button class="btn btn-success" id="solicitar">Solicitar</button></center>
                    </div>
                

                          
                  </form>

                
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
    <script src="src/plugins/datatables/js/natural.js"></script>

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
          "paging": false,
          "searching": false,
          order: [[2, 'asc']],
          columnDefs: [
            { type: 'natural', targets: 2 }
          ]
        });
      });


    </script>
    
  </body>
</html>
