<?php

  require_once("../php/aut.php");
  include("../conexion/bdd.php");
?>
<div class="pd-20">
                         

                     
                          <center><h3>Solicitud de Recursos</h3></center><br><br>

                          <?php

                            $sql = "SELECT SUM(r.legaliza) as total FROM solicitudes_recursos s JOIN recursos_solicitados r ON s.id=r.id_solicitud WHERE s.id_colegio='".$_GET['colegio']."' AND s.id_periodo='".$_GET['periodo']."' AND s.estado='4';";

                            $req = $bdd->prepare($sql);
                            $req->execute();
                            $total = $req->fetch();

                            echo "<h5>Recurso entregado a la fecha: $ ".number_format($total['total'],0,",", ".")."</h5><br>";

                          ?>
                          <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_atenciones" type="button">Agregar solicitud de atención</a><br><br>

                          <div class="modal fade bs-example-modal-lg" id="modal_atenciones" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">
                                  Solicitar atención
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                  ×
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="php/solicitud_recurso.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                  
                                  <div class="form-group col-sm-4">
                                    <label for="solicitante" class="control-label">Solicitante del colegio <small style="color:red;"> *</small></label>
                                    <select name="solicitante" id="solicitante" class="form-control custom-select2" required>
                                      <option value="">Seleccionar</option>
                                      <?php 
                                        $sql = "SELECT t.id, CONCAT(nombre, ' ', apellido) as trabajador, c.cargo FROM trabajadores_colegios t JOIN cargos c ON t.cargo=c.id WHERE t.telefono !='' AND id_colegio='{$_GET['colegio']}'";

                                        $req = $bdd->prepare($sql);
                                        $req->execute();
                                        $trabajadores = $req->fetchAll();

                                        foreach($trabajadores as $trabajador) {

                                          echo '<option value="'.$trabajador['id'].'">'.$trabajador['trabajador'].' ('.$trabajador['cargo'].')</option>';
                                        }
                                      ?>
                                    </select>
                                  </div>

                                </div>
                                <hr style="background-color: #4c00ff;">
                                <div class="row">
                                  <div class="col-sm-3">
                                    <h5>Áreas comprometidas</h5><br>
                                  </div>
                                  <div class="col-sm-3 col-sm-offset-2">
                                    <h5>Compradores activos</h5>
                                  </div>
                                </div>
                                <div class="otro_area">

                                  <div class="row">
                                    <div class="form-group col-sm-3">
                                      <label id="l_materia_at" for="materia_at" class="control-label">Materia:<small style="color:red;"> *</small></label>
                                      <select name="materia[]" id="materia_at" class="form-control">
                                        <option value="">Seleccionar</option>
                                          <?php 
                                            $sql = "SELECT id, materia FROM materias";

                                            $req = $bdd->prepare($sql);
                                            $req->execute();
                                            $materias_at = $req->fetchAll();

                                            foreach($materias_at as $materia_at) {

                                              echo '<option value="'.$materia_at['id'].'">'.$materia_at['materia'].'</option>';
                                            }
                                          ?>
                                      </select>
                                    </div>

                                    <div class="form-group col-sm-3">
                                      <label id="l_preescolar_at" for="preescolar_at" class="control-label">Preescolar</label>
                                      <input type="number" class="form-control" name="preescolar" id="preescolar_at" autocomplete="off">
                                    </div>
                                    <div class="form-group col-sm-3">
                                      <label id="l_primaria_at" for="primaria_at" class="control-label">Pimaria</label>
                                      <input type="number" class="form-control" name="primaria" id="primaria_at" autocomplete="off">
                                    </div>
                                    <div class="form-group col-sm-3">
                                      <label id="l_bachillerato_at" for="bachillerato_at" class="control-label">Bachillerato</label>
                                      <input type="number" class="form-control" name="bachillerato" id="bachillerato_at" autocomplete="off">
                                    </div>
                                  </div>

                                </div>
                                <input type="hidden" name="areas_r[]" id="areas_r">

                                <?php for ($i=1; $i < 10; $i++) { ?>
                                  <div id="agg_area<?php echo $i;?>" class="d-none">

                                    <div class="row">
                                      <div class="form-group col-sm-3">
                                        <label id="l_materia_at<?php echo $i;?>" for="materia_at<?php echo $i;?>" class="control-label">Materia:<small style="color:red;"> *</small></label>
                                        <select name="materia[]" id="materia_at<?php echo $i;?>" class="form-control">
                                          <option value="">Seleccionar</option>
                                            <?php 
                                                $sql = "SELECT id, materia FROM materias";

                                                $req = $bdd->prepare($sql);
                                                $req->execute();
                                                $materias_at = $req->fetchAll();

                                                foreach($materias_at as $materia_at) {

                                                    echo '<option value="'.$materia_at['id'].'">'.$materia_at['materia'].'</option>';
                                                }
                                              ?>
                                          </select>
                                        </div>

                                        <div class="form-group col-sm-3">
                                          <label id="l_preescolar_at<?php echo $i;?>" for="preescolar_at<?php echo $i;?>" class="control-label">Preescolar</label>
                                          <input type="number" class="form-control" name="preescolar" id="preescolar_at<?php echo $i;?>" autocomplete="off">
                                        </div>
                                        <div class="form-group col-sm-3">
                                          <label id="l_primaria_at<?php echo $i;?>" for="primaria_at<?php echo $i;?>" class="control-label">Pimaria</label>
                                          <input type="number" class="form-control" name="primaria_at" id="primaria_at<?php echo $i;?>" autocomplete="off">
                                        </div>
                                        <div class="form-group col-sm-3">
                                          <label id="l_bachillerato_at<?php echo $i;?>" for="bachillerato_at<?php echo $i;?>" class="control-label">Bachillerato</label>
                                          <input type="number" class="form-control" name="bachillerato" id="bachillerato_at<?php echo $i;?>" autocomplete="off">
                                        </div>
                                    </div>
                                  </div>
                                  <input type="hidden" name="areas_r[]" id="areas_r<?php echo $i;?>">
                                <?php } ?>
                                
                                <a id="agregar_area" style="cursor: pointer;">Agregar area +</a><br><br>

                                <hr>

                                <div class="row">
                                  <div class="form-group col-sm-4">
                                    <label id="l_recurso_at" for="recurso_at" class="control-label">Recurso solicitado <small style="color:red;"> *</small></label>
                                    <input type="text" class="form-control" name="recurso" id="recurso_at" autocomplete="off">
                                  </div>

                                  <div class="form-group col-sm-2">
                                    <label id="l_tipo_at" for="tipo_at" class="control-label">Tipo<small style="color:red;"> *</small></label>
                                    <select name="tipo_at[]" id="tipo_at" class="form-control" required>
                                      <option value="">Seleccionar</option>
                                        <?php 
                                          $sql = "SELECT id, tipo FROM tipos_recursos WHERE categoria=1 OR categoria=3";

                                          $req = $bdd->prepare($sql);
                                          $req->execute();
                                          $tipos = $req->fetchAll();

                                          foreach($tipos as $tipo) {
                                                 
                                            echo '<option value="'.$tipo['id'].'">'.$tipo['tipo'].'</option>';
                                          }
                                        ?>
                                    </select>
                                  </div>

                                  <div class="form-group col-sm-3">
                                    <label id="l_cate" for="categoria" class="control-label">Categoría<small style="color:red;"> *</small></label>
                                    <select name="categoria[]" id="categoria" class="form-control" required>
                                      <option value="">Seleccionar</option>
                                        <?php 
                                          $sql = "SELECT id, categoria FROM categoria_recursos";

                                          $req = $bdd->prepare($sql);
                                          $req->execute();
                                          $cates = $req->fetchAll();

                                          foreach($cates as $cate) {
                                                 
                                            echo '<option value="'.$cate['id'].'">'.$cate['categoria'].'</option>';
                                          }
                                        ?>
                                      </select>
                                  </div>

                                  <div class="form-group col-sm-3">
                                    <label id="l_presupuesto_at" for="presupuesto_at" class="control-label">Presupuesto <small style="color:red;"> *</small></label>
                                    <input type="number" class="form-control" name="primaria" id="presupuesto_at" autocomplete="off">
                                  </div>
                                         
                                  </div>

                                  <input type="hidden" name="recursos[]" id="recursos">

                                  <?php for ($i=1; $i < 10; $i++) { ?>

                                    <div id="agg_recurso<?php echo $i;?>" class="d-none">
                                      <div class="row">
                                        <div class="form-group col-sm-4">
                                          <label id="l_recurso_at<?php echo $i;?>" for="recurso_at<?php echo $i;?>" class="control-label">Recurso solicitado <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" name="recurso" id="recurso_at<?php echo $i;?>" autocomplete="off">
                                        </div>

                                        <div class="form-group col-sm-2">
                                          <label id="l_tipo_at<?php echo $i;?>" for="tipo_at<?php echo $i;?>" class="control-label">Tipo <small style="color:red;"> *</small></label>
                                          <select name="materia[]" id="tipo_at<?php echo $i;?>" class="form-control">
                                            <option value="">Seleccionar</option>
                                            <?php 
                                              $sql = "SELECT id, tipo FROM tipos_recursos WHERE categoria=1 OR categoria=3";
                                              $req = $bdd->prepare($sql);
                                              $req->execute();
                                              $tipos = $req->fetchAll();
                                              foreach($tipos as $tipo) {
                                                echo '<option value="'.$tipo['id'].'">'.$tipo['tipo'].'</option>';
                                              }
                                            ?>
                                          </select>
                                        </div>

                                        <div class="form-group col-sm-3">
                                          <label id="l_cate<?php echo $i;?>" for="categoria<?php echo $i;?>" class="control-label">Categoría<small style="color:red;"> *</small></label>
                                          <select name="categoria[]" id="categoria<?php echo $i;?>" class="form-control">
                                            <option value="">Seleccionar</option>
                                            <?php 
                                              $sql = "SELECT id, categoria FROM categoria_recursos";

                                                $req = $bdd->prepare($sql);
                                                $req->execute();
                                                $cates = $req->fetchAll();
                                                foreach($cates as $cate) {
                                                       
                                                  echo '<option value="'.$cate['id'].'">'.$cate['categoria'].'</option>';
                                                }
                                                  ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-sm-3">
                                          <label id="l_presupuesto_at<?php echo $i;?>" for="presupuesto_at<?php echo $i;?>" class="control-label">Presupuesto<small style="color:red;"> *</small></label>
                                          <input type="number" class="form-control" name="primaria" id="presupuesto_at<?php echo $i;?>" autocomplete="off">
                                        </div>
                                           
                                      </div>

                                      <input type="hidden" name="recursos[]" id="recursos<?php echo $i;?>">

                                    </div>

                                  <?php } ?>

                                  <a id="agregar_recurso" style="cursor: pointer;">Agregar recurso +</a><br><br>

                                  <div class="row">
                                    <div class="form-group col-sm-3">
                                      <label for="fecha_entrega" class="control-label">Fecha de entrega <small style="color:red;"> *</small></label>
                                      <input type="text" class="form-control date-picker" name="fecha_entrega" id="fecha_entrega" data-date-format="yyyy-mm-dd" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-sm-3">
                                      <label for="reintegro" class="control-label">Reintegro</label>
                                      <input type="text" class="form-control" name="reintegro" id="reintegro" autocomplete="off">
                                    </div>
                                  </div>             

                                  <input type="hidden" name="id_colegio" value='<?php echo $_GET['colegio'] ?>'>
                                  <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                  <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">       
                              </div>
                              <div class="modal-footer">
                                <?php if($_SESSION["tipo"] !=2 && $_SESSION["tipo"] != 4) { ?>
                                  <br><center><button class="btn btn-primary">Solicitar</button></center><br>
                                <?php } ?>
                              </form>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="table-responsive">
                          <table class="table table-bordered">

                            <thead>
                              <th>#</th>
                              <th>Fecha</th>
                              <th>Solicitante (Cargo)</th>
                              <th>Fecha de entrega</th>
                              <th>Valor de la solicitud</th>
                              <th>Estado</th>
                            </thead>
                            <tbody>
                            <?php
                              $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, c.cargo, s.fecha_entrega, s.conse FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado JOIN trabajadores_colegios t ON t.id=s.solicitante JOIN cargos c ON c.id=t.cargo WHERE s.id_colegio='".$_GET['colegio']."' AND s.id_periodo='".$_GET['periodo']."' ORDER BY s.id DESC";

                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $solicitudes = $req->fetchAll();

                              foreach ($solicitudes as $solicitud) {

                                $sql = "SELECT SUM(presupuesto) as sum_solici FROM recursos_solicitados WHERE id_solicitud='".$solicitud["id"]."'";

                                $req = $bdd->prepare($sql);
                                $req->execute();
                                $total = $req->fetch();

                                echo "<tr>";
                                  echo "<td><a href='vista_solicitud.php?id=".$solicitud["id"]."' class='vista_soli'>".$solicitud["id"]."</a></td>";
                                  echo "<td>".$solicitud["fecha"]."</td>";
                                  echo "<td>".$solicitud["solicitante"]." (".$solicitud["cargo"].")</td>";
                                  echo "<td>".$solicitud["fecha_entrega"]."</td>";
                                  echo "<td>$ ".number_format($total["sum_solici"],0,",", ".")."</td>";
                                  echo "<td>".$solicitud["estado"]."</td>";
                                echo "</tr>";
                              }


                             ?>
                                <tr>
                                        
                                </tr>
                              </tbody>
                          </table>
                        </div>


                      </div>

                      <script>
                        
                        //atencion a clientes

      $('#materia_at').change(function(){
        var materia =$('#materia_at').val();
        var preescolar=$('#preescolar_at').val();
        var primaria=$('#primaria_at').val();
        var bachillerato=$('#bachillerato_at').val();
        $('#areas_r').val(materia+'/'+preescolar+'/'+primaria+'/'+bachillerato);

      })

      $('#preescolar_at, #primaria_at, #bachillerato_at').keyup(function(){
        var materia =$('#materia_at').val();
        var preescolar=$('#preescolar_at').val();
        var primaria=$('#primaria_at').val();
        var bachillerato=$('#bachillerato_at').val();
        $('#areas_r').val(materia+'/'+preescolar+'/'+primaria+'/'+bachillerato);

      })

      var m = 1;
    
      $("#agregar_area").click(function(){
        if (m>8) {
          $("#agregar_area").addClass("d-none");
        }
    
        $("#agg_area"+m).removeClass("d-none")

        m++;

        <?php for ($i=1; $i < 10; $i++) { ?>

          $('#materia_at<?php echo $i; ?>').change(function(){
            var materia =$('#materia_at<?php echo $i; ?>').val();
            var preescolar=$('#preescolar_at<?php echo $i; ?>').val();
            var primaria=$('#primaria_at<?php echo $i; ?>').val();
            var bachillerato=$('#bachillerato_at<?php echo $i; ?>').val();
            $('#areas_r<?php echo $i; ?>').val(materia+'/'+preescolar+'/'+primaria+'/'+bachillerato);

          })

          $('#preescolar_at<?php echo $i; ?>, #primaria_at<?php echo $i; ?>, #bachillerato_at<?php echo $i; ?>').keyup(function(){
            var materia =$('#materia_at<?php echo $i; ?>').val();
            var preescolar=$('#preescolar_at<?php echo $i; ?>').val();
            var primaria=$('#primaria_at<?php echo $i; ?>').val();
            var bachillerato=$('#bachillerato_at<?php echo $i; ?>').val();
            $('#areas_r<?php echo $i; ?>').val(materia+'/'+preescolar+'/'+primaria+'/'+bachillerato);

          })



        <?php } ?>

      
      })

      $('#recurso_at, #presupuesto_at').keyup(function(){
        var recurso =$('#recurso_at').val();
        var tipo=$('#tipo_at').val();
        var categoria=$('#categoria').val();
        var presupuesto=$('#presupuesto_at').val();
        $('#recursos').val(recurso+'/'+tipo+'/'+categoria+'/'+presupuesto);

      })

      $('#tipo_at').change(function(){
        var recurso =$('#recurso_at').val();
        var tipo=$('#tipo_at').val();
        var categoria=$('#categoria').val();
        var presupuesto=$('#presupuesto_at').val();
        $('#recursos').val(recurso+'/'+tipo+'/'+categoria+'/'+presupuesto);

      })


      $("#agregar_recurso").click(function(){
        if (m>8) {
          $("#agregar_recurso").addClass("d-none");
        }
      
        $("#agg_recurso"+m).removeClass("d-none")

        m++;

        <?php for ($i=1; $i < 10; $i++) { ?>

          $('#recurso_at<?php echo $i; ?>, #presupuesto_at<?php echo $i; ?>').keyup(function(){
            var recurso =$('#recurso_at<?php echo $i; ?>').val();
            var tipo=$('#tipo_at<?php echo $i; ?>').val();
            var categoria=$('#categoria<?php echo $i; ?>').val();
            var presupuesto=$('#presupuesto_at<?php echo $i; ?>').val();
            $('#recursos<?php echo $i; ?>').val(recurso+'/'+tipo+'/'+categoria+'/'+presupuesto);

          })

          $('#tipo_at<?php echo $i; ?>').change(function(){
            var recurso =$('#recurso_at<?php echo $i; ?>').val();
            var tipo=$('#tipo_at<?php echo $i; ?>').val();
            var categoria=$('#categoria<?php echo $i; ?>').val();
            var presupuesto=$('#presupuesto_at<?php echo $i; ?>').val();
            $('#recursos<?php echo $i; ?>').val(recurso+'/'+tipo+'/'+categoria+'/'+presupuesto);

          })


        <?php } ?>

        
      })

       $(".vista_soli" ).click(function( e ) {

        e.preventDefault();
        var url= $(this).attr("href")
        var caracteristicas = "height=700,width=1300,scrollTo,resizable=1,scrollbars=1,location=0";
        nueva=window.open(url, "Popup", caracteristicas);

      })

       
                      </script>