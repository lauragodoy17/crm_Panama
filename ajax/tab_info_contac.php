<?php

	require_once("../php/aut.php");
	include("../conexion/bdd.php");
?>
<div class="pd-20">

                        <br><h5>Nivel 1. Administrativo</h5>
                        <br>
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_adm" type="button">Agregar contacto</a><br><br>
                        <?php

                            $sql = "SELECT * FROM trabajadores_colegios WHERE id_colegio='".$_GET["colegio"]."' AND cargo !=6";

                            $req = $bdd->prepare($sql);
                            $req->execute();

                            $adms = $req->fetchAll();

                            foreach ($adms as $adm) { ?>

                              <form action="php/modificar_adm.php" method="POST"><div class="row">
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label>Nombres <small style="color:red;"> *</small></label>
                                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_adm" value="<?php echo $adm["nombre"] ?>" required/>
                                  </div>
                                </div>
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label >Apellidos <small style="color:red;"> *</small></label>
                                      <input type="text" class="form-control" placeholder="Apellido" name="apellido_adm" value="<?php echo $adm["apellido"] ?>" required/>
                                    </div>
                                  </div>
                               

                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label>Correo <small style="color:red;"> *</small></label>
                                    <input type="email" class="form-control" placeholder="Correo" name="correo_adm" value="<?php echo $adm["email"] ?>" required/>
                                  </div>
                                </div>

                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label >Teléfono <small style="color:red;"> *</small></label>
                                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_adm" value="<?php echo $adm["telefono"] ?>" required/>
                                  </div>
                                </div>
                                <div class="col-ms-2">
                                  <label>Cargo<small style="color:red;"> *</small></label>
                                  <select class="custom-select" name="cargo_adm"  required>
                                    <option value="">Seleccione</option>
                                      <?php

                                        $sql = "SELECT * FROM cargos WHERE id !=5";

                                        $req = $bdd->prepare($sql);
                                        $req->execute();

                                        $cargos = $req->fetchAll();

                                        foreach ($cargos as $cargo) {

                                           if ($adm["cargo"]==$cargo["id"]) {
                                              echo '<option value="'.$cargo["id"].'" SELECTED>'.$cargo["cargo"].'</option>';
                                           }else{
                                              echo '<option value="'.$cargo["id"].'">'.$cargo["cargo"].'</option>';
                                           }
                                                  
                                            

                                        }
                                                

                                      ?>

                                  </select>
                                </div>
                                <div class="col-sm-2">
                                  <button class="btn btn-success">Modificar</button>
                                </div>
                                <input type="hidden" name="id_adm" value="<?php echo $adm["id"] ?>">
                                <input type="hidden" name="id_colegio" value='<?php echo $_GET["colegio"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
                              </div></form>
                              <?php } ?>
                        

                        <div class="modal fade bs-example-modal-lg" id="modal_adm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">
                                  Agregar trabajador administrativo
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                  ×
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="php/guardar_adm.php" method="POST">
                                <div class="otro_adm">

                                  <diw class="row">
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label id="l_nombre_adm">Nombres <small style="color:red;"> *</small></label>
                                        <input type="text" class="form-control" placeholder="Nombre" name="nombre_adm" id="nombre_adm" required/>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label id="l_apellido_adm">Apellidos <small style="color:red;"> *</small></label>
                                        <input type="text" class="form-control" placeholder="Apellido" name="apellido_adm" id="apellido_adm" required/>
                                      </div>
                                    </div>
                                  </diw>

                                  <div class="row">

                                    <div class="col-sm-4">
                                      <div class="form-group">
                                        <label id="l_correo_adm">Correo <small style="color:red;"> *</small></label>
                                        <input type="email" class="form-control" placeholder="Correo" name="correo_adm" id="correo_adm" required/>
                                      </div>
                                    </div>

                                    <div class="col-sm-4">
                                      <div class="form-group">
                                        <label id="l_telefono_adm">Teléfono <small style="color:red;"> *</small></label>
                                        <input type="text" class="form-control" placeholder="Teléfono" name="telefono_adm" id="telefono_adm" required/>
                                      </div>
                                    </div>
                                    <div class="col-ms-4">
                                      <label id="l_cargo_adm">Cargo<small style="color:red;"> *</small></label>
                                      <select class="custom-select" name="cargo_adm" id="cargo_adm" required>
                                          <option value="">Seleccione</option>
                                          
                                            <?php

                                              $sql = "SELECT * FROM cargos WHERE id !=5";

                                              $req = $bdd->prepare($sql);
                                              $req->execute();

                                              $cargos = $req->fetchAll();

                                              foreach ($cargos as $cargo) {
                                                  
                                                echo '<option value="'.$cargo["id"].'">'.$cargo["cargo"].'</option>';

                                              }
                                                

                                            ?>

                                        </select>
                                    </div>

                                  </div>

                                </div>

                                <input type="hidden" name="adm[]" id="adm">

                                <?php for ($i=1; $i < 15; $i++) { ?>

                                  <div id="agg_adm<?php echo $i;?>" class="d-none">

                                    <hr style="background-color: #4c00ff;"><diw class="row">
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                          <label id="l_nombre_adm<?php echo $i;?>">Nombres <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" placeholder="Nombre" name="nombre_adm" id="nombre_adm<?php echo $i;?>"/>
                                        </div>
                                      </div>
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                          <label id="l_apellido_adm<?php echo $i;?>">Apellidos <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" placeholder="Apellido" name="apellido_adm" id="apellido_adm<?php echo $i;?>"/>
                                        </div>
                                      </div>
                                    </diw>

                                    <div class="row">

                                      <div class="col-sm-4">
                                        <div class="form-group">
                                          <label id="l_correo_adm<?php echo $i;?>">Correo <small style="color:red;"> *</small></label>
                                          <input type="email" class="form-control" placeholder="Correo" name="correo_adm" id="correo_adm<?php echo $i;?>"/>
                                        </div>
                                      </div>

                                      <div class="col-sm-4">
                                        <div class="form-group">
                                          <label id="l_telefono_adm<?php echo $i;?>">Teléfono <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" placeholder="Teléfono" name="telefono_adm" id="telefono_adm<?php echo $i;?>"/>
                                        </div>
                                      </div>
                                      <div class="col-ms-4">
                                        <label id="l_cargo_adm<?php echo $i;?>">Cargo<small style="color:red;"> *</small></label>
                                        <select class="custom-select" name="cargo_adm" id="cargo_adm<?php echo $i;?>">
                                            <option value="">Seleccione</option>
                                            
                                              <?php

                                                $sql = "SELECT * FROM cargos WHERE id !=5";

                                                $req = $bdd->prepare($sql);
                                                $req->execute();

                                                $cargos = $req->fetchAll();

                                                foreach ($cargos as $cargo) {
                                                    
                                                  echo '<option value="'.$cargo["id"].'">'.$cargo["cargo"].'</option>';

                                                }
                                                  

                                              ?>

                                          </select>
                                      </div>

                                    </div>

                                  </div>
                                  <input type="hidden" name="adm[]" id="adm<?php echo $i;?>">
                                <?php } ?>

                                <a id="agregar_adm" style="cursor: pointer;">Agregar otro trabajador +</a><br>
                                <input type="hidden" name="id_colegio" value='<?php echo $_GET["colegio"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
                              </div>
                              <div class="modal-footer">
                                <button  class="btn btn-primary">
                                  Guardar
                                </button>
                              </form>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Profesores -->

                        <br><h5>Nivel 2. Académico</h5>
                        <br>
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_profes" type="button">Agregar Profesor</a><br><br>
                        <?php

                            $sql = "SELECT * FROM trabajadores_colegios WHERE id_colegio='".$_GET["colegio"]."' AND cargo=6";

                            $req = $bdd->prepare($sql);
                            $req->execute();

                            $profes = $req->fetchAll();

                            foreach ($profes as $profe) { ?>

                              <form action="php/modificar_profe.php" method="POST"><div class="row">
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label>Nombres <small style="color:red;"> *</small></label>
                                    <input type="text" class="form-control" placeholder="Nombre" name="nombre_profe" value="<?php echo $profe["nombre"] ?>" required/>
                                  </div>
                                </div>
                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label >Apellidos <small style="color:red;"> *</small></label>
                                      <input type="text" class="form-control" placeholder="Apellido" name="apellido_profe" value="<?php echo $profe["apellido"] ?>" required/>
                                    </div>
                                  </div>
                               

                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label>Correo <small style="color:red;"> *</small></label>
                                    <input type="email" class="form-control" placeholder="Correo" name="correo_profe" value="<?php echo $profe["email"] ?>" required/>
                                  </div>
                                </div>

                                <div class="col-sm-2">
                                  <div class="form-group">
                                    <label >Teléfono <small style="color:red;"> *</small></label>
                                    <input type="text" class="form-control" placeholder="Teléfono" name="telefono_profe" value="<?php echo $profe["telefono"] ?>" required/>
                                  </div>
                                </div>
                                <div class="col-ms-2">
                                  <label>Área<small style="color:red;"> *</small></label>
                                  <select class="custom-select" name="area_profe"  required>
                                    <option value="">Seleccione</option>
                                      <?php

                                        $sql = "SELECT * FROM materias WHERE id < 16";

                                        $req = $bdd->prepare($sql);
                                        $req->execute();

                                        $materias = $req->fetchAll();

                                        foreach ($materias as $materia) {

                                           if ($profe["area"]==$materia["id"]) {
                                              echo '<option value="'.$materia["id"].'" SELECTED>'.$materia["materia"].'</option>';
                                           }else{
                                              echo '<option value="'.$materia["id"].'">'.$materia["materia"].'</option>';
                                           }
                                                  
                                            

                                        }
                                                

                                      ?>

                                  </select>
                                </div>
                                <div class="col-sm-2">
                                  <button class="btn btn-success">Modificar</button>
                                </div>
                                <input type="hidden" name="id_profe" value="<?php echo $profe["id"] ?>">
                                <input type="hidden" name="id_colegio" value='<?php echo $_GET["colegio"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
                              </div></form>
                              <?php } ?>
                        

                        <div class="modal fade bs-example-modal-lg" id="modal_profes" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title" id="myLargeModalLabel">
                                  Agregar profesor
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                  ×
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="php/guardar_profe.php" method="POST">
                                <div class="otro_profe">

                                  <diw class="row">
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label id="l_nombre_profe">Nombres <small style="color:red;"> *</small></label>
                                        <input type="text" class="form-control" placeholder="Nombre" name="nombre_profe" id="nombre_profe" required/>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label id="l_apellido_profe">Apellidos <small style="color:red;"> *</small></label>
                                        <input type="text" class="form-control" placeholder="Apellido" name="apellido_profe" id="apellido_profe" required/>
                                      </div>
                                    </div>
                                  </diw>

                                  <div class="row">

                                    <div class="col-sm-4">
                                      <div class="form-group">
                                        <label id="l_correo_profe">Correo <small style="color:red;"> *</small></label>
                                        <input type="email" class="form-control" placeholder="Correo" name="correo_profe" id="correo_profe" required/>
                                      </div>
                                    </div>

                                    <div class="col-sm-4">
                                      <div class="form-group">
                                        <label id="l_telefono_profe">Teléfono <small style="color:red;"> *</small></label>
                                        <input type="text" class="form-control" placeholder="Teléfono" name="telefono_profe" id="telefono_profe" required/>
                                      </div>
                                    </div>
                                    <div class="col-ms-4">
                                      <label id="l_area_profe">Área<small style="color:red;"> *</small></label>
                                      <select class="custom-select2" name="area_profe" id="area_profe" required>
                                          <option value="">Seleccione</option>
                                          
                                            <?php

                                              $sql = "SELECT * FROM materias WHERE id < 16";

                                              $req = $bdd->prepare($sql);
                                              $req->execute();

                                              $materias = $req->fetchAll();

                                              foreach ($materias as $materia) {
                                                  
                                                echo '<option value="'.$materia["id"].'">'.$materia["materia"].'</option>';

                                              }
                                                

                                            ?>

                                        </select>
                                    </div>

                                  </div>

                                </div>

                                <input type="hidden" name="profe[]" id="profe">

                                <?php for ($i=1; $i < 15; $i++) { ?>

                                  <div id="agg_profe<?php echo $i;?>" class="d-none">

                                    <hr style="background-color: #4c00ff;"><diw class="row">
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                          <label id="l_nombre_profe<?php echo $i;?>">Nombres <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" placeholder="Nombre" name="nombre_profe" id="nombre_profe<?php echo $i;?>"/>
                                        </div>
                                      </div>
                                      <div class="col-sm-6">
                                        <div class="form-group">
                                          <label id="l_apellido_profe<?php echo $i;?>">Apellidos <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" placeholder="Apellido" name="apellido_profe" id="apellido_profe<?php echo $i;?>"/>
                                        </div>
                                      </div>
                                    </diw>

                                    <div class="row">

                                      <div class="col-sm-4">
                                        <div class="form-group">
                                          <label id="l_correo_profe<?php echo $i;?>">Correo <small style="color:red;"> *</small></label>
                                          <input type="email" class="form-control" placeholder="Correo" name="correo_profe" id="correo_profe<?php echo $i;?>"/>
                                        </div>
                                      </div>

                                      <div class="col-sm-4">
                                        <div class="form-group">
                                          <label id="l_telefono_profe<?php echo $i;?>">Teléfono <small style="color:red;"> *</small></label>
                                          <input type="text" class="form-control" placeholder="Teléfono" name="telefono_profe" id="telefono_profe<?php echo $i;?>"/>
                                        </div>
                                      </div>
                                      <div class="col-ms-4">
                                        <label id="l_area_profe<?php echo $i;?>">Área<small style="color:red;"> *</small></label>
                                        <select class="custom-select2" name="area_profe" id="area_profe<?php echo $i;?>">
                                            <option value="">Seleccione</option>
                                            
                                              <?php

                                                $sql = "SELECT * FROM materias WHERE id < 16";

                                                $req = $bdd->prepare($sql);
                                                $req->execute();

                                                $materias = $req->fetchAll();

                                                foreach ($materias as $materia) {
                                                    
                                                  echo '<option value="'.$materia["id"].'">'.$materia["materia"].'</option>';

                                                }
                                                  

                                              ?>

                                          </select>
                                      </div>

                                    </div>

                                  </div>
                                  <input type="hidden" name="profe[]" id="profe<?php echo $i;?>">
                                <?php } ?>

                                <a id="agregar_profe" style="cursor: pointer;">Agregar otro profesor +</a><br>
                                <input type="hidden" name="id_colegio" value='<?php echo $_GET["colegio"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
                              </div>
                              <div class="modal-footer">
                                <button  class="btn btn-primary">
                                  Guardar
                                </button>
                              </form>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                      <script>
                      	//guardar trabajador adm

      $('#nombre_adm, #apellido_adm, #correo_adm, #telefono_adm').keyup(function(){
        var nombre =$('#nombre_adm').val();
        var apellido=$('#apellido_adm').val();
        var correo=$('#correo_adm').val();
        var telefono=$('#telefono_adm').val();
        var cargo=$('#cargo_adm').val();

        $('#adm').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+cargo);

      })

      $('#cargo_adm').change(function(){
        var nombre =$('#nombre_adm').val();
        var apellido=$('#apellido_adm').val();
        var correo=$('#correo_adm').val();
        var telefono=$('#telefono_adm').val();
        var cargo=$('#cargo_adm').val();

        $('#adm').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+cargo);

      })

      //agregar mas trabajadores adm

      var m = 1;

      $("#agregar_adm").click(function(){

        if (m>13) {
          $("#agregar_adm").addClass("d-none");
        }

        $("#agg_adm"+m).removeClass("d-none");

        m++;

        <?php for ($i=1; $i < 15; $i++) { ?>

          $('#nombre_adm<?php echo $i; ?>, #apellido_adm<?php echo $i; ?>, #correo_adm<?php echo $i; ?>, #telefono_adm<?php echo $i; ?>').keyup(function(){
            var nombre =$('#nombre_adm<?php echo $i; ?>').val();
            var apellido=$('#apellido_adm<?php echo $i; ?>').val();
            var correo=$('#correo_adm<?php echo $i; ?>').val();
            var telefono=$('#telefono_adm<?php echo $i; ?>').val();
            var cargo=$('#cargo_adm<?php echo $i; ?>').val();

            $('#adm<?php echo $i; ?>').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+cargo);

          })

          $('#cargo_adm<?php echo $i; ?>').change(function(){
            var nombre =$('#nombre_adm<?php echo $i; ?>').val();
            var apellido=$('#apellido_adm<?php echo $i; ?>').val();
            var correo=$('#correo_adm').val();
            var telefono=$('#telefono_adm<?php echo $i; ?>').val();
            var cargo=$('#cargo_adm<?php echo $i; ?>').val();

            $('#adm<?php echo $i; ?>').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+cargo);

          })

        <?php } ?>

      })

      //guardar profesor

      $('#nombre_profe, #apellido_profe, #correo_profe, #telefono_profe').keyup(function(){
        var nombre =$('#nombre_profe').val();
        var apellido=$('#apellido_profe').val();
        var correo=$('#correo_profe').val();
        var telefono=$('#telefono_profe').val();
        var area=$('#area_profe').val();

        $('#profe').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+area);

      })

      $('#area_profe').change(function(){
        var nombre =$('#nombre_profe').val();
        var apellido=$('#apellido_profe').val();
        var correo=$('#correo_profe').val();
        var telefono=$('#telefono_profe').val();
        var area=$('#area_profe').val();

        $('#profe').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+area);

      })

      //agregar mas tprofesores

      var m = 1;

      $("#agregar_profe").click(function(){

        if (m>13) {
          $("#agregar_profe").addClass("d-none");
        }

        $("#agg_profe"+m).removeClass("d-none");

        m++;

        <?php for ($i=1; $i < 15; $i++) { ?>

          $('#nombre_profe<?php echo $i; ?>, #apellido_profe<?php echo $i; ?>, #correo_profe<?php echo $i; ?>, #telefono_profe<?php echo $i; ?>').keyup(function(){
            var nombre =$('#nombre_profe<?php echo $i; ?>').val();
            var apellido=$('#apellido_profe<?php echo $i; ?>').val();
            var correo=$('#correo_profe<?php echo $i; ?>').val();
            var telefono=$('#telefono_profe<?php echo $i; ?>').val();
            var area=$('#area_profe<?php echo $i; ?>').val();

            $('#profe<?php echo $i; ?>').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+area);

          })

          $('#area_profe<?php echo $i; ?>').change(function(){
            var nombre =$('#nombre_profe<?php echo $i; ?>').val();
            var apellido=$('#apellido_profe<?php echo $i; ?>').val();
            var correo=$('#correo_profe<?php echo $i; ?>').val();
            var telefono=$('#telefono_profe<?php echo $i; ?>').val();
            var area=$('#area_profe<?php echo $i; ?>').val();

            $('#profe<?php echo $i; ?>').val(nombre+'/'+apellido+'/'+correo+'/'+telefono+'/'+area);

          })

        <?php } ?>

      })
                      </script>