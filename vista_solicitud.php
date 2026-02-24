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
    <div class="main-container">
      <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
          <div class="page-header">
            <div class="row">
              <div class="col-md-6 col-sm-12">
                <div class="title">
                  <h4>Atenciones a clientes</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Atenciones a clientes
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Ver solicitud
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            <?php 
                include("conexion/bdd.php");

                $sql = "SELECT e.estado, s.estado as idestado ,s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, ca.cargo, s.fecha_entrega,s.id_periodo, s.archivo ,s.contab, s.conse, c.colegio, c.codigo FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado JOIN colegios c ON c.id=s.id_colegio LEFT JOIN trabajadores_colegios t ON s.solicitante=t.id LEFT JOIN cargos ca ON ca.id=t.cargo WHERE s.id='".$_GET["id"]."'";

                $req = $bdd->prepare($sql);
                $req->execute();
                $solicitud = $req->fetch();

                if ($solicitud["id"] < 221) {
                  echo "<h3>Solicitud # ".$solicitud["id"]."</h3>";
                }else{
                  echo "<h3>Solicitud # ".$solicitud["conse"]."</h3>";
                }

                echo"<table class='table table-bordered'>
                    <tr><td>Colegio: ".$solicitud["colegio"]."</td></tr>
                    <tr><td>Fecha: ".$solicitud["fecha"]."</td>
                        <td>Solicitante: ".$solicitud["solicitante"]." (".$solicitud["cargo"].")</td>
                    </tr>
                    <tr>
                      <td>Fecha de entrega: ".$solicitud["fecha_entrega"]."</td>
                      <td>Estado: ".$solicitud["estado"]."</td>
                    </tr>
                </table>";


                echo "<table class='table table-bordered'>
                    <thead>
                      <th></th>
                      <th colspan='3'><center>Compradores activos</center></th>
                    </thead>
                    <thead>
                      <th>Areas comprometidas</th>
                        <th>Preescolar</th>
                        <th>Primaria</th>
                        <th>Bachillerato</th>
                    </thead>
                    <tbody>";

                      $sql = "SELECT m.materia, a.preescolar, a.primaria, a.bachillerato FROM areas_recursos a JOIN materias m ON a.materia=m.id WHERE a.id_solicitud='".$solicitud["id"]."'";

                      $req = $bdd->prepare($sql);
                      $req->execute();
                      $areas = $req->fetchAll();

                      foreach ($areas as $area) {
                        echo"<tr>
                          <td>".$area["materia"]."</td>
                          <td>".$area["preescolar"]."</td>
                          <td>".$area["primaria"]."</td>
                          <td>".$area["bachillerato"]."</td>
                        </tr>";
                      }

                    echo"</tbody>
                  </table>";

                  echo "<table class='table table-bordered table-striped'>
                      <thead>
                        <th>Recurso</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Presupuesto</th>
                        <th>Tipo de recurso entregado</th>
                        <th>Valor de recurso entregado</th>
                        <th>Fecha de entregado</th>
                        <th>Legalización</th>
                      </thead>
                      <tbody>
                        <script src='vendors/scripts/jquery-2.1.4.min.js'></script>";
                          if ($solicitud["idestado"] == 1) {
                            echo"<form action='php/modificar_solicitud.php?tipo=1' method='POST' id='form_a'>";
                          }else{
                            echo"<form action='php/modificar_solicitud.php?tipo=2' method='POST' id='form_a'>";
                          }

                          $sql = "SELECT t.tipo, r.id,r.recurso, r.presupuesto, r.tipo_e, r.valor_e, r.fecha_e, r.legaliza, c.categoria FROM recursos_solicitados r JOIN tipos_recursos t ON t.id=r.tipo JOIN categoria_recursos c ON c.id=r.categoria WHERE r.id_solicitud='".$solicitud["id"]."'";

                          $req = $bdd->prepare($sql);
                          $req->execute();
                          $recursos = $req->fetchAll();

                          foreach ($recursos as $recurso) {

                            echo "<tr>
                              <td>".$recurso["recurso"]."</td>
                              <td>".$recurso["tipo"]."</td>
                              <td>".$recurso["categoria"]."</td>";

                              if ($solicitud["idestado"] == 1) {

                                  echo"<td><input type='text' id='presup".$recurso["id"]."' value='".$recurso["presupuesto"]."'></td>";

                                  echo "<input type='hidden' id='i_presup".$recurso["id"]."' name='i_presup[]'>";

                                    echo "<script>
                                      $('#i_presup".$recurso["id"]."').val('".$recurso["presupuesto"]."')
                                      $('#presup".$recurso["id"]."').keyup(function(){

                                        var v_presup=parseInt($('#presup".$recurso["id"]."').val());

                                        $('#i_presup".$recurso["id"]."').val(".$recurso["id"]."+'/'+v_presup);


                                      })
                                    </script>";

                              }else{

                                echo"<td>$ ".number_format($recurso["presupuesto"],0,",", ".")."</td>";

                              }
                                        
                              if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 7 || $_SESSION['tipo'] == 9) {

                                if ($solicitud["idestado"] == 2) {
                                  echo'<td>
                                    <select name="" id="tipo_e'.$recurso["id"].'">
                                      <option value="">Seleccionar</option>';
                                      $sql = "SELECT id, tipo FROM tipos_recursos WHERE categoria=2 OR categoria=3";

                                      $req = $bdd->prepare($sql);
                                      $req->execute();
                                      $tipos_e = $req->fetchAll();

                                      foreach($tipos_e as $tipo_e) {

                                        if($recurso["tipo_e"]==$tipo_e["id"]){
                                          echo'<option value="'.$tipo_e["id"].'" SELECTED>'.$tipo_e["tipo"].'</option>';
                                        }else{
                                          echo'<option value="'.$tipo_e["id"].'">'.$tipo_e["tipo"].'</option>';
                                        }
                                                           
                                      }
                                                    
                                    echo'</select>

                                  </td>';
                                  echo"<td><input type='text' id='valor_e".$recurso["id"]."' value='".$recurso["valor_e"]."'></td>";
                                  echo"<td><input type='text' id='fecha_e".$recurso["id"]."' class='date-picker' data-date-format='yyyy-mm-dd' autocomplete='off' value='".$recurso["fecha_e"]."'></td>";
                                            

                                }elseif ($solicitud["idestado"] == 4) {
                                            
                                  $sql = "SELECT tipo FROM tipos_recursos WHERE id='".$recurso["tipo_e"]."'";

                                  $req = $bdd->prepare($sql);
                                  $req->execute();
                                  $tipo_e = $req->fetch();

                                  echo "<td>".$tipo_e["tipo"]."</td>";
                                  echo "<td>$ ".number_format($recurso["valor_e"],0,",", ".")."</td>";
                                  echo "<td>".$recurso["fecha_e"]."</td>";
                                  echo"<td><input type='text' id='legaliza".$recurso["id"]."' value='".$recurso["legaliza"]."'></td>";
                                }

                                else{

                                  $sql = "SELECT tipo FROM tipos_recursos WHERE id='".$recurso["tipo_e"]."'";

                                  $req = $bdd->prepare($sql);
                                  $req->execute();
                                  $tipo_e = $req->fetch();

                                  echo "<td>".$tipo_e["tipo"]."</td>";
                                  echo "<td>$ ".number_format($recurso["valor_e"],0,",", ".")."</td>";
                                  echo "<td>".$recurso["fecha_e"]."</td>";
                                  echo "<td>$ ".number_format($recurso["legaliza"],0,",", ".")."</td>";
                                }

                              }else{

                                $sql = "SELECT tipo FROM tipos_recursos WHERE id='".$recurso["tipo_e"]."'";

                                $req = $bdd->prepare($sql);
                                $req->execute();
                                $tipo_e = $req->fetch();

                                echo "<td>".$tipo_e["tipo"]."</td>";
                                echo "<td>$ ".number_format($recurso["valor_e"],0,",", ".")."</td>";
                                echo "<td>".$recurso["fecha_e"]."</td>";
                                echo "<td>$ ".number_format($recurso["legaliza"],0,",", ".")."</td>";

                              }

                              echo"</tr>";

                                $t_presup[]=$recurso["presupuesto"];

                                $t_legaliza[]=$recurso["legaliza"];

                                echo "<input type='hidden' id='i_legaliza".$recurso["id"]."' name='i_legaliza[]'>";

                                echo "<input type='hidden' id='i_entrega".$recurso["id"]."' name='i_entrega[]'>";

                                echo "<script>

                                  $('#tipo_e".$recurso["id"]."').change(function(){

                                    var v_tipo_e=$('#tipo_e".$recurso["id"]."').val();
                                    var v_valor_e=parseInt($('#valor_e".$recurso["id"]."').val());
                                    var v_fecha_e=$('#fecha_e".$recurso["id"]."').val();

                                    $('#i_entrega".$recurso["id"]."').val(".$recurso["id"]."+'|'+v_tipo_e+'|'+v_valor_e+'|'+v_fecha_e);


                                  })

                                  $('#valor_e".$recurso["id"]."').keyup(function(){

                                    var v_tipo_e=$('#tipo_e".$recurso["id"]."').val();
                                    var v_valor_e=parseInt($('#valor_e".$recurso["id"]."').val());
                                    var v_fecha_e=$('#fecha_e".$recurso["id"]."').val();

                                    $('#i_entrega".$recurso["id"]."').val(".$recurso["id"]."+'|'+v_tipo_e+'|'+v_valor_e+'|'+v_fecha_e);


                                  })

                                  $('#fecha_e".$recurso["id"]."').blur(function(){

                                    var v_tipo_e=$('#tipo_e".$recurso["id"]."').val();
                                    var v_valor_e=parseInt($('#valor_e".$recurso["id"]."').val());
                                    var v_fecha_e=$('#fecha_e".$recurso["id"]."').val();

                                    $('#i_entrega".$recurso["id"]."').val(".$recurso["id"]."+'|'+v_tipo_e+'|'+v_valor_e+'|'+v_fecha_e);

                                  })

                                  $('#legaliza".$recurso["id"]."').keyup(function(){

                                    var v_legaliza=parseInt($('#legaliza".$recurso["id"]."').val());

                                    $('#i_legaliza".$recurso["id"]."').val(".$recurso["id"]."+'|'+v_legaliza);


                                  })
                                </script>";

                              }
                              echo"<tr>
                                <td><b>Total:</b></td>
                                <td></td>
                                <td></td>
                                <td>$ ".number_format($t_presup=array_sum($t_presup),0,",", ".")."</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>$ ".number_format($t_legaliza=array_sum($t_legaliza),0,",", ".")."</td>
                              <tr>

                            </tbody>
                          </table>";
                          echo "<input type='hidden' name='solicitud' value='".$solicitud["id"]."'>";

                          if ($solicitud["idestado"] == 2) {

                            if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 7 || $_SESSION['tipo'] == 9) {

                              echo"<center><button class='btn btn-primary' id='entregar'>Entregar</button></center><br>";
                              
                                
                            }

                          }

                          if ($solicitud["idestado"] == 2 || $solicitud["idestado"] == 4) {
                            if ($_SESSION['tipo'] == 1 || $_SESSION['tipo'] == 7 || $_SESSION['tipo'] == 9) {
                              echo"<center><button class='btn btn-success'>Legalizar</button></center>";
                            }
                          }

                          if ($solicitud["idestado"] == 1) {

                            echo"<center><button class='btn btn-primary'>Modificar</button></center>";
                          }

                          if ($_SESSION['tipo']==1 || $_SESSION['tipo']==9) {

                            if ($solicitud["contab"] == 0 && $solicitud["idestado"] == 4) {
                              echo"<center><br><a class='btn btn-success' href='php/accion_solicitudes.php?solicitud=".$solicitud["id"]."&contab=1&cod_colegio=".$solicitud["codigo"]."&periodo=".$solicitud["id_periodo"]."' >Contabilizar</a></center>";
                            }
                              
                          }


                          

                         

                          if ($_SESSION['tipo']==1) {

                            if ($solicitud["idestado"] == 1) {
                              echo "<br><center><a class='btn btn-success' href='php/accion_solicitudes.php?solicitud=".$solicitud["id"]."&aprobar=1&cod_colegio=".$solicitud["codigo"]."&periodo=".$solicitud["id_periodo"]."'>Aprobar</a> <a class='btn btn-danger' href='php/accion_solicitudes.php?solicitud=".$solicitud["id"]."&rechazar=1&cod_colegio=".$solicitud["codigo"]."&periodo=".$solicitud["id_periodo"]."'>Rechazar</a></center>";
                              }
                          }
                            
                        echo "</form>";

                        if ($solicitud["idestado"] == 4 && $solicitud["archivo"]=="") {

                            echo '<form action="php/archivo_solicitud.php" method="POST" enctype="multipart/form-data"><div class="form-group">
                              <label class="control-label no-padding-right" for="archivo"> Archivo de legalización</label>

                              <input type="file" name="archivo" id="archivo" placeholder="Adjunto" class="form-control" required/>
                    
                            </div>
                             <input type="hidden" name="solicitud" value="'.$solicitud["id"].'">
                              <button class="btn btn-warning">Subir archivo</button>
                            </form><br>';

                          }else{
                            $n_archivo = explode('_', $solicitud["archivo"]);
                            echo "Archivo de legalización: <a href='adjuntos_atenc/".$solicitud["archivo"]."' download='".$n_archivo[1]."'>".$n_archivo[1]."</a>"; 
                          }

                          if ($solicitud["contab"] == 1) {

                            echo "<br><center><p class='bg-success' style='font-size: 20px; color: #FFF'>Contabilizada</p></center>";
                          }
                                

                      ?>

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
      
    	$("#entregar").click(function(){

        $("#form_a").attr("action","php/entregar_solicitud.php")

      })
    </script>
    
  </body>
</html>
