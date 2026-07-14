<?php require_once("php/aut.php"); ?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Inkpulse - Colegio</title>

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
              <div class="col-sm-6 col-sm-12">
                <div class="title">
                  <h4>Ficha de colegio</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                      Colegios
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Colegio
                    </li>
                  </ol>
                </nav>
              </div>
              
            </div>
          </div>
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            <?php

              if (isset($_GET["codigo"])) {
                $codigo_col= $_GET["codigo"];
              }
              else {
                $codigo_col= $_POST["codigo"];
              }
                                
              $sql = "SELECT * FROM colegios WHERE codigo='".$codigo_col."'";

              $req = $bdd->prepare($sql);
              $req->execute();

              $colegio = $req->fetch();

              $sql_promo = "SELECT u.id, CONCAT(u.nombres,' ',u.apellidos) as promotor, u.tipo, z.zona FROM usuarios u JOIN zonas z ON u.cod_zona=z.codigo WHERE cod_zona='".$colegio["cod_zona"]."'";

              $req_promo = $bdd->prepare($sql_promo);
              $req_promo->execute();

              $promotor = $req_promo->fetch();

              if (isset($_POST["periodo"])) {
                $periodo=$_POST["periodo"];
              }
              else {
                $periodo=$_GET["periodo"];
              }

            ?>
            <center><h5 class="h4 text-blue mb-20"><?php echo $colegio["colegio"]; ?></h5></center>
                <div class="tab">
                  <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                      <a
                        class="nav-link active"
                        data-toggle="tab"
                        href="#info_basica"
                        role="tab"
                        aria-selected="true"
                        >Información básica</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#info_contac"
                        role="tab"
                        aria-selected="false"
                        >Información de contacto</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#poblacion"
                        role="tab"
                        aria-selected="false"
                        >Población</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#presupuesto"
                        role="tab"
                        aria-selected="false"
                        >Presupuesto</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#adopciones"
                        role="tab"
                        aria-selected="false"
                        >Adopciones</a
                      >
                    </li>
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#atenciones"
                        role="tab"
                        aria-selected="false"
                        >Atenciones a clientes</a
                      >
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div
                      class="tab-pane show active"
                      id="info_basica"
                      role="tabpanel"
                    >
                      <div class="pd-20">
                        <form action="php/actualizar_colegio.php" method="POST">
                          <div class="row">
                            <?php if ($promotor['tipo']==3 || $promotor['tipo']==1) {

                              list($empresa,$n_zona) = explode("/", $promotor["zona"]);

                            ?>
                              <div class="col-sm-4">
                                <h5>Empresa: <?php echo $empresa; ?></h5>
                              </div>
                              <div class="col-sm-4">
                                <h5>Zona: <?php echo $n_zona; ?></h5>
                              </div>

                            <?php }else{

                              $sql_sz="SELECT sub_zona FROM sub_zonas WHERE id='".$colegio["sub_zona"]."'";
                              $req_sz = $bdd->prepare($sql_sz);
                              $req_sz->execute();
                              $sub_zona = $req_sz->fetch();

                            ?>
                              <div class="col-sm-4">
                                <h5>Empresa: <?php echo $promotor['promotor']; ?></h5>
                              </div>
                              <div class="col-sm-4">
                                <h5>Zona: <?php echo $sub_zona['sub_zona']; ?></h5>
                              </div>
                              <?php } ?>
                              <div class="col-sm-4">
                                <?php if ($_SESSION['tipo']==6) { ?>
                                  
                                  <div class="form-group">
                                    <label>Responsable <small style="color:red;"> *</small></label>
                                    <input type="text" class="form-control" placeholder="Responsable" name="colegio"  value="<?php echo $colegio['responsable']; ?>" required />
                                  </div>
                                  
                                <?php }else{

                                  if ($promotor['tipo']==3 || $promotor['tipo']==1) {

                                    echo "<h5>Responsable: ".$promotor['promotor']."</h5>"; 

                                  }else{ ?>
                                    <div class="form-group">
                                      <label>Responsable <small style="color:red;"> *</small></label>
                                      <input type="text" class="form-control" placeholder="Responsable" name="colegio"  value="<?php echo $colegio['responsable']; ?>" required />
                                    </div>
                                  <?php }
                                  }
                                  ?>
                              </div> 
                          </div>
                          <br>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Nombre de la institución <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Nombre de la institución" name="colegio"  value="<?php echo $colegio['colegio']; ?>" required />
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label>DANE <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="DANE" name="dane" value="<?php echo $colegio['dane']; ?>" required/>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label>Calendario <small style="color:red;"> *</small></label>
                                <select class="custom-select" name="calendario" required>
                                  <option value="">Seleccione...</option>
                                  
                                    <?php

                                      $sql = "SELECT * FROM calendarios";

                                      $req = $bdd->prepare($sql);
                                      $req->execute();

                                      $calendarios = $req->fetchAll();

                                      foreach ($calendarios as $calendario) {
                                        if ($calendario["id"]==$colegio["id_calendario"]) {
                                          echo '<option value="'.$calendario["id"].'" SELECTED>'.$calendario["calendario"].'</option>';
                                        }else{
                                           echo '<option value="'.$calendario["id"].'">'.$calendario["calendario"].'</option>';
                                        }
                                       
                                      }

                                    ?>


                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Provincia <small style="color:red;"> *</small></label>
                                <select class="custom-select2" name="departamento" required>
                                  <option value="">Seleccione...</option>
                                  
                                    <?php

                                      $sql = "SELECT * FROM departamentos";

                                      $req = $bdd->prepare($sql);
                                      $req->execute();

                                      $departamentos = $req->fetchAll();

                                      foreach ($departamentos as $departamento) {
                                        if ($departamento["id"]==$colegio["departamento"]) {
                                          echo '<option value="'.$departamento["id"].'" SELECTED>'.$departamento["departamento"].'</option>';
                                        }else{
                                           echo '<option value="'.$departamento["id"].'">'.$departamento["departamento"].'</option>';
                                        }
                                       
                                      }

                                    ?>


                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Ciudad <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Ciudad" name="ciudad" value="<?php echo $colegio['ciudad']; ?>" required/>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Barrio</label>
                                <input type="text" class="form-control" placeholder="Barrio" name="barrio"  value="<?php echo $colegio['barrio']; ?>"/>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Dirección <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Dirección" name="direccion" value="<?php echo $colegio['direccion']; ?>" required/>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Teléfono <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Teléfono" name="telefono_c" value="<?php echo $colegio['telefono']; ?>" required/>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Página Web</label>
                                <input type="text" class="form-control" placeholder="Página Web" name="web"  value="<?php echo $colegio['web']; ?>"/>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label>Correo institucional</label>
                                <input type="text" class="form-control" placeholder="Correo institucional" name="correo_i" value="<?php echo $colegio['correo_i']; ?>"/>
                              </div>
                            </div>

                          </div>
                          <hr style="background-color: #4c00ff;">
                          <div class="row">

                            
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label>Segmento <small style="color:red;"> *</small></label>
                                  <select class="custom-select" name="segmento" required>
                                    <option value="">Seleccione...</option>
                                    
                                      <?php

                                        $sql = "SELECT * FROM segmentos";

                                        $req = $bdd->prepare($sql);
                                        $req->execute();

                                        $segmentos = $req->fetchAll();

                                        foreach ($segmentos as $segmento) {
                                          if ($segmento["id"]==$colegio["id_segmento"]) {
                                            echo '<option value="'.$segmento["id"].'" SELECTED>'.$segmento["segmento"].'</option>';
                                          }else{
                                             echo '<option value="'.$segmento["id"].'">'.$segmento["segmento"].'</option>';
                                          }
                                         
                                        }

                                      ?>


                                  </select>
                                </div>
                              </div>
                            
                            <div class="col-sm-4">

                              <div class="form-group">
                                <label>Status <small style="color:red;"> *</small></label>
                                <select class="custom-select" name="status" required>
                                  <option value="">Seleccione...</option>
                                  
                                    <?php

                                      $sql = "SELECT id, id_status FROM colegios_status WHERE id_colegio='".$colegio["id"]."' AND id_periodo='".$_GET['periodo']."'";

                                      $req = $bdd->prepare($sql);
                                      $req->execute();
                                      $cole_status = $req->fetch();

                                      $sql = "SELECT * FROM status_cubrimiento WHERE act=1";

                                      $req = $bdd->prepare($sql);
                                      $req->execute();

                                      $status = $req->fetchAll();

                              
                                      foreach ($status as $statu) {

                                        if ($statu["id"]==$cole_status["id_status"]) {
                                          echo '<option value="'.$statu["id"].'" SELECTED>'.$statu["status"].'</option>';
                                        }else{
                                          echo '<option value="'.$statu["id"].'">'.$statu["status"].'</option>';
                                        }

                                        
                                      }    

                                    ?>

                                </select>
                              </div>
                             
                            </div>
                            <?php if ($_SESSION["tipo"]!=6) { ?>
                              <div class="col-sm-4">

                                <div class="form-group">
                                  <label>Estado de cliente <small style="color:red;"> *</small></label>
                                  <select class="custom-select" name="estado_cliente" required>
                                    <option value="">Seleccione...</option>
                                    
                                      <?php

                                        $sql = "SELECT id, id_estado_cliente FROM colegios_estados_clientes WHERE id_colegio='".$colegio["id"]."' AND id_periodo='".$_GET['periodo']."'";

                                        $req = $bdd->prepare($sql);
                                        $req->execute();
                                        $cole_estado = $req->fetch();

                                        $sql = "SELECT * FROM estados_cliente WHERE act=1";

                                        $req = $bdd->prepare($sql);
                                        $req->execute();

                                        $estados = $req->fetchAll();

                                
                                        foreach ($estados as $estado) {

                                          if ($estado["id"]==$cole_estado["id_estado_cliente"]) {
                                            echo '<option value="'.$estado["id"].'" SELECTED>'.$estado["estado"].'</option>';
                                          }else{
                                            echo '<option value="'.$estado["id"].'">'.$estado["estado"].'</option>';
                                          }

                                          
                                        }    

                                      ?>

                                  </select>
                                </div>
                               
                              </div>
                            <?php } ?>
                          </div>
                          <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                          <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                          <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
                           <?php if($_SESSION["tipo"] !=2 && $_SESSION["tipo"] != 4) {
                              if($_SESSION["zona"] ==$colegio["cod_zona"] || $_SESSION["tipo"] == 1) { ?>
                                <center><button class="btn btn-primary btn-lg"><i class="icon-copy bi bi-sd-card-fill"></i> Guardar</button></center>
                              <?php } ?>
                            <?php } ?> 
                        </form>
                      </div>
                    </div>
                    <div class="tab-pane" id="info_contac" role="tabpanel">
                      <div class="pd-20">

                        <br><h5>Nivel 1. Administrativo</h5>
                        <br>
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_adm" type="button">Agregar contacto</a><br><br>
                        <?php

                            $sql = "SELECT * FROM trabajadores_colegios WHERE id_colegio='".$colegio["id"]."' AND cargo !=6";

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
                                <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
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
                                <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
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

                            $sql = "SELECT * FROM trabajadores_colegios WHERE id_colegio='".$colegio["id"]."' AND cargo=6";

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

                                        $sql = "SELECT * FROM materias";

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
                                <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
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

                                              $sql = "SELECT * FROM materias";

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

                                                $sql = "SELECT * FROM materias";

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
                                <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                                <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
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
                    </div>
                    <div class="tab-pane" id="poblacion" role="tabpanel">
                      <div class="pd-20">
                        <form action="php/poblacion.php" method="POST">
                        <div class="table-responsive">
                          <style>
                            input[type=number] { -moz-appearance:textfield; }
                            input[type=number]::-webkit-inner-spin-button, 
                            input[type=number]::-webkit-outer-spin-button { 
                              -webkit-appearance: none; 
                                margin: 0; 
                            }

                            #tabla.table td {
                              padding: 0 !important;
                            }
                            .prescolar {
                              background-color: #FB979E;
                              color: #000;
                              text-align: center;
                            }
                            .primaria {
                              background-color: #8A92FF;
                              color: #000;
                              text-align: center;
                            }
                            .bachillerato {
                              background-color: #9C63FB;
                              color: #000;
                              text-align: center;
                            }
                            #total-general{
                              background-color: #74FFC9;
                              color: #000;
                              text-align: center;
                            }
                            tr.fila-base td, #tabla tr th {
                              text-align: center;
                            }
                          </style>
                        <table id="tabla" class="table table-striped table-sm table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>Cursos ↓ / Grados →</th>
                              <th class="prescolar">PRE</th><th class="prescolar">JAR</th><th class="prescolar">TRA</th>
                              <th class="primaria">1</th><th class="primaria">2</th><th class="primaria">3</th><th class="primaria">4</th><th class="primaria">5</th>
                              <th class="bachillerato">6</th><th class="bachillerato">7</th><th class="bachillerato">8</th><th class="bachillerato">9</th><th class="bachillerato">10</th><th class="bachillerato">11</th>
                            </tr>
                          </thead>
                          <tbody>

                            <?php

                              $sql = "SELECT MAX(paralelos) as nunfila FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$_GET['periodo']}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $nunfila = $req->fetch();

                              if ($nunfila['nunfila'] <1) {

                                $sql_pa="SELECT id FROM periodos WHERE id_calendario='{$colegio['id_calendario']}' ORDER BY id DESC LIMIT 1 OFFSET 1;";

                                $req_pa = $bdd->prepare($sql_pa);
                                $req_pa->execute();
                                $pa = $req_pa->fetch();

                                $periodo_po=$pa['id'];

                                $sql = "SELECT MAX(paralelos) as nunfila FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$pa['id']}'";
                                $req = $bdd->prepare($sql);
                                $req->execute();
                                $nunfila = $req->fetch();

                                if ($nunfila['nunfila'] <1) {
                                  $nunfila['nunfila']=1;
                                }else{
                                  echo "<style>
                                    .poblacion {
                                      color: #FF4335 !important;
                                    }
                                  </style>";
                                  echo '<span class="poblacion">* Se está mostrando la población de la temporada anterior, verifícala y dale en “Guardar”</span><br><br>';
                                }

                              }else{
                                $periodo_po=$_GET['periodo'];
                              }

                             
                            ?>

                            <?php for ($i=0; $i < $nunfila['nunfila']; $i++) {
                              $p=$i+1;

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=1 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $prejardin = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=2 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $jardin = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=3 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $transicion = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=4 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $primero = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=5 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $segundo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=6 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $tercero = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=7 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $cuarto = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=8 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $quinto = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=9 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $sexto = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=10 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $septimo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=11 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $octavo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=12 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $noveno = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=13 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $decimo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$colegio['id']}' AND id_periodo='{$periodo_po}' AND id_grado=14 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $once = $req->fetch();

                            ?>



                              <tr class="fila-base">
                                <td>0<?php echo $i+1 ?></td>
                                <td><input type="text" class="poblacion" size="2" name="1-<?php echo $i+1 ?>" id="1-<?php echo $i+1 ?>" value="<?php echo $prejardin['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="2-<?php echo $i+1 ?>" id="2-<?php echo $i+1 ?>" value="<?php echo $jardin['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="3-<?php echo $i+1 ?>" id="3-<?php echo $i+1 ?>" value="<?php echo $transicion['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="4-<?php echo $i+1 ?>" id="4-<?php echo $i+1 ?>" value="<?php echo $primero['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="5-<?php echo $i+1 ?>" id="5-<?php echo $i+1 ?>" value="<?php echo $segundo['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="6-<?php echo $i+1 ?>" id="6-<?php echo $i+1 ?>" value="<?php echo $tercero['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="7-<?php echo $i+1 ?>" id="7-<?php echo $i+1 ?>" value="<?php echo $cuarto['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="8-<?php echo $i+1 ?>" id="8-<?php echo $i+1 ?>" value="<?php echo $quinto['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="9-<?php echo $i+1 ?>" id="9-<?php echo $i+1 ?>" value="<?php echo $sexto['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="10-<?php echo $i+1 ?>" id="10-<?php echo $i+1 ?>" value="<?php echo $septimo['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="11-<?php echo $i+1 ?>" id="11-<?php echo $i+1 ?>" value="<?php echo $octavo['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="12-<?php echo $i+1 ?>" id="12-<?php echo $i+1 ?>" value="<?php echo $noveno['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="13-<?php echo $i+1 ?>" id="13-<?php echo $i+1 ?>" value="<?php echo $decimo['alumnos'] ?>"></td>
                                <td><input type="text" class="poblacion" size="2" name="14-<?php echo $i+1 ?>" id="14-<?php echo $i+1 ?>" value="<?php echo $once['alumnos'] ?>"></td>
                              </tr>
                            <?php } ?>                            
                            </tbody>
                          
                          <tfoot>
                            <tr id="filaTotal">
                              <td><strong>Total:</strong></td>
                              <td class="total-col prescolar"></td>
                              <td class="total-col prescolar"></td>
                              <td class="total-col prescolar"></td>
                              <td class="total-col primaria"></td>
                              <td class="total-col primaria"></td>
                              <td class="total-col primaria"></td>
                              <td class="total-col primaria"></td>
                              <td class="total-col primaria"></td>
                              <td class="total-col bachillerato"></td>
                              <td class="total-col bachillerato"></td>
                              <td class="total-col bachillerato"></td>
                              <td class="total-col bachillerato"></td>
                              <td class="total-col bachillerato"></td>
                              <td class="total-col bachillerato"></td>
                              <td id="total-general"></td>
                            </tr>
                          </tfoot>
                        </table>
                        </div>
                        <button class="btn" type="button" id="agregar">Agregar paralelo</button>
                        <button class="btn" type="button" id="quitar">Quitar paralelo</button>
                        <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                        <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                        <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
                        <center><br><button class="btn btn-primary">Guardar</button></center>
                        </form>
                      </div>
                    </div>

                    <div class="tab-pane" id="presupuesto" role="tabpanel">
                      <div class="pd-20">
                         <h5>En construcción</h5>
                      </div>
                    </div>

                    <div class="tab-pane" id="adopciones" role="tabpanel">
                      <div class="pd-20">
                         <h5>En construcción</h5>
                      </div>
                    </div>

                    <div class="tab-pane" id="atenciones" role="tabpanel">
                      <div class="pd-20">
                         

                     
                          <center><h3>Solicitud de Recursos</h3></center><br><br>

                          <?php

                            $sql = "SELECT SUM(r.legaliza) as total FROM solicitudes_recursos s JOIN recursos_solicitados r ON s.id=r.id_solicitud WHERE s.id_colegio='".$colegio["id"]."' AND s.id_periodo='".$_GET['periodo']."' AND s.estado='4';";

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
                                        $sql = "SELECT t.id, CONCAT(nombre, ' ', apellido) as trabajador, c.cargo FROM trabajadores_colegios t JOIN cargos c ON t.cargo=c.id WHERE t.telefono !='' AND id_colegio='{$colegio['id']}'";

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
                                      <label id="l_primaria_at" for="primaria_at" class="control-label">Primaria</label>
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
                                          <label id="l_primaria_at<?php echo $i;?>" for="primaria_at<?php echo $i;?>" class="control-label">Primaria</label>
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

                                  <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                                  <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                                  <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">       
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
                              $sql = "SELECT e.estado, s.id,s.fecha, CONCAT(t.nombre, ' ', t.apellido) as solicitante, c.cargo, s.fecha_entrega, s.conse FROM solicitudes_recursos s JOIN estados_pedidos e ON e.id=s.estado JOIN trabajadores_colegios t ON t.id=s.solicitante JOIN cargos c ON c.id=t.cargo WHERE s.id_colegio='".$colegio["id"]."' AND s.id_periodo='".$_GET['periodo']."' ORDER BY s.id DESC";

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
                    </div>

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

      //filas población

      $(document).ready(function () {

        function calcularTotales() {
          let numColumnas = $('#tabla tbody tr:first input').length;
          let totales = Array(numColumnas).fill(0);

          $('#tabla tbody tr').not('#filaTotal').each(function () {
            $(this).find('input').each(function (i) {
              let val = parseFloat($(this).val()) || 0;
              totales[i] += val;
            });
          });

          $('#filaTotal .total-col').each(function (i) {
            $(this).text(totales[i]);
          });

          let total = 0;

          $('#tabla tbody tr').not('#filaTotal').each(function () {
            $(this).find('input').each(function () {
              let val = parseFloat($(this).val()) || 0;
              total += val;
            });
          });

          $('#total-general').text(total);
        }

        calcularTotales();
      

        $('#agregar').click(function () {
          // Clona la última fila actual
          let ultimaFila = $('#tabla tbody tr.fila-base').last();
          let nuevaFila = ultimaFila.clone();

          // Obtener número de fila desde la primera celda
          let numeroAnterior = parseInt(ultimaFila.find('td:first').text(), 10);
          let numeroNuevo = ("0" + (numeroAnterior + 1)).slice(-2);
          nuevaFila.find('td:first').text(numeroNuevo);

          // Recorre los inputs y ajusta name e id en base al número anterior
          nuevaFila.find('input').each(function () {
            let input = $(this);
            let name = input.attr('name');
            let id = input.attr('id');

            // Detecta el número actual y lo reemplaza por el nuevo
            if (name && id) {
              let nuevoName = name.replace(/\d+$/, numeroAnterior + 1);
              let nuevoId = id.replace(/\d+$/, numeroAnterior + 1);
              input.attr('name', nuevoName).attr('id', nuevoId).val('');
            }
          });

          // Agrega la fila clonada al DOM
          $('#tabla tbody').append(nuevaFila);

          calcularTotales(); // <-- Aquí
        });

        $('#agregar').click(function () {
          let ultimaFila = $('#tabla tbody tr.fila-datos').last();
          let nuevaFila = ultimaFila.clone();
          
          // Obtener número base desde la primera celda
          let numActual = parseInt(ultimaFila.find('td:first').text(), 10);
          let numNuevo = ("0" + (numActual + 1)).slice(-2); // formato 01, 02...

          nuevaFila.find('td:first').text(numNuevo); // actualizar numeración de fila

          // Actualizar name e id de inputs
          nuevaFila.find('input').each(function () {
            let input = $(this);
            let name = input.attr('name');
            let id = input.attr('id');

            if (name) {
              let baseName = name.replace(/\d+$/, '');
              input.attr('name', baseName + numNuevo);
            }

            if (id) {
              let baseId = id.replace(/\d+$/, '');
              input.attr('id', baseId + numNuevo);
            }

            input.val(''); // limpiar valor
          });

          $('#tabla tbody').append(nuevaFila);
         calcularTotales(); // <-- Aquí
        });

  
        $('#quitar').click(function () {
          let filas = $('#tabla tbody tr');

          if (filas.length > 1) {
            let ultimaFila = filas.last();
            let inputs = ultimaFila.find('input');

            let puedeEliminar = true;

            inputs.each(function () {
              let val = $(this).val();
              // Si hay al menos un valor mayor a 0, no se puede eliminar
              if (val !== "" && parseInt(val) > 0) {
                puedeEliminar = false;
                return false; // salir del each
              }
            });

            if (puedeEliminar) {
              ultimaFila.remove();
              contador--;
            } else {
              alert("No se puede eliminar esta fila. Contiene valores mayores a 0.");
            }
          } else {
            alert("Debe haber al menos una fila.");
          }

           calcularTotales(); // <-- Aquí
        });

        // Detectar cambios en cualquier input de la tabla
        $('#tabla').on('input', 'input', function () {
          calcularTotales(); // <-- Aquí
        });

      });

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

      

       $(document).ready(function() {
        $("#modal_atenciones .custom-select2").select2({
           dropdownParent: $('#modal_atenciones')
        });

        $("#modal_profes .custom-select2").select2({
           dropdownParent: $('#modal_profes')
        });
      });

       $("#zonificacion").addClass("show");
       $("#zonificacion .submenu").css("display","block");
       $("#ver_zonificacion").addClass("active");

       $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');

        if (tab) {
          // Quitar clases activas actuales
          $('.nav-link').removeClass('active');
          $('.tab-pane').removeClass('active');

          // Activar el tab link
          $('.nav-link[href="#' + tab + '"]').addClass('active');

          // Activar el contenido del tab
          $('#' + tab).addClass('active');
        }
      });
    </script>
    
  </body>
</html>
