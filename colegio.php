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
                    <li class="nav-item info_b">
                      <a
                        class="nav-link active"
                        data-toggle="tab"
                        href="#info_basica"
                        role="tab"
                        aria-selected="true"
                        >Información básica</a
                      >
                    </li>
                    <li class="nav-item info_c">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#info_contac"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_info_contac.php"
                        >Información de contacto</a
                      >
                    </li>
                    <li class="nav-item pobla">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#poblacion"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_poblacion.php"
                        >Población</a
                      >
                    </li>
                    <li class="nav-item presupuesto">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#presupuesto"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_presup.php"
                        >Presupuesto</a
                      >
                    </li>
                    <li class="nav-item adop">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#adopciones"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_adopciones.php"
                        >Adopciones</a
                      >
                    </li>
                    <li class="nav-item atenc">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#atenciones"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_atenciones.php"
                        >Atenciones a clientes</a
                      >
                    </li>
                    <li class="nav-item atenc">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#adjuntos"
                        role="tab"
                        aria-selected="false" data-url="ajax/tab_adjuntos.php"
                        >Adjuntos</a
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
                        <form action="php/actualizar_colegio.php" method="POST" enctype="multipart/form-data">
                          <?php if ($_SESSION['tipo']==1) { ?>
                            <a href="" class="btn btn-warning" data-toggle="modal" data-target="#modal_reasig">Reasignar</a><br><br>
                          <?php } ?>
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
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label>DANE <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="DANE" name="dane" value="<?php echo $colegio['dane']; ?>" required/>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label>Nombre de la institución <small style="color:red;"> *</small></label>
                                <input type="text" class="form-control" placeholder="Nombre de la instricución" name="colegio"  value="<?php echo $colegio['colegio']; ?>" required />
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
                                <label>Departamento <small style="color:red;"> *</small></label>
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
                                <label>Pagina Web</label>
                                <input type="text" class="form-control" placeholder="Pagina Web" name="web"  value="<?php echo $colegio['web']; ?>"/>
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

                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label for="">Propuesta comercial</label>
                                <select name="propuesta_c" id="propuesta_c" class="form-control">
                                  <?php

                                    $sql = "SELECT adjunto FROM adjuntos WHERE id_colegio='".$colegio["id"]."' AND id_periodo='".$_GET['periodo']."' AND tipo=1";

                                    $req = $bdd->prepare($sql);
                                    $req->execute();
                                    $count_p = $req->rowCount();
                                    
                                    if ($count_p > 0) {
                                      echo '<option value="0">No</option>
                                      <option value="1" SELECTED>Si</option>';
                                    }ELSE{
                                      echo '<option value="0">No</option>
                                      <option value="1">Si</option>';
                                    }
                                  ?>
                                  
                                </select>
                              </div>
                            </div>
                            <?php if ($count_p > 0) {
                              $propuesta = $req->fetch();
                              list($antes,$archivo)=explode("_", $propuesta["adjunto"]);
                              echo '<div class="col-sm-4">
                                <label>Adjunto propuesta comercial</>
                                <a href="adjuntos/'.$propuesta["adjunto"].'" download="'.$archivo.'">'.$archivo.'</a>
                              </div>';

                            ?>

                              
                              
                            <?php }else { ?>
                              <div class="col-sm-4 d-none" id="adjunto_propuesta_c">
                  
                                <div class="form-group">
                                  <label class="control-label no-padding-right" for="archivo"> Adjunto propuesta comercial <small style="color:red;"> *</small></label>

                                  <input type="file" name="archivo" id="i_pc" placeholder="Adjunto" class="form-control" />
                                    
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
                    <div class="tab-pane" id="info_contac" role="tabpanel"></div>
                    <div class="tab-pane" id="poblacion" role="tabpanel"></div>

                    <div class="tab-pane" id="presupuesto" role="tabpanel"></div>

                    <div class="tab-pane" id="adopciones" role="tabpanel"></div>

                    <div class="tab-pane" id="atenciones" role="tabpanel"></div>

                    <div class="tab-pane" id="adjuntos" role="tabpanel"></div>

                  </div>
                </div>
                      

                <div class="modal fade bs-example-modal-lg" id="modal_reasig" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">
                          Reasignar colegio
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                          ×
                        </button>
                      </div>
                      <form action="php/reasignar_colegio.php" method="POST">
                        <div class="modal-body">
                          <div class="row">

                            <div class="col-sm-6">
                
                              <div class="form-group">
                                <label class="control-label no-padding-right" for="empresa"> Empresa:<small style="color:red;"> *</small> </label>

                                <select name="empresa" id="empresa" class="form-control custom-select2" required>
                                  <option value="">Seleccione</option>
                                  <option value="1">EUREKA</option>
                                    <?php

                                      $sql = "SELECT * FROM zonas WHERE zona NOT LIKE '%Eureka%' AND zona NOT LIKE '%ALEJANDRO%'";

                                      $req = $bdd->prepare($sql);
                                      $req->execute();

                                      $zonas = $req->fetchAll();

                                      foreach ($zonas as $zona) {
                                                          
                                        echo '<option value="'.$zona["codigo"].'">'.$zona["zona"].'</option>';
                                                       
                                      }

                                    ?>
                                  </select>
                                  
                              </div>

                            </div>

                            <div class="col-sm-6">
                              
                              <div class="form-group">
                                <label class="control-label no-padding-right" for="zona"> Zona:<small style="color:red;"> *</small> </label>

                                <select name="zona" id="zona" class="form-control custom-select2" required>
                                  <option value="">Seleccione</option>
                                </select>
                                  
                              </div>

                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6 col-responsable d-none">

                              <div class="form-group">
                                <label class="control-label no-padding-right" for="responsable"> Responsable:<small style="color:red;"> *</small> </label>              
                                <input type="text" name="responsable" id="responsable" placeholder="" class="form-control" />
                                  
                              </div>  
                            </div>
                          </div>   
                        </div>
                        <div class="modal-footer">
                          <button  class="btn btn-primary">
                            Guardar
                            </button>
                          </div>
                          <input type="hidden" name="id_colegio" value='<?php echo $colegio["id"] ?>'>
                          <input type="hidden" name="cod_colegio" value="<?php echo $colegio['codigo'] ?>">
                      </form>
                    </div>
                    </div>

                </div>
        </div>
        <?php

          $sql = "SELECT f_cierre FROM periodos WHERE id='".$_GET['periodo']."'";

          $req = $bdd->prepare($sql);
          $req->execute();

          $gp_periodo = $req->fetch();

        ?>
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


       $("#zonificacion").addClass("show");
       $("#zonificacion .submenu").css("display","block");
       $("#ver_zonificacion").addClass("active");

      $(document).ready(function () {
        $("#modal_reasig .custom-select2").select2({
          dropdownParent: $('#modal_reasig')
        });
        //llamar contenido de tabs dinamicamente
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href");
          var baseUrl = $(e.target).data("url");
          var codigo = <?php echo json_encode($colegio["codigo"]); ?>;
          // Agrega parámetros dinámicos
          if (target !="#presupuesto" && target !="#adopciones") {
            var urlConParametros = baseUrl + '?colegio=' + <?php echo $colegio["id"] ?> + '&periodo='+ <?php echo $_GET["periodo"] ?>+ '&codigo='+ encodeURIComponent(codigo)+ '&id_calendario='+ <?php echo $colegio['id_calendario'] ?>;
          }else{
            var responsable = <?php echo json_encode($colegio["responsable"]); ?>;
            var f_cierre = <?php echo json_encode($gp_periodo["f_cierre"]); ?>;
            var urlConParametros = baseUrl + '?colegio=' + <?php echo $colegio["id"] ?> + '&periodo='+ <?php echo $_GET["periodo"] ?>+ '&codigo='+ encodeURIComponent(codigo)+ '&cod_zona='+ <?php echo $colegio['cod_zona'] ?>+ '&sub_zona='+ <?php echo $colegio['sub_zona'] ?>+ '&responsable='+encodeURIComponent(responsable)+ '&promotor='+ <?php echo $promotor['id'] ?>+ '&f_cierre='+encodeURIComponent(f_cierre);

           
          }
          

          if ($(target).is(':empty')) {
            $(target).html("<br><br><center style='font-size:30px; color:#E25906'>Cargando...</center>");
            $(target).load(urlConParametros);

          }

          setTimeout(function () {
            const selects = $(target).find('select.custom-select2');
            if (selects.length) {
              

              $("#modal_atenciones .custom-select2").select2({
                dropdownParent: $('#modal_atenciones')
              });

              $("#modal_profes .custom-select2").select2({
                dropdownParent: $('#modal_profes')
              });

              $("#modal_presupuesto .custom-select2").select2({
                  dropdownParent: $('#modal_presupuesto')
              });

              $("#modal_adopciones .custom-select2").select2({
                dropdownParent: $('#modal_adopciones')
              });
            }
          }, 1000); // Pequeña espera para asegurar que el DOM ya fue insertado
        });

        //activar tabs
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');

        if (tab) {
           $('.nav-link[href="#' + tab + '"]').tab('show');
           
        }


        $('#propuesta_c').on('change',function(){
            var valor = $(this).val();

            if (valor==1) {
              $("#adjunto_propuesta_c").removeClass("d-none");
              $("#i_pc").attr("required","required");
               
                       
            }else {
              $("#adjunto_propuesta_c").addClass("d-none");
              $("#i_pc").removeAttr("required","required");
            }
                
        });

      });


      $('#empresa').on('change',function(){
        var valor = $(this).val();
        
        if (valor==1) {
          $(".col-responsable").addClass("d-none");
          $(".col-responsable").addClass("d-none");
           $("#responsable").removeAttr("required");
        }else{
          $(".col-responsable").removeClass("d-none");
          $("#responsable").attr("required","required");
        }
       
        var dataString = 'empresa='+valor;
        $.ajax({

          url: "ajax/buscar_zona.php",
          type: "POST",
          data: dataString,
          success: function (resp) {
                   
            $("#zona").html(resp);                        
            console.log(resp);
            if(valor =="") {
              $("#zona").html("");
            }
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

    </script>
    
  </body>
</html>
