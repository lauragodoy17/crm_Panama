<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php if ($_GET['tp']==2) { ?>
      <title>Inkpulse - Pedido sin adopción pendiente</title>
    <?php }elseif ($_GET['tp']==3) { ?>
      <title>Inkpulse - Pedido sin adopción aprobado</title>
    <?php }elseif ($_GET['tp']==4) { ?>
      <title>Inkpulse - Pedido sin adopción entregado</title>
    <?php }else { ?>
      <title>Inkpulse - Pedido sin adopción anulado</title>
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
                  <?php if ($_GET['tp']==2) { ?>
                    <h4>Pedido sin adopción pendiente</h4>
                  <?php }elseif ($_GET['tp']==3) { ?>
                    <h4>Pedido sin adopción aprobado</h4>
                  <?php }elseif ($_GET['tp']==4) { ?>
                    <h4>Pedido sin adopción entregado</h4>
                  <?php }else { ?>
                    <h4>Pedido sin adopción anulado</h4>
                  <?php } ?>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Pedido
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                    <?php if ($_GET['tp']==2) { ?>
                      Pendiente
                    <?php }elseif ($_GET['tp']==3) { ?>
                      Aprobado
                    <?php }elseif ($_GET['tp']==4) { ?>
                      Entregado
                    <?php }else { ?>
                      Anulado
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
                <!-- PAGE CONTENT BEGINS -->
                <!--<div class="alert alert-info">
                  <button class="close" data-dismiss="alert">
                    <i class="ace-icon fa fa-times"></i>
                  </button>

                  <i class="ace-icon fa fa-hand-o-right"></i>
                  Please note that demo server is not configured to save the changes, therefore you may see an error message.
                </div>-->

                <?php 
                 
                  if (isset($_GET["id_pedido_dist"])) {
                    $_GET["id_pedido"]=$_GET["id_pedido_dist"];
                  }

                  $sql_pedido="SELECT id FROM pedidos2 WHERE id='".$_GET["id_pedido"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.fecha_r, pe.colegio, pe.archivo, pe.codigo,pe.estado, pe.fac_rem, pe.verify, u.nombres, u.apellidos FROM pedidos2 pe  JOIN usuarios u ON u.id=pe.id_usuario WHERE pe.id='".$pedido["id"]."'";

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.precio, l.isbn, m.materia, lp.cantidad, lp.descuento, lp.cantidad_aprob, lp.descuento_aprob, lp.id as lpid FROM pedidos2 pe JOIN libros_pedidos2 lp ON lp.cod_pedido=pe.codigo JOIN libros l ON l.id=lp.id_libro JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$_GET["id_pedido"]."' AND lp.cantidad!=0 ORDER BY lp.id";


                  $req = $bdd->prepare($sql);
                  $req->execute();

                                
                  $libros = $req->fetchAll();

                  $sql = "SELECT id FROM ordenes_pedidos WHERE id_pedido_dist='".$_GET["id_pedido"]."' AND estado!=4";


                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $op = $req->rowCount();
                  $n_op = $req->fetch();

                  if ($op !=0) {
                    echo "<h4>OP <a href='op_pendiente.php?op=".$n_op["id"]."' target='_blank'># ".$n_op["id"]."</a></h4>";
                  }
                                
                  if ($pedido["estado"]==1 && $pedido["verify"]==0) {
                    echo "<center><h3>Vista previa</h3></center>
                    <div class='alert alert-warning' style='font-size:16px'>Antes de solicitar el pedido puede agregar libros, eliminar libros y modificar las observaciones</div><br>";
                  }

                ?>

                            
                <table class="table table-bordered table-hover">
                  <tr>
                    <td># Pedido: <?php echo $_GET["id_pedido"] ?></td>
                    <td>Colegio: <?php echo $pedido["colegio"] ?></td>
                    <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
                  <tr>
                    <td>Distribuidor: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                    <td>Fecha de recogida: <?php echo $pedido["fecha_r"];?></td>
                    <?php if($pedido["fac_rem"]==1) {?>
                      <td>Factura</td>
                    <?php }else{?>
                      <td>Remisión</td>
                    <?php } ?>
                  </tr>
                </table>
                <center id="impre"></center>
                <input type="hidden" id="fecha_impre">
                <center>Archivo Adjunto:</b> <a href="adjuntos_dist/<?php echo $pedido["archivo"] ?>" style="cursor: pointer;" target="_blank"><?php echo $pedido["archivo"] ?></a></center><br>
                <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Isbn</th>
                      <th>Título</th>
                      <th>Materia</th>
                      <th>Grado</th>
                      <th>PVP</th>
                      <th>Desc.</th>
                      <th>Precio Fact.</th>
                      <th>Cant.</th>
                      <th>Valor Venta</th>
                      <?php if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==21) { ?>
                        <th class="d-print-none">Desc. Aprobado</th>
                        <th class="d-print-none">Cant. Aprobada</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <script src='vendors/scripts/jquery-2.1.4.min.js'></script>
                    <form method="POST" action="php/aprobar_pedido_sa.php" id="form_pedido">
                    <?php 
                      $i=1;
                      foreach($libros as $libro) {
                        
                        $descuento=$libro["descuento"];
                        $precio_fact=$libro["precio"] -($libro["precio"] * ($libro["descuento"] / 100) );

                        $v_venta=$precio_fact * $libro["cantidad"];
                        $total_venta[]=$v_venta;
                        $total_cantidad[]=$libro["cantidad"];

                        echo'<tr class="odd gradeX" id="'.$libro["lpid"].'">';
                          echo'<td class="center">';
                          if (($_SESSION["tipo"] ==1 || $_SESSION["id"]==21) || $pedido["verify"]==0 ) {

                             echo'<button type="button" class="btn btn-danger btn-xs d-print-none" id="e'.$libro["lpid"].'"><i class="fa fa-trash"></i></button> ';
                          }
                                                
                          echo $i.'</td>';
                          echo'<td class="">'.$libro["isbn"].'</td>';
                          echo'<td class="">'.$libro["libro"].'</td>';
                          echo'<td class="center">'.$libro["materia"].'</td>';
                          if ($libro["cod_area"] == "") {

                            $sql_g = "SELECT grado FROM grados WHERE id='".$libro["id_grado"]."'";
                            $req_g = $bdd->prepare($sql_g);
                            $req_g->execute();
                            $grado= $req_g->fetch();
                                                  
                          }else{
                                                  
                            $sql = "SELECT id_grado_otro FROM areas_objetivas WHERE codigo='".$libro["cod_area"]."'";
                            $req = $bdd->prepare($sql);
                            $req->execute();

                            $go = $req->fetch();

                            $sql_g = "SELECT grado FROM grados WHERE id='".$go["id_grado_otro"]."'";
                            $req_g = $bdd->prepare($sql_g);
                            $req_g->execute();
                            $grado= $req_g->fetch();
                          }
                                               
                          echo'<td class="center">'.$grado["grado"].'</td>';
                          echo'<td class="center">$ '.number_format($libro["precio"],0,",", ".").'</td>';
                          echo'<td class="center">'.$descuento.' %</td>';
                          echo'<td class="center">$ '.number_format($precio_fact,0,",", ".").'</td>';
                          echo'<td class="center">'.$libro["cantidad"].'</td>';
                          echo'<td class="center">$ '.number_format($v_venta,0,",", ".").'</td>';
                          if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==21) {
                            echo'<td class="center d-print-none"><input type="number" id="d'.$libro["lpid"].'" name="cantidad_a" value="'.$libro["descuento_aprob"].'" class="form-control dc" size="4"></td>';
                            echo'<td class="center d-print-none"><input type="number" id="c'.$libro["lpid"].'" name="cantidad_a" value="'.$libro["cantidad_aprob"].'" class="form-control dc" size="4"></td>';
                          }
                          echo '<input type="hidden" name="lpid[]" value="'.$libro["lpid"].'">';
                          echo '<input type="hidden" name="lib_p[]" id="l'.$libro["lpid"].'" >';
                                               
                          echo "<script>

                            $('#c".$libro["lpid"]."').keyup(function(){
                              var cant =$(this).val();
                              var desc=$('#d".$libro["lpid"]."').val();
                              $('#l".$libro["lpid"]."').val(cant+'/'+".$libro["lpid"]."+'/'+desc);

                            })

                            $('#d".$libro["lpid"]."').keyup(function(){
                              var desc =$(this).val();
                              var cant=$('#c".$libro["lpid"]."').val();
                              $('#l".$libro["lpid"]."').val(cant+'/'+".$libro["lpid"]."+'/'+desc);

                            })

                            $('#e".$libro["lpid"]."').click(function(){

                              $('#".$libro["lpid"]."').remove();

                            })
                          </script>";
                                                 
                        $i++;
                      }
                      $total_v=array_sum($total_venta);
                      $total_c=array_sum($total_cantidad);
                    ?>
                                        
                    </tr>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"></td>
                    <td class="center"><b>Total:</b></td>
                    <td class="center"><b><?php echo $total_c; ?></b></td>
                    <td class="center"><b>$ <?php echo number_format($total_v,0,",", "."); ?></b></td>
                    <input type="hidden" name="pedido" value="<?php echo $_GET["id_pedido"] ?>">
                    <input type="hidden" name="codigo" value="<?php echo $pedido["codigo"] ?>">
                    <input type="hidden" name="salida" value="pendiente">
                                       
                  </tbody>
                </table>
              </div>

              <?php for ($i=1; $i < 100; $i++) { ?>

                <div id="agg_l<?php echo $i;?>" class="d-none">
                    <h4>Libro #<?php echo $i;?>:</h4>
                    <div class="row">
                      <div class="form-group col-sm-3">
                        <label id="l_materia<?php echo $i;?>" for="materia<?php echo $i;?>" class="control-label">Materia:<small style="color:red;"> *</small></label>
                        <select name="materia[]" id="materia<?php echo $i;?>" class="form-control">
                          <option value="">Seleccionar</option>
                          <?php 
                            $sql = "SELECT id, materia FROM materias";

                            $req = $bdd->prepare($sql);
                            $req->execute();
                            $colegios = $req->fetchAll();

                            foreach($colegios as $colegio) {
                                $id = $colegio['id'];
                                $nom = $colegio['materia'];
                                echo '<option value="'.$id.'">'.$nom.'</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <div class="form-group col-sm-3">
                        <label id="l_libro<?php echo $i;?>" for="libro<?php echo $i;?>" class="control-label">Libro:<small style="color:red;"> *</small></label>
                    
                            <select name="libro" id="libro<?php echo $i;?>" class="form-control custom-select2"></select>
                      </div>


                      <div class="form-group col-sm-3">
                        <label id="l_descuento<?php echo $i;?>" for="descuento<?php echo $i;?>" class="control-label">Descuento %<small style="color:red;"> *</small></label>
                        <input type="number" class="form-control" name="descuento" id="descuento<?php echo $i;?>">
                      </div>

                      <div class="form-group col-sm-3">
                        <label id="l_cantidad<?php echo $i;?>" for="cantidad<?php echo $i;?>" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                        <input type="number" class="form-control" name="cantidad" id="cantidad<?php echo $i;?>">
                      </div>
                    </div>
              
              
                    <input type="hidden" name="libro_e[]" id="libro_e<?php echo $i;?>">
                  </div>

              <?php } ?>

              <?php if (($_SESSION["tipo"] ==1 || $_SESSION["id"]==21) || $pedido["verify"]==0 ) { ?>
                <a id="agregar_libro" style="cursor: pointer;">Agregar libro +</a><br>
              <?php } ?>


              <center>
                 <label for="observaciones">Observaciones:</label><br>
                 <textarea name="observaciones" id="observaciones" cols="100" rows="9" class="form-control"><?php echo $pedido["observaciones"] ?></textarea><br><br>
                  <br><br>
                <?php if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==21 ) {
                    if ($pedido["estado"] ==1) {
                        echo '<button type="button" type="button" class="btn btn-success d-print-none" id="aprobar">Aprobar</button> <button type="button" class="btn btn-danger d-print-none" id="rechazar">Rechazar</button>';
                    }elseif ($pedido["estado"]==2) {
                      if ($op ==0 && $op_agp ==0) {
                        echo '<a href="solicitar_op.php?id_pedido_dist='.$_GET["id_pedido"].'" target="_blank" class="btn btn-warning d-print-none">Solicitar OP</a> ';
                      }

                      echo '<button type="button" class="btn btn-danger d-print-none" id="rechazar">Rechazar</button> <button type="button" class="btn btn-primary d-print-none modificar">Modificar</button> <button class="btn btn-success d-print-none" id="entregar" type="button">Entregar</button>';

                    }
                ?>
                  
                <?php } ?>              

                <?php if ($pedido["verify"]==0 ) { ?>
                  <button type="button" class="btn btn-success d-print-none modificar">Confirmar</button>
                <?php } ?>
                <button type="button" id="imprimir" class="btn btn-info d-print-none">Imprimir</button>
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


      window.addEventListener("beforeprint", function(event) {
        $("#impre").html("<h5><?php echo date("Y-m-d H:i") ?></h5>");
          
        var dataString = 'feid='+"<?php echo date("Y-m-d H:i:s") ?>"+'/'+"<?php echo $_GET["id_pedido"] ?>";
              
          $.ajax({

            url: "ajax/fecha_impre2.php",
            type: "POST",
            data: dataString,
            dataType: "html",
            success: function (resp) {

            },
            error: function (jqXHR,estado,error){
              alert("error");
              console.log(estado);
              console.log(error);
            },
            complete: function (jqXHR,estado){
              console.log(estado);
            }

                          
          })

      });


      $("#aprobar").click(function(){
        $("#form_pedido").submit();
      });

      $(".modificar").click(function(){
        $("#form_pedido").attr("action","php/mod_pedido_sa.php");
        $("#form_pedido").submit();
      });

      $("#rechazar").click(function(){
        window.location="php/accion_pedidos_sa.php?rechazar=<?php echo $_GET["id_pedido"] ?>";
      });

      $("#entregar").click(function(){
        window.location="php/accion_pedidos_sa.php?entregado=<?php echo $_GET["id_pedido"] ?>";
      });

      $("#imprimir").click(function(){
        window.print();
      })

      var m = 1;
    
    $("#agregar_libro").click(function(){
      if (m>98) {
        $("#agregar_libro").addClass("d-none");
      }
    
      $("#agg_l"+m).removeClass("d-none")

      m++;

      <?php for ($i=1; $i < 100; $i++) { ?>

        $('#materia<?php echo $i; ?>').on('change',function(){
          var valor = $(this).val();
          //alert(valor);
          var dataString = 'mat_gra='+valor;
                
          $.ajax({

            url: "ajax/buscar_l_eureka2.php",
            type: "POST",
            data: dataString,
            dataType: "html",
            success: function (resp) {
                   
              $("#libro<?php echo $i; ?>").html(resp);                        
              var cant =$('#cantidad<?php echo $i; ?>').val();
              var libro=$('#libro<?php echo $i; ?>').val();
              var desc=$('#descuento<?php echo $i; ?>').val();
              $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant+'/'+desc);
            },
            error: function (jqXHR,estado,error){
              alert("error");
              console.log(estado);
              console.log(error);
            },
            complete: function (jqXHR,estado){
              console.log(estado);
            }

                            
          })
                
        });

        $('#cantidad<?php echo $i; ?>').keyup(function(){
          var cant =$('#cantidad<?php echo $i; ?>').val();
          var libro=$('#libro<?php echo $i; ?>').val();
          var desc=$('#descuento<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant+'/'+desc);

        })

        $('#libro<?php echo $i; ?>').on('change',function(){

          var cant =$('#cantidad<?php echo $i; ?>').val();
          var libro=$('#libro<?php echo $i; ?>').val();
          var desc=$('#descuento<?php echo $i; ?>').val();
          $('#libro_e<?php echo $i; ?>').val(libro+'/'+cant+'/'+desc);

        })

      <?php } ?>
      
  })


    </script>
    
  </body>
</html>
