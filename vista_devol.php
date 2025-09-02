<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Devolución</title>

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
                  <h4>Devolución</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Devoluciones
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Ver
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
                
                  if ($_GET["tipo"] =="1") {

                    $sql_pedido="SELECT id FROM devoluciones WHERE id='".$_GET["id_devol"]."'";
                  }else{
                    $sql_pedido="SELECT id FROM devoluciones_prov WHERE id='".$_GET["id_devol"]."'";
                  }

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  if ($_GET["tipo"] =="1") {
                    $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.archivo,pe.codigo,u.nombres, u.apellidos, e.id as eid,e.estado, c.cliente, c.id as cid FROM devoluciones pe JOIN usuarios u ON u.id=pe.id_usuario JOIN estados_dev e ON e.id=pe.estado JOIN clientes c ON pe.persona=c.id WHERE pe.id='".$pedido["id"]."'";
                  }else{

                    $sql_pedido="SELECT pe.fecha,pe.observaciones,pe.archivo,pe.codigo,u.nombres, u.apellidos, e.id as eid,e.estado, c.proveedor as cliente, c.id as cid FROM devoluciones_prov pe JOIN usuarios u ON u.id=pe.id_usuario JOIN estados_dev e ON e.id=pe.estado JOIN proveedores c ON pe.persona=c.id WHERE pe.id='".$pedido["id"]."'";

                  }
                                  
                                  

                  $req_pedido = $bdd->prepare($sql_pedido);
                  $req_pedido->execute();
                  $pedido = $req_pedido->fetch();

                  if ($_GET["tipo"] =="1") {

                    $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.isbn, m.materia, lp.cantidad, lp.id as lpid FROM devoluciones pe LEFT JOIN libros_devol lp ON lp.cod_pedido=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$_GET["id_devol"]."'";

                  }else{
                    $sql = "SELECT pe.id, l.id, l.id_grado, l.libro, l.isbn, m.materia, lp.cantidad, lp.id as lpid FROM devoluciones_prov pe LEFT JOIN libros_devol lp ON lp.cod_pedido=pe.codigo LEFT JOIN libros l ON l.id=lp.id_libro LEFT JOIN materias m ON l.id_materia=m.id WHERE pe.id='".$_GET["id_devol"]."'";
                  }

                  $req = $bdd->prepare($sql);
                  $req->execute();
                  
                  $libros = $req->fetchAll();

                  if ($_GET["tipo"] =="1") {

                    $sql = "SELECT id, estado FROM ordenes_pedidos WHERE id_devol_c='".$_GET["id_devol"]."' AND estado!=4";

                  }else{
                    echo $_GET["id_devol_p"];
                    $sql = "SELECT id, estado FROM ordenes_pedidos WHERE id_devol_p='".$_GET["id_devol"]."' AND estado!=4";
                  }
                  $req = $bdd->prepare($sql);
                  $req->execute();
                  $op = $req->rowCount();
                  $n_op = $req->fetch();

                  if ($op !=0) {
                    echo "<h4>OP <a href='op_pendiente.php?op=".$n_op["id"]."' target='_blank'># ".$n_op["id"]."</a></h4>";
                  }
                                
                ?>
                <table class="table table-bordered table-hover">
                  <tr>
                    <?php if ($_GET["tipo"] =="1") { ?>
                      <td>Devolución de muestras # <?php echo $_GET["id_devol"] ?></td>
                    <?php }else{ ?>
                      <td>Devolución de proveedor # <?php echo $_GET["id_devol"] ?></td>
                    <?php } ?>
                      <td>Fecha: <?php echo $pedido["fecha"] ?></td>
                  </tr>
                  <tr>
                    <td>Usuario: <?php echo $pedido["nombres"]." ".$pedido["apellidos"] ?></td>
                    <?php if ($pedido["estado"]!="Anulado") {?>
                      <form method="POST" action="php/mod_devol.php" id="form_pedido">
                        <?php if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) { ?>
                          <div class="form-group col-sm-9" for="persona">
                            <label>Cliente<small style="color:red;"> *</small> </label>
                              <select class="form-control select2" name="persona" id="persona" style="width: 100%;" required>
                                <option selected="selected" value="">Seleccionar</option>
                                  <?php 

                                    $sql = "SELECT * FROM clientes";

                                    $req = $bdd->prepare($sql);
                                    $req->execute();

                                    $clientes = $req->fetchAll();

                                    foreach ($clientes as $cliente) {

                                      if ($cliente["id"] == $pedido["cid"]) {
                                                
                                        echo '<option value="'.$cliente["id"].'" SELECTED>'.$cliente["cliente"].'</option>';

                                      }else{

                                        echo '<option value="'.$cliente["id"].'">'.$cliente["cliente"].'</option>';

                                      }
                                              
                                              
                                    }

                                  ?>
                                </select>
                              </div>
                        <?php }else{ ?>
                          <td>Cliente: <?php echo $pedido["cliente"] ?></td>
                        <?php } ?>

                      <?php }else{ ?>
                        <td>Cliente: <?php echo $pedido["cliente"] ?></td>
                      <?php } ?>
                                
                  </tr>
                </table>

                <center><h5>Soporte Adjunto:</b> <a href="adjuntos/<?php echo $pedido["archivo"] ?>" style="cursor: pointer;" target="_blank"><?php echo $pedido["archivo"] ?></a></h5></center><br>

                <center id="impre"></center>
                <input type="hidden" id="fecha_impre">
                          
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Isbn</th>
                        <th>Título</th>
                        <th>Materia</th>
                        <th>Grado</th>
                        <th>Cant.</th>
                      </tr>
                    </thead>
                    <tbody>
                      <script src='vendors/scripts/jquery-2.1.4.min.js'></script>                
                      <?php 
                        $i=1;
                        foreach($libros as $libro) {
                                           
                          $total_cantidad[]=$libro["cantidad"];

                          echo'<tr class="odd gradeX" id="'.$libro["lpid"].'">';
                            echo'<td class="center">';
                              if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==22) {

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
                            if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==22 ) {
                              echo'<td class="center"><input type="number" id="c'.$libro["lpid"].'" name="cantidad_a" value="'.$libro["cantidad"].'" class="form-control dc" size="4"></td>';
                            }else{

                              echo'<td class="center">'.$libro["cantidad"].'</td>';
                            }

                            echo '<input type="hidden" name="lpid[]" value="'.$libro["lpid"].'">';
                            echo '<input type="hidden" name="lib_p[]" id="l'.$libro["lpid"].'" >';

                            echo "<script>

                              $('#c".$libro["lpid"]."').keyup(function(){
                                var cant =$(this).val();
                                $('#l".$libro["lpid"]."').val(cant+'/'+".$libro["lpid"].");

                              })


                              $('#e".$libro["lpid"]."').click(function(){

                                $('#".$libro["lpid"]."').remove();

                              })
                            </script>";
                                              
                            $i++;
                          }

                          $total_c=array_sum($total_cantidad);
                        ?>
                                        
                        </tr>
                        <td class="center"></td>
                        <td class="center"></td>
                        <td class="center"></td>
                        <td class="center"></td>
                        <td class="center"><b>Total:</b></td>
                        <td class="center"><b><?php echo $total_c; ?></b></td>
                      </tbody>
                    </table>
                  </div>

                  <?php for ($i=1; $i < 100; $i++) { ?>

                      <div id="agg_l<?php echo $i;?>" class="d-none">
                        <h4>Libro #<?php echo $i;?>:</h4>
                        <div class="row">
                          <div class="form-group col-sm-4">
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
                          <div class="form-group col-sm-4">
                            <label id="l_libro<?php echo $i;?>" for="libro<?php echo $i;?>" class="control-label">Libro:<small style="color:red;"> *</small></label>
                        
                                <select name="libro" id="libro<?php echo $i;?>" class="form-control select2"></select>
                          </div>

                          <div class="form-group col-sm-4">
                            <label id="l_cantidad<?php echo $i;?>" for="cantidad<?php echo $i;?>" class="control-label">Cantidad<small style="color:red;"> *</small></label>
                            <input type="number" class="form-control" name="cantidad" id="cantidad<?php echo $i;?>">
                          </div>
                        </div>
              
              
                        <input type="hidden" name="libro_e[]" id="libro_e<?php echo $i;?>">
                      </div>

                  <?php } ?>

                  <a id="agregar_libro" style="cursor: pointer;">Agregar libro +</a><br>

                  <input type="hidden" name="pedido" value="<?php echo $_GET["id_devol"] ?>">
                  <input type="hidden" name="codigo" value="<?php echo $pedido["codigo"] ?>">
                  <input type="hidden" name="tipo" value="<?php echo $_GET["tipo"] ?>">

              <center>
                 <label for="observaciones">Observaciones:</label><br>
                 <textarea name="observaciones" id="observaciones" cols="80" rows="8" class="form-control"><?php echo $pedido["observaciones"]; ?></textarea><br><br>
                
                

                 <div id="entregado" class="pull-left"></div>
                 <div id="recibido" class="pull-right"></div>
                
                 
                 <?php 
                  if ($pedido["eid"]==1 && $n_op["estado"]!=2) {
                     if ($_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==2) {
                      echo '<h3>'.$pedido["estado"].'</h3><br>';
                      echo'<button class="btn btn-danger d-print-none" id="rechazar" type="button">Anular</button> <br><br>';
                      echo '<button class="btn btn-success d-print-none" id="aprobar" type="button">Recibir</button> <br><br>';
                    }
                   }elseif ($pedido["eid"]==2 && $n_op["estado"]!=2) {
                    if ($_SESSION["tipo"] ==1 || $_SESSION["tipo"] ==2) {
                      echo '<h3>'.$pedido["estado"].'</h3><br>';
                      echo'<button class="btn btn-danger d-print-none" id="rechazar" type="button">Anular</button> <br><br>';
                      echo '<button class="btn btn-success d-print-none" id="proceso" type="button">En proceso</button> <br><br>';
                    }
                   }elseif ($pedido["eid"]==4 && $n_op["estado"]!=2) {
                    echo '<h3>'.$pedido["estado"].'</h3><br>';
                   }elseif ($pedido["eid"]==3 && $n_op["estado"]!=2) {
                    echo '<h3>'.$pedido["estado"].'</h3><br>';
                   }

                   elseif ($n_op["estado"]==2) {
                    echo '<h3>Atendida</h3>';

                   }


                  
                 ?>

                
                
                <?php
                  if ($_SESSION["tipo"]==1 || $_SESSION["tipo"] ==2) {
                        if ($op ==0) {
                          if ($_GET["tipo"] =="1") {
                            echo '<a href="solicitar_op.php?id_devol_c='.$_GET["id_devol"].'" target="_blank" class="btn btn-warning d-print-none">Solicitar OP</a>';
                          }else{
                            echo '<a href="solicitar_op.php?id_devol_p='.$_GET["id_devol"].'" target="_blank" class="btn btn-warning d-print-none">Solicitar OP</a>';
                          }
                          
                        }
                    }

                  

                ?>
                 <button type="button" id="imprimir" class="btn btn-info d-print-none">Imprimir</button>

                  <?php if ($_SESSION["tipo"] ==1 || $_SESSION["id"]==22) { ?>
                               <button type="button" class="btn btn-primary d-print-none" id="modificar">Modificar</button></center>
                            <?php } ?>
                          </center>
                        </form>

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

      $("#rechazar").click(function(){

        if (confirm("¿Seguro desea anular?")) {
          window.location="php/accion_devol.php?rechazar=<?php echo $_GET["id_devol"] ?>&tipo=<?php echo $_GET["tipo"] ?>";
        }
        
      });

      $("#aprobar").click(function(){

        if (confirm("¿Seguro desea recibir?")) {
          window.location="php/accion_devol.php?aprobar=<?php echo $_GET["id_devol"] ?>&tipo=<?php echo $_GET["tipo"] ?>";
        }
        
      });

      $("#proceso").click(function(){

        if (confirm("¿Seguro desea poner en proceso?")) {
          window.location="php/accion_devol.php?proceso=<?php echo $_GET["id_devol"] ?>&tipo=<?php echo $_GET["tipo"] ?>";
        }
        
      });

      $("#modificar").click(function(){
        $("#form_pedido").submit();
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

  <?php if($_SESSION["tipo"]==1 || $_SESSION["tipo"]==2)   { ?>

    window.addEventListener("beforeprint", function(event) {
      $("#impre").html("<h4>Fecha recibido bodega: <?php echo date("Y-m-d H:i") ?></h4>");

      $("#entregado").html("<h4>Entregado por: ___________________________  </h4>");

      $("#recibido").html("<h4>Recibido por: ___________________________</h4>");
            
        var dataString = 'feid='+"<?php echo date("Y-m-d H:i:s") ?>"+'/'+"<?php echo $_GET["id_devol"] ?>";
                
        $.ajax({

            url: "ajax/fecha_impre_devol.php",
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

  <?php } ?>


    </script>
    
  </body>
</html>
