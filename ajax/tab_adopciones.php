<?php
	/*ini_set("display_errors", 1);

	ini_set("display_startup_errors", 1);

	error_reporting(E_ALL);*/

	require_once("../php/aut.php");
  	include("../conexion/bdd.php");

	$sql_periodo="SELECT * FROM periodos WHERE id='".$_GET['periodo']."'";

	$req_periodo = $bdd->prepare($sql_periodo);
	$req_periodo->execute();
	$gp_periodo = $req_periodo->fetch();
?>

<div class="pd-20">
	<style>
		.table td {
			padding: 5px !important;
		}
	</style>
	<a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_adopciones" type="button">Añadir libros</a><br><br>
	<div class="modal fade bs-example-modal-xl" id="modal_adopciones" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
            	<div class="modal-header">
                	<h4 class="modal-title" id="myLargeModalLabel">
                                  Añadir libros
               		</h4>
                 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form action="php/areas_cumplimiento.php" method="POST" class="miFormulario">
                <div class="modal-body">
                               
					<div class="otra_aod">
                        <center><h4>Añadir nuevo</h4></center><br>
                            
                        <h4>Libro #1:</h4>
                        <div class="row">
                            <div class="col-sm-3">
                                
                                <div class="form-group">
                                	<label id="l_materiad" class="control-label no-padding-right" for="materiad"> Materia:<small style="color:red;"> *</small></label>

                                    <select name="materiad" id="materiad" class="form-control materiad">
                                    	<option value="">Seleccionar</option>
                                        <?php 
                                          $sql = "SELECT id, materia FROM materias";
                                          $req = $bdd->prepare($sql);
                                          $req->execute();
                                          $materias = $req->fetchAll();
                                          foreach($materias as $materia) {
                                              $id = $materia['id'];
                                              $nom = $materia['materia'];
                                              echo '<option value="'.$id.'">'.$nom.'</option>';
                                          }
                                        ?>
                                    </select>
                                        
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                	<label for="gradod" id="l_gradod" class="control-label no-padding-right">Grado<small style="color:red;"> *</small></label>
                                
                                    <select name="gradod" required id="gradod" class="form-control gradod">
                                       <option value="">Seleccionar</option>

                                        <?php 
                                          $sql = "SELECT id, grado FROM grados";
                                          $req = $bdd->prepare($sql);
                                          $req->execute();
                                          $grados = $req->fetchAll();
                                          foreach($grados as $grado) {
                                              $id = $grado['id'];
                                              $nom = $grado['grado'];
                                              echo '<option value="'.$id.'">'.$nom.'</option>';
                                          }
                                        ?>
                                          
                                          
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                	<label for="grado_otrod" id="l_grado_otrod" class="control-label no-padding-right d-none g_otrod">Grado otro<small style="color:red;"> *</small></label>
                                
                                    <select name="grado_otrod" id="grado_otrod" class="form-control g_otrod d-none">
                                        <option value="">Seleccionar</option>

                                        <?php 
                                          $sql = "SELECT id, grado FROM grados WHERE id < 15";
                                          $req = $bdd->prepare($sql);
                                          $req->execute();
                                          $grados = $req->fetchAll();
                                          foreach($grados as $grado) {
                                              $id = $grado['id'];
                                              $nom = $grado['grado'];
                                              echo '<option value="'.$id.'">'.$nom.'</option>';
                                          }
                                        ?>
                                          
                                          
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                	<label  for="libro_ed" id="l_libro_ed" class="control-label no-padding-right">Libro<small style="color:red;"> *</small></label>
                                  
	                                <select name="libro_ed" id="libro_ed" class="form-control gradod custom-select2" required>
	                                            
	                                            
	                                </select>
                                </div>

                            </div>


                        </div>
                                                        
                        <input type="hidden" name="libs_aod[]" id="libs_aod">

                        <?php for ($i=1; $i < 100; $i++) { ?>

                            <div id="agg_aod<?php echo $i;?>" class="d-none">

                            <h4>Libro #<?php echo $i+1;?>:</h4>
                            <div class="row">
                            	<div class="col-sm-3">
                                  
                                    <div class="form-group">
                                    	<label id="l_materiad<?php echo $i;?>" class="control-label no-padding-right" for="materiad<?php echo $i;?>"> Materia:<small style="color:red;"> *</small></label>

	                                    <select name="materiad1" id="materiad<?php echo $i;?>" class="form-control materiad">
	                                        <option value="">Seleccionar</option>
	                                        <?php 
	                                            $sql = "SELECT id, materia FROM materias";
	                                            $req = $bdd->prepare($sql);
	                                            $req->execute();
	                                            $materias = $req->fetchAll();
	                                            foreach($materias as $materia) {
	                                                $id = $materia['id'];
	                                                $nom = $materia['materia'];
	                                                echo '<option value="'.$id.'">'.$nom.'</option>';
	                                            }
	                                        ?>
	                                    </select>
                                            
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="gradod<?php echo $i;?>" id="l_gradod<?php echo $i;?>" class="control-label no-padding-right">Grado<small style="color:red;"> *</small></label>
                                    
                                        <select name="gradod1" id="gradod<?php echo $i;?>" class="form-control gradod">
                                            <option value="">Seleccionar</option>

                                              <?php 
	                                            $sql = "SELECT id, grado FROM grados";
	                                            $req = $bdd->prepare($sql);
	                                            $req->execute();
	                                            $grados = $req->fetchAll();
	                                            foreach($grados as $grado) {
	                                                $id = $grado['id'];
	                                                $nom = $grado['grado'];
	                                                echo '<option value="'.$id.'">'.$nom.'</option>';
	                                            }
                                            ?>
                                              
                                              
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
	                                    <label for="grado_otrod<?php echo $i;?>" id="l_grado_otrod<?php echo $i;?>" class="control-label no-padding-right d-none g_otrod<?php echo $i;?>">Grado otro<small style="color:red;"> *</small></label>
	                                    
	                                    <select name="grado_otrod" id="grado_otrod<?php echo $i;?>" class="form-control g_otrod<?php echo $i;?> d-none">
	                                        <option value="">Seleccionar</option>

	                                        <?php 
	                                            $sql = "SELECT id, grado FROM grados WHERE id < 15";
	                                            $req = $bdd->prepare($sql);
	                                            $req->execute();
	                                            $grados = $req->fetchAll();
	                                            foreach($grados as $grado) {
	                                                $id = $grado['id'];
	                                                $nom = $grado['grado'];
	                                                echo '<option value="'.$id.'">'.$nom.'</option>';
	                                            }
	                                        ?>
	                                                       
	                                    </select>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
	                                    <label  for="libro_ed<?php echo $i;?>" id="l_libro_ed<?php echo $i;?>" class="control-label no-padding-right">Libro<small style="color:red;"> *</small></label>
	                                      
	                                    <select name="libro_ed" id="libro_ed<?php echo $i;?>" class="form-control gradod custom-select2">
	                                                
	                                                
	                                    </select>
                                    </div>

                                </div>


                            </div>
                                                        
                            <input type="hidden" name="libs_aod[]" id="libs_aod<?php echo $i;?>">

                        </div>

                    	<?php } ?>
                                  
                        <input type="hidden" name="promotor" id="promotor" value="<?php echo $_GET['promotor'] ?>">
                        <input type="hidden" name="id_colegio" id="cole" value="<?php echo $_GET["colegio"] ?>">
                        <input type="hidden" name="cod_zona" value="<?php echo $_GET['cod_zona'] ?>">
                        <input type="hidden" name="sub_zona" value="<?php echo $_GET['sub_zona'] ?>">
                        <input type="hidden" name="responsable" value="<?php echo $_GET['responsable'] ?>">
                        <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
                        <input type="hidden" name="periodo" value="<?php echo $gp_periodo['id'] ?>">
                    </div>
                    <?php  if($_SESSION["tipo"] !=2 ) { ?>
                        <?php if($_SESSION["zona"] ==$_GET["cod_zona"] || $_SESSION["tipo"] == 1) { ?>

                            <?php if ($_GET["f_cierre"] > date("Y-m-d")) { ?>

                                <a id="agregar_aod" style="cursor: pointer;">Agregar Otro +</a>
                                <br><br><center><button class="btn btn-success miBoton">Guardar</button></center>
                            <?php } ?>
                        <?php  } ?>
                    <?php  } ?>
                </div>
                </form>
    			     
            	

	            <div class="modal-footer">
	            
	            </div>

         </div>

    	</div>

	</div>
    <?php
                            
        $sql = "SELECT p.id,p.cod_area, b.materia, c.grado,l.id, l.libro,l.id_materia, l.id_grado, l.pri_sec, l.precio, desc_max, desc_max_dist FROM presupuestos p JOIN libros l ON p.id_libro=l.id JOIN materias b ON l.id_materia=b.id JOIN grados c ON l.id_grado=c.id WHERE id_colegio='".$_GET["colegio"]."' AND id_periodo='".$gp_periodo["id"]."' AND p.aprobado < 2 AND p.pre_definido='1' AND p.probabilidad !=3";


        $req = $bdd->prepare($sql);
        $req->execute();
        $libros_p = $req->fetchAll();

        $sql_hp = "SELECT id FROM presupuestos WHERE id_periodo='".$gp_periodo["id"]."' AND id_colegio='".$_GET["colegio"]."'";

        $req_hp = $bdd->prepare($sql_hp);
        $req_hp->execute();
        $num_hp= $req_hp->rowCount();

		echo "<form action='php/guardar_definicion.php' class='miFormulario' method='POST' id='form_definicion' name='f2'>";
                              
            echo "<script src='../vendors/scripts/jquery-2.1.4.min.js'></script><div class='table-responsive'>
                <table class='table table-sm table-bordered table-striped'>
                <thead>
                    <th>Título</th>
                    <th>Materia</th>
                    <th>Grado</th>
                    <!--<th>Paralelos</th>-->
                    <th>Alumnos</th>
                    <th>Tasa de compra</th>
                    <th>PVP</th>
                    <th>Descuento</th>
                    <th>Precio neto</th>
                    <th>Venta potencial</th>
                    <th>Precio venta padre</th>
                    <th>Adopción <input type='checkbox' id='seleccionar_pre'></th>
                    <th>Unidades venta real</th>
                    <th>Venta real</th>
                </thead>
                <tbody>";
                    foreach ($libros_p as $libro_p) {

                        if ($libro_p["id_grado"] == 15 || $libro_p["id_grado"] == 16 ) {

                            $sq_l2 = "SELECT l.id, l.libro,l.id_grado, l.precio, g.grado, m.materia FROM libros l JOIN materias m ON l.id_materia=m.id JOIN grados g ON l.id_grado=g.id WHERE l.pri_sec='".$libro_p["lib_eureka"]."'";
                            
                            $req_l2 = $bdd->prepare($sq_l2);
                            $req_l2->execute();
                            $libros2 = $req_l2->fetchAll();

                            foreach ($libros2 as $libro2) {

                                $sql_presup = "SELECT id, precio, tasa_compra, descuento, definido, tasa_compra_d, descuento_d, precio_venta_final FROM presupuestos WHERE id_libro='".$libro2["id"]."' AND id_periodo='".$gp_periodo["id"]."' AND id_colegio='".$_GET["colegio"]."'";
                            
                                $req_presup = $bdd->prepare($sql_presup);
                                $req_presup->execute();
                                $presup = $req_presup->fetch();
 
                                $libro=$libro2["libro"];

                                $sq_gp = "SELECT paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET['colegio']."' AND id_grado='".$libro2["id_grado"]."' AND id_periodo='".$gp_periodo["id"]."'";
                            
                                $req_gp = $bdd->prepare($sq_gp);
                                $req_gp->execute();
                                $gp = $req_gp->fetch();

                                echo '<script>alert('.$presup["definido"].')</script>';

                                 echo "<tr>
                                    <td>".$libro."</td>
                                    <td>".$libro2["materia"]."</td>
                                    <td>".$libro2["grado"]."</td>
                                    <!--<td>".$gp["paralelos"]."</td>-->
                                    <td id='alm_d".$libro2["id"]."'>".$gp["alumnos"]."</td>";
                                      

                                    if ($presup["tasa_compra"] !="" && $presup["tasa_compra_d"] ==0.00 ) {

                                        $presup["tasa_compra"] = $presup["tasa_compra"] *100;
                                        echo "<td><input type='text' size='2' name='tasa[]' id='tasa_d".$libro2["id"]."' value='".$presup["tasa_compra"]."'> %</td>";
                                    }
                                    elseif( $presup["tasa_compra_d"] !=""){

                                        $presup["tasa_compra_d"] = $presup["tasa_compra_d"] *100;
                                          echo "<td><input type='text' size='2' name='tasa[]' id='tasa_d".$libro2["id"]."' value='".$presup["tasa_compra_d"]."'> %</td>";

                                    }
                                    else {
                                        echo "<td><input type='text' size='2' name='tasa[]' id='tasa_d".$libro2["id"]."' value='0' required> %</td>";

                                    }
                                        
                                        
                                    if ($presup["precio"] !=0) {
                                        $precio=number_format($presup["precio"],0,",", ".");

                                        echo "<td id='pvp_d".$libro2["id"]."'>".$precio."</td>";

                                        echo "<input type='hidden' id='pvp_s_d".$libro2["id"]."' value='".$presup["precio"]."'>";
                                    }else {

                                        $precio=number_format($libro2["precio"],0,",", ".");

                                        echo "<td id='pvp_d".$libro2["id"]."'>".$precio."</td>";

                                        echo "<input type='hidden' id='pvp_s_d".$libro2["id"]."' value='".$libro2["precio"]."'>";
                                    }
                                    if ($presup["descuento"] !="" && $presup["descuento_d"] ==0.00) {

                                        $presup_m = $presup["descuento"] * 100;
                                        echo "<td><input type='text' size='2' name='descuento[]' id='descuento_d".$libro2["id"]."' value='".$presup_m."'> %</td>";

                                    }
                                    elseif( $presup["descuento_d"] !=""){

                                        $presup_m = $presup["descuento_d"] * 100;
                                        echo "<td><input type='text' size='2' name='descuento[]' id='descuento_d".$libro2["id"]."' value='".$presup_m."'> %</td>";

                                    }
                                    else {

                                        echo "<td><input type='text' size='2' name='descuento[]' value='20' id='descuento_d".$libro_p["id"]."' required> %</td>";
                                    }
                                    if ($presup["tasa_compra"] !="" && $presup["tasa_compra_d"] ==0.00) {
                                        $precio_neto= $presup["precio"] -( $presup["precio"] * $presup["descuento"]);

                                        if ($presup["definido"] ==1) {
                                            $venta_p= $precio_neto * floor($gp["alumnos"] * $presup["tasa_compra"]/100);
                                        }else{
                                            $venta_p=0;
                                        }
                                          

                                        $precio_ne=number_format($precio_neto,2,",", ".");

                                        echo "<td id='pn_d".$libro2["id"]."'>".$precio_ne."</td>";

                                        echo "<input type='hidden' id='pn_s_d".$libro2["id"]."' value='".$precio_neto."'>";
                                        if ($presup["definido"] ==1) {
                                            $venta_po=number_format($venta_p,0,",", ".");
                                        }else{
                                            $venta_po=0;
                                        }
                                          

                                        echo"<td id='venta_p_d".$libro2["id"]."' class='venta'>".$venta_po."</td>

                                        <input type='hidden' id='venta_ps_d".$libro2["id"]."' class='venta1_d' value='".$venta_p."'>";
                                    }
                                    elseif($presup["tasa_compra_d"] !=""){

                                        $precio_neto= $presup["precio"] -( $presup["precio"] * $presup["descuento_d"]);

                                        if ($presup["definido"] ==1) {
                                            $venta_p= $precio_neto * floor($gp["alumnos"] * $presup["tasa_compra_d"]/100);
                                        }else{
                                            $venta_p=0;
                                        }

                                        $precio_ne=number_format($precio_neto,2,",", ".");

                                        echo "<td id='pn_d".$libro2["id"]."'>".$precio_ne."</td>";

                                        echo "<input type='hidden' id='pn_s_d".$libro2["id"]."' value='".$precio_neto."'>";
                                        if ($presup["definido"] ==1) {
                                            $venta_po=number_format($venta_p,0,",", ".");
                                        }else{
                                            $venta_po=0;
                                        }
                                         
                                        echo"<td id='venta_p_d".$libro2["id"]."' class='venta'>".$venta_po."</td>

                                        <input type='hidden' id='venta_ps_d".$libro2["id"]."' class='venta1_d' value='".$venta_p."'>";

                                        
                                    }else {

                                        echo "<td id='pn_d".$libro2["id"]."'></td>

                                        <td id='venta_p_d".$libro2["id"]."' class='venta1_d'></td>

                                        <input type='hidden' id='venta_ps_d".$libro2["id"]."' class='venta1_d'>";

                                    }

                                    echo "<td><input type='text' size='6' name='precio_padre[]' id='precio_padre".$libro2["id"]."' value='".$presup["precio_venta_final"]."'></td>";
                                        
                                    if ($presup["tasa_compra"] !=0.00 || $presup["tasa_compra_d"] !=0.00) {
                                        if ($presup["definido"] ==1) {
                                            echo "<td><input type='checkbox' name='definir[]' class='definir' checked value='".$libro2["id"]."/'".$presup["id"]."></td>";
                                        }
	                                    else {

	                                        echo "<td><input type='checkbox' name='definir[]' class='definir' value='".$libro2["id"]."/1".$presup["id"]."'></td>";

	                                    }
                                	}else {
                                    	echo"<td></td>";
                                	}


                                    echo "<input type='hidden' name='presupuesto_d[]' value='".$libro2["id"]."' id='presupuesto_d".$libro2["id"]."'>

                                      <script>

                                        $('#descuento_d".$libro2["id"]."').keyup(function(){
                                            var pvp=parseInt($('#pvp_s_d".$libro2["id"]."').val());

                                            descuento= descuento/100;

                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                            $('#pn_d".$libro2["id"]."').text(formatNumber.new(precio_neto));


                                            var tasa_c=parseInt($('#tasa_d".$libro2["id"]."').val());

                                            tasa_c=tasa_c/100;

                                            var alumnos=parseInt($('#alm_d".$libro2["id"]."').text());

                                            var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                            if(isNaN(vp)){
                                              vp=0
                                            }

                                            $('#venta_p_d".$libro2["id"]."').text(formatNumber.new(vp));

                                            $('#venta_ps_d".$libro2["id"]."').val(vp);
                                            

                                            $('#v_d".$libro2["id"]."').val(vp);


                                            $('#precio_n".$libro2["id"]."').val(precio_neto);
                                            
                                            
                                            var precio_padre=parseInt($('#precio_padre".$libro2["id"]."').val());

                                            $('#presupuesto_d".$libro2["id"]."').val(".$libro2["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+precio_padre);

                                            var total_vp_d=0;

                                            $('.venta1_d').each(function(){

                                              total_vp_d+=parseFloat($(this).val()) || 0;
                                              total_vp_d=Math.round(total_vp_d * 100) / 100;

                                            });
                                                                              
                                            $('#total_vp_d').text(formatNumber.new(total_vp_d));



                                          })

                                          $('#tasa_d".$libro2["id"]."').keyup(function(){
                                            var pvp=parseInt($('#pvp_s_d".$libro2["id"]."').val());

                                            var descuento=parseFloat($('#descuento_d".$libro2["id"]."').val());
                                            descuento= descuento/100;

                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                            $('#pn_d".$libro2["id"]."').text(formatNumber.new(precio_neto));


                                            var tasa_c=parseInt($('#tasa_d".$libro2["id"]."').val());

                                            tasa_c=tasa_c/100;

                                            var alumnos=parseInt($('#alm_d".$libro2["id"]."').text());

                                            var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                            if(isNaN(vp)){
                                              vp=0
                                            }

                                            $('#venta_p_d".$libro2["id"]."').text(formatNumber.new(vp));

                                            $('#venta_ps_d".$libro2["id"]."').val(vp);
                                            

                                            $('#v_d".$libro2["id"]."').val(vp);


                                            $('#precio_n".$libro2["id"]."').val(precio_neto);


                                            var precio_padre=parseInt($('#precio_padre".$libro2["id"]."').val());

                                            $('#presupuesto_d".$libro2["id"]."').val(".$libro2["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+precio_padre);

                                            var total_vp_d=0;

                                            $('.venta1_d').each(function(){

                                              total_vp_d+=parseFloat($(this).val()) || 0;
                                              total_vp_d=Math.round(total_vp_d * 100) / 100;

                                            });
                                                                              
                                            $('#total_vp_d').text(formatNumber.new(total_vp_d));


                                          })

                                          $('#precio_padre".$libro2["id"]."').keyup(function(){
                                            var pvp=parseInt($('#pvp_s_d".$libro2["id"]."').val());

                                            var descuento=parseFloat($('#descuento_d".$libro2["id"]."').val());
                                            descuento= descuento/100;

                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                            $('#pn_d".$libro2["id"]."').text(formatNumber.new(precio_neto));


                                            var tasa_c=parseInt($('#tasa_d".$libro2["id"]."').val());

                                            tasa_c=tasa_c/100;

                                            var alumnos=parseInt($('#alm_d".$libro2["id"]."').text());

                                            var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                            if(isNaN(vp)){
                                              vp=0
                                            }

                                            $('#venta_p_d".$libro2["id"]."').text(formatNumber.new(vp));

                                            $('#venta_ps_d".$libro2["id"]."').val(vp);
                                            

                                            $('#v_d".$libro2["id"]."').val(vp);


                                            $('#precio_n".$libro2["id"]."').val(precio_neto);


                                            var precio_padre=parseInt($('#precio_padre".$libro2["id"]."').val());

                                            $('#presupuesto_d".$libro2["id"]."').val(".$libro2["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+precio_padre);

                                            var total_vp_d=0;

                                            $('.venta1_d').each(function(){

                                              total_vp_d+=parseFloat($(this).val()) || 0;
                                              total_vp_d=Math.round(total_vp_d * 100) / 100;

                                            });
                                                                              
                                            $('#total_vp_d').text(formatNumber.new(total_vp_d));


                                          })
                                      </script>

                                        
                                      </tr>";
                                  }
                                }

                                else {


                                  if ($libro_p["cod_area"] !="") {
                                    $libro_p["id_grado"] = 17;
                                  }

                                  if ($libro_p["id_grado"] != 17) {

                                  $sql_presup = "SELECT id,precio, tasa_compra, descuento, tasa_compra_d, descuento_d, precio_venta_final, definido, uni_vr FROM presupuestos WHERE id_libro='".$libro_p["id"]."' AND id_periodo='".$gp_periodo["id"]."' AND id_colegio='".$_GET["colegio"]."'";

                                  }else{

                                    $sql_presup = "SELECT id,precio, tasa_compra, descuento, tasa_compra_d, descuento_d, precio_venta_final, definido, uni_vr  FROM presupuestos WHERE cod_area='".$libro_p["cod_area"]."' AND id_periodo='".$gp_periodo["id"]."' AND id_colegio='".$_GET["colegio"]."'";

                                  }
                            
                                  $req_presup = $bdd->prepare($sql_presup);
                                  $req_presup->execute();
                                  $presup = $req_presup->fetch();

                                  $lib_id=$libro_p["id"];

                                  if ($libro_p["id_grado"] != 17) {

                                    $sq_gp = "SELECT paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET['colegio']."' AND id_grado='".$libro_p["id_grado"]."' AND id_periodo='".$gp_periodo["id"]."'";

                                  }else {

                                    $libro_100=$libro_p["id"];

                                    $libro_p["id"]=$libro_p["cod_area"];

                                    $sql_go = "SELECT id_grado_otro FROM areas_objetivas WHERE codigo='".$libro_p["cod_area"]."'";


                                    $req_go = $bdd->prepare($sql_go);
                                    $req_go->execute();
                                    $go = $req_go->fetch();

                                    $sq_gp = "SELECT paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET['colegio']."' AND id_grado='".$go["id_grado_otro"]."' AND id_periodo='".$gp_periodo["id"]."'";
                                  }

                            
                                    $req_gp = $bdd->prepare($sq_gp);
                                    $req_gp->execute();
                                    $gp = $req_gp->fetch();

                                  echo "<tr>
                                      <td>".$libro_p["libro"]."</td>
                                      <td>".$libro_p["materia"]."</td>";

                                    if ($libro_p["id_grado"] != 17) {
                                        echo "<td>".$libro_p["grado"]."</td>";
                                      
                                    }else {

                                      $sql_otrg = "SELECT g.grado FROM grados g JOIN areas_objetivas a ON g.id=a.id_grado_otro WHERE a.codigo='".$libro_p["cod_area"]."'";

                                      $req_otrg = $bdd->prepare($sql_otrg);
                                      $req_otrg->execute();
                                      $otrg = $req_otrg->fetch();

                                      echo "<td>".$otrg["grado"]."</td>";
                                    }
                                      
                                      echo"<!--<td>".$gp["paralelos"]."</td>-->
                                      <td id='alm_d".$libro_p["id"]."'>".$gp["alumnos"]."</td>";
                                      if ($presup["tasa_compra"] !="" && $presup["tasa_compra_d"] ==0.00 ) {

                                          $presup["tasa_compra"] = $presup["tasa_compra"] *100;
                                          echo "<td><input type='text' size='2' name='tasa[]' id='tasa_d".$libro_p["id"]."' value='".$presup["tasa_compra"]."'> %</td>";
                                        }
                                        elseif( $presup["tasa_compra_d"] !=""){

                                          $presup["tasa_compra_d"] = $presup["tasa_compra_d"] *100;
                                          echo "<td><input type='text' size='2' name='tasa[]' id='tasa_d".$libro_p["id"]."' value='".$presup["tasa_compra_d"]."'> %</td>";

                                        }
                                        else {
                                          echo "<td><input type='text' size='2' name='tasa[]' id='tasa_d".$libro_p["id"]."' value='0' required> %</td>";

                                        }
                                        if ($presup["precio"] !=0) {

                                          $precio=number_format($presup["precio"],0,",", ".");

                                          echo "<td id='pvp_d".$libro_p["id"]."'>".$precio."</td>";

                                          echo "<input type='hidden' id='pvp_s_d".$libro_p["id"]."' value='".$presup["precio"]."'>";
                                        }else {
                                          $precio=number_format($libro_p["precio"],0,",", ".");

                                          echo "<td id='pvp_d".$libro_p["id"]."'>".$precio."</td>";

                                          echo "<input type='hidden' id='pvp_s_d".$libro_p["id"]."' value='".$libro_p["precio"]."'>";
                                        }
                                      if ($presup["descuento"] !="" && $presup["descuento_d"] ==0.00) {

                                          $presup_m = $presup["descuento"] * 100;
                                          echo "<td><input type='text' size='2' name='descuento[]' id='descuento_d".$libro_p["id"]."' value='".$presup_m."'> %</td>";

                                        }
                                        elseif( $presup["descuento_d"] !=""){

                                          $presup_m = $presup["descuento_d"] * 100;
                                          echo "<td><input type='text' size='2' name='descuento[]' id='descuento_d".$libro_p["id"]."' value='".$presup_m."'> %</td>";

                                        }
                                        else {

                                          echo "<td><input type='text' size='2' name='descuento[]' value='20' id='descuento_d".$libro_p["id"]."' required> %</td>";
                                        }
                                        if ($presup["tasa_compra"] !="" && $presup["tasa_compra_d"] ==0.00) {
                                          $precio_neto= $presup["precio"] -( $presup["precio"] * $presup["descuento"]);

                                          if ($presup["definido"]==1) {
                                            $venta_p= $precio_neto * floor($gp["alumnos"] * $presup["tasa_compra"]/100);
                                          }else{
                                            $venta_p=0;
                                          }
                                          

                                          $precio_ne=number_format($precio_neto,2,",", ".");

                                          echo "<td id='pn_d".$libro_p["id"]."'>".$precio_ne."</td>";

                                          echo "<input type='hidden' id='pn_s_d".$libro_p["id"]."' value='".$precio_neto."'>";
                                          if ($presup["definido"]==1) {
                                            $venta_po=number_format($venta_p,0,",", ".");
                                          }else{
                                            $venta_po=0;              
                                          }

                                          echo"<td id='venta_p_d".$libro_p["id"]."' class='venta'>".$venta_po."</td>

                                          <input type='hidden' id='venta_ps_d".$libro_p["id"]."' class='venta1_d' value='".$venta_p."'>";

                                        }
                                        elseif( $presup["tasa_compra_d"] !=""){

                                          $precio_neto= $presup["precio"] -( $presup["precio"] * $presup["descuento_d"]);
                                          if ($presup["definido"]==1) {
                                            $venta_p= $precio_neto * floor($gp["alumnos"] * $presup["tasa_compra_d"]/100);
                                          }else{
                                             $venta_p= 0;
                                          }
                                          

                                          $precio_ne=number_format($precio_neto,2,",", ".");

                                          echo "<td id='pn_d".$libro_p["id"]."'>".$precio_ne."</td>";

                                          echo "<input type='hidden' id='pn_s_d".$libro_p["id"]."' value='".$precio_neto."'>";
                                          if ($presup["definido"]==1) {
                                            $venta_po=number_format($venta_p,0,",", ".");
                                          }else{
                                            $venta_po=0;
                                          }

                                          echo"<td id='venta_p_d".$libro_p["id"]."' class='venta'>".$venta_po."</td>

                                          <input type='hidden' id='venta_ps_d".$libro_p["id"]."' class='venta1_d' value='".$venta_p."'>";

                                        }

                                          else {

                                          echo "<td id='pn_d".$libro_p["id"]."'></td>

                                          <td id='venta_p_d".$libro_p["id"]."' class='venta'></td>

                                          <input type='hidden' id='venta_ps_d".$libro_p["id"]."' class='venta1_d'>";

                                        }


                                          echo "<td><input type='text' size='6' name='precio_padre[]' id='precio_padre".$libro_p["id"]."' value='".$presup["precio_venta_final"]."'></td>";

                                          if ($presup["tasa_compra"] !=0.00 || $presup["tasa_compra_d"] !=0.00) {
                                            if ($presup["definido"] ==1) {
                                              echo "<td><input type='checkbox' name='definir[]' class='definir' checked value='".$libro_p["id"]."/".$presup["id"]."'></td>";
                                            }
                                            else {

                                              echo "<td><input type='checkbox' name='definir[]' class='definir' value='".$libro_p["id"]."/".$presup["id"]."'></td>";

                                            }
                                          }else {
                                            echo"<td></td>";
                                          }

                                          if ($presup["definido"] ==1) {

                                            if ($presup["uni_vr"] !=0 ) {
                                              echo "<td><input type='text' size='2' name='uni_vr[]' id='uni_vr".$libro_p["id"]."' value='".$presup["uni_vr"]."'></td>";

                                              $venta_r= $precio_neto * $presup["uni_vr"];
                                            
                                              $venta_ro=number_format($venta_r,0,",", ".");
                                           
                                              echo"<td id='venta_r".$libro_p["id"]."' class='venta'>".$venta_ro."</td>
                                              <input type='hidden' id='i_uni_vr".$libro_p["id"]."' class='uni_vr_d' value='".$venta_r."'>";

                                              
                                            }else{
                                              echo "<td><input type='text' size='2' name='uni_vr[]' id='uni_vr".$libro_p["id"]."'></td>
                                              <input type='hidden' id='i_uni_vr".$libro_p["id"]."' class='uni_vr_d'>";

                                              echo"<td id='venta_r".$libro_p["id"]."' class='venta'></td>";
                                             
                                            }
                                                                               
                                            



                                          }else{
                                            echo "<td></td>";
                                            echo "<td></td>";
                                          }
                                          



                                      echo "<input type='hidden' name='presupuesto_d[]' value='".$libro_p["id"]."' id='presupuesto_d".$libro_p["id"]."'>

                                            <input type='hidden' name='v_uni_vr[]' id='v_uni_vr".$libro_p["id"]."'>
                                      <input type='hidden' name='presup_profes[]' value='".$presup["id"]."'>


                                      <script>
                                        $('#descuento_d".$libro_p["id"]."').keyup(function(){
                                            var pvp=parseInt($('#pvp_s_d".$libro_p["id"]."').val());

                                            var descuento=parseFloat($('#descuento_d".$libro_p["id"]."').val());";


                                            if ($_SESSION['tipo']!=6) {
	                                    		echo "var desc_max=parseFloat(".$libro_p["desc_max"].")* 100;";
			                                }else{
			                                        	echo "var desc_max=parseFloat(".$libro_p["desc_max_dist"].")* 100;";
			                                }


		                                    if (isset($libro_100)) {
		                                        if ($libro_100 !=3481 && $libro_100 !=3482) {
			                                        echo "if (descuento > 69){
			                                            alert('el descuento no debe superar el 69%');
			                                            $('#descuento_d".$libro_p["id"]."').val('20');
			                                            $('#descuento_d".$libro_p["id"]."').focus();
			                                            descuento=20;
			                                        }
			                                        ";


			                                    }
		                                    }else{
		                                    	echo "if (descuento > 69){
			                                        alert('el descuento no debe superar el 69%');
			                                        $('#descuento_d".$libro_p["id"]."').val('20');
			                                        $('#descuento_d".$libro_p["id"]."').focus();
			                                        descuento=20;
			                                    }
			                                    ";
		                                    }
                                            

		                                    echo"

		                                    if (desc_max > 0){
		                                    	if (descuento > desc_max){

				                                    alert('el descuento no debe superar: '+desc_max);
				                                    $('#descuento_d".$libro_p["id"]."').val(desc_max);
				                                    $('#descuento_d".$libro_p["id"]."').focus();
				                                    descuento=desc_max;
			                                	}
		                                    }

                                            descuento= descuento/100;



                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                            $('#pn_d".$libro_p["id"]."').text(formatNumber.new(precio_neto));


                                            var tasa_c=parseInt($('#tasa_d".$libro_p["id"]."').val());

                                            tasa_c=tasa_c/100;

                                            var alumnos=parseInt($('#alm_d".$libro_p["id"]."').text());

                                            var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                            if(isNaN(vp)){
                                              vp=0;
                                            }

                                            $('#venta_p_d".$libro_p["id"]."').text(formatNumber.new(vp));

                                            $('#venta_ps_d".$libro_p["id"]."').val(vp);

                                            var precio_padre=parseInt($('#precio_padre".$libro_p["id"]."').val());


                                            $('#presupuesto_d".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+precio_padre);

                                            var total_vp_d=0;


                                            $('.venta1_d').each(function(){

                                              total_vp_d+=parseFloat($(this).val()) || 0;
                                              total_vp_d=Math.round(total_vp_d * 100) / 100;

                                            });
                                                                              
                                            $('#total_vp_d').text(formatNumber.new(total_vp_d));

                                          })

                                          $('#tasa_d".$libro_p["id"]."').keyup(function(){
                                            var pvp=parseInt($('#pvp_s_d".$libro_p["id"]."').val());

                                            var descuento=parseFloat($('#descuento_d".$libro_p["id"]."').val());
                                            descuento= descuento/100;

                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                            $('#pn_d".$libro_p["id"]."').text(formatNumber.new(precio_neto));


                                            var tasa_c=parseInt($('#tasa_d".$libro_p["id"]."').val());

                                            tasa_c=tasa_c/100;

                                            var alumnos=parseInt($('#alm_d".$libro_p["id"]."').text());

                                            var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                            if(isNaN(vp)){
                                              vp=0;
                                            }

                                            $('#venta_p_d".$libro_p["id"]."').text(formatNumber.new(vp));

                                            $('#venta_ps_d".$libro_p["id"]."').val(vp);


                                            var precio_padre=parseInt($('#precio_padre".$libro_p["id"]."').val());


                                            $('#presupuesto_d".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+precio_padre);


                                            var total_vp_d=0;

                                            $('.venta1_d').each(function(){

                                              total_vp_d+=parseFloat($(this).val()) || 0;
                                              total_vp_d=Math.round(total_vp_d * 100) / 100;

                                            });
                                                                              
                                            $('#total_vp_d').text(formatNumber.new(total_vp_d));

                                          })

                                          $('#precio_padre".$libro_p["id"]."').keyup(function(){
                                            var pvp=parseInt($('#pvp_s_d".$libro_p["id"]."').val());

                                            var descuento=parseFloat($('#descuento_d".$libro_p["id"]."').val());
                                            descuento= descuento/100;

                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                            $('#pn_d".$libro_p["id"]."').text(formatNumber.new(precio_neto));


                                            var tasa_c=parseInt($('#tasa_d".$libro_p["id"]."').val());

                                            tasa_c=tasa_c/100;

                                            var alumnos=parseInt($('#alm_d".$libro_p["id"]."').text());

                                            var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                            if(isNaN(vp)){
                                              vp=0;
                                            }

                                            $('#venta_p_d".$libro_p["id"]."').text(formatNumber.new(vp));

                                            $('#venta_ps_d".$libro_p["id"]."').val(vp);


                                            var precio_padre=parseInt($('#precio_padre".$libro_p["id"]."').val());


                                            $('#presupuesto_d".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+precio_padre);


                                            var total_vp_d=0;

                                            $('.venta1_d').each(function(){

                                              total_vp_d+=parseFloat($(this).val()) || 0;
                                              total_vp_d=Math.round(total_vp_d * 100) / 100;

                                            });
                                                                              
                                            $('#total_vp_d').text(formatNumber.new(total_vp_d));

                                          })

                                          $('#uni_vr".$libro_p["id"]."').keyup(function(){
                                            
                                            var pvp=parseInt($('#pvp_s_d".$libro_p["id"]."').val());

                                            var descuento=parseFloat($('#descuento_d".$libro_p["id"]."').val());

                                            descuento= descuento/100;

                                            var precio_neto= pvp - (pvp * descuento);

                                            if(isNaN(precio_neto)){
                                              precio_neto=0
                                            }

                                           

                                            var uni_vr=parseInt($('#uni_vr".$libro_p["id"]."').val());

                                            var vr= precio_neto * uni_vr

                                            if(isNaN(vr)){
                                              vr=0;
                                            }



                                            $('#venta_r".$libro_p["id"]."').text(formatNumber.new(vr));

                                            $('#i_uni_vr".$libro_p["id"]."').val(vr);


                                            $('#v_uni_vr".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+uni_vr);


                                          

                                            total_uni_vr_d=0;

                                            $('.uni_vr_d').each(function(){

                                              total_uni_vr_d+=parseFloat($(this).val()) || 0;

                                              total_uni_vr_d=Math.round(total_uni_vr_d * 100) / 100;
                                              

                                            });

                                            $('#total_vr').text(formatNumber.new(total_uni_vr_d));

                                            var cumplimiento=total_uni_vr_d / total_vp_d;

                                            $('#cumplimiento').text(cumplimiento.toFixed(2));


                                          })

                                         


                                      </script>

                                      
                                    </tr>";

                                }


                            if($_SESSION["tipo"] !=2 ) { 
                                /*if ($_GET["f_cierre"] > date("Y-m-d")){
                                echo'<center><button class="btn btn-primary">Actualizar</button></center>
                                <input type="hidden" name="id_colegio" id="cole" value="'.$_GET["colegio"].'">
                              <input type="hidden" name="cod_colegio" value="'.$_GET["codigo"].'">
                              <input type="hidden" name="id_area" value="'.$area["aid"].'"> 
                                </form></div>';
                              }*/
                            }
                              }
                              echo "<tr>
                                <td><b>Total:</b></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id='total_vp_d'></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id='total_vr'></td>
                                </tr>";

                              echo "<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b> % de Cumplimiento</b></td>
                                <td id='cumplimiento'></td>
                                </tr>";

                              echo '</tbody>
                                  </table></div>
                                   <input type="hidden" name="id_colegio" id="cole" value="'.$_GET["colegio"].'">
                                    <input type="hidden" name="codigo" value="'.$_GET["codigo"].'">
                                    <input type="hidden" name="periodo" value="'.$gp_periodo["id"].'">';
                                    
                                    //adopcion solo admin

                                    /*$sql_periodo1="SELECT id FROM periodos ORDER BY id DESC  limit 1;";

                                  $req_periodo1 = $bdd->prepare($sql_periodo1);
                                  $req_periodo1->execute();
                                  $u_periodo = $req_periodo1->fetch();*/                           

                          $sql_rec = "SELECT * FROM recursos WHERE id_periodo='".$gp_periodo["id"]."' AND id_colegio='".$_GET["colegio"]."'";
                            
                        $req_rec = $bdd->prepare($sql_rec);
                          $req_rec->execute();
                          $recursos = $req_rec->fetch();
                          $count = $req_rec->rowCount();
                          if ($count < 1) {
                            echo '<div class="row">
        ';
                                    echo'<br><br><div class="col-sm-4">
                                        <div class="form-group">
                                          <label class="control-label no-padding-right" for="canal"> Canal de venta</label>
              
                                          <select name="canal" id="canal" class="form-control materia" >
                                          <option value="">Seleccionar</option>';
                         
                                        $sql = "SELECT id, canal_venta FROM canales_venta";
                            
                                        $req = $bdd->prepare($sql);
                                        $req->execute();
                                        $canales = $req->fetchAll();
                            
                                        foreach($canales as $canal) {
                                            $id = $canal['id'];
                                            $nom = $canal['canal_venta'];
                                            echo '<option value="'.$id.'">'.$nom.'</option>';
                                        }
                        
                                        echo'</select>
                                          
                                      </div>
                                    </div>
                                  </div>';

                                  echo'<br><br><div class="form-group">
                                        <label class="control-label no-padding-right" for="observaciones"> Observaciones:</label>

                                      <textarea class="form-control" name="observaciones" rows="2" cols="2" placeholder="En la papeleria se deja la mercancia para venta y alla suben $2.000 por libro, una vez se haga la venta se reintegrara alaempresa el valor correspondiente al excedente del 10% aprobado como recurso"></textarea>
                    
                                      </div>';
                          }else {

                            echo '<div class="row">';
                                    echo'<br><br><div class="col-sm-4">
                                        <div class="form-group">
                                          <label class="control-label no-padding-right" for="canal"> Canal de venta</label>
              
                                          <select name="canal" id="canal" class="form-control materia" >
                                          <option value="">Seleccionar</option>';
                         
                                        $sql = "SELECT id, canal_venta FROM canales_venta";
                            
                                        $req = $bdd->prepare($sql);
                                        $req->execute();
                                        $canales = $req->fetchAll();
                            
                                        foreach($canales as $canal) {


                                            $id = $canal['id'];
                                            $nom = $canal['canal_venta'];
                                            if ($recursos["id_canal"]==$id) {
                                            echo '<option value="'.$id.'" SELECTED>'.$nom.'</option>';
                                          }else {
                                            echo '<option value="'.$id.'">'.$nom.'</option>';
                                          }
                                            
                                        }
                        
                                        echo'</select>
                                          
                                      </div>
                                    </div>
                                  </div>';

                               

                                  echo'<br><br><div class="form-group">
                                        <label class="control-label no-padding-right" for="observaciones"> Observaciones:</label>

                                      <textarea class="form-control" name="observaciones" rows="2" cols="2" placeholder="En la papeleria se deja la mercancia para venta y alla suben $2.000 por libro, una vez se haga la venta se reintegrara alaempresa el valor correspondiente al excedente del 10% aprobado como recurso">'.$recursos["observaciones"].'</textarea>
                    
                                      </div>';

                          }
                                  if ($_SESSION["tipo"]==1) {

                                    echo '<div class="row">
                                      <div class="form-group col-sm-4">
                                        <label class="control-label no-padding-right" for="observaciones"> Venta real:</label>
                                        <input type="text" name="venta_real" id="venta_real" class="form-control" value="'.$recursos["venta_real"].'"/>
                                      </div>
                                    </div>';


                                  }  
                                  

                                    if ($num_hp < 1) {
                                      
                                    }
                                    else {
                                      
                                        echo '<a class="btn btn-success float-right" href="php/adopcion_excel.php?cole='.$_GET['colegio'].'&periodo='.$_GET["periodo"].'">Exportar Excel</a><br>';
                              //adopcion solo admin
                            if($_SESSION["tipo"] !=2 && $_SESSION["tipo"] != 4) { 
                              //if ($_SESSION["tipo"]==1 || $gp_periodo["id"] == $u_periodo["id"]) {

                                        if ($_GET["f_cierre"] > date("Y-m-d")) {    
                                          //echo '<center><button class="btn btn-primary">Guardar</button></center>';
                                          echo '<center><button class="btn btn-primary miBoton">Guardar</button></center>';
                                        }
                                //}

                                
                            }     
                                      
                                      
                                    }
                                    echo '</form>';
                                  
                           ?>
	
</div>
<script src="../vendors/scripts/core.js"></script>
<script>
	//libros definicion

    $('#gradod').on('change',function(){
        var valor = $(this).val();
        var materia=$("#materiad").val();
        //alert(valor);

        if (valor==17) {
            $(".g_otrod").removeClass("d-none");
            $(".g_otrod").addClass("show");
            $("#grado_otrod").attr("required","required");
                 
        }else {
            $(".g_otrod").addClass("d-none");
            $(".g_otrod").removeClass("show");
            $("#grado_otrod").removeAttr("required");
        }
        var dataString = 'mat_gra='+materia+"/"+valor;
            $.ajax({

                url: "ajax/buscar_l_eureka_p.php",
                type: "POST",
                data: dataString,
                success: function (resp) {
                 
                    $("#libro_ed").html(resp);                        
                    //console.log(resp);
                    if(valor =="") {
                        $("#libro_ed").html("");
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

    $('#materiad').on('change',function(){
        var valor = $(this).val();
        var grado = $("#gradod").val()
        //alert(valor);
        var dataString = 'mat_gra='+valor+'/'+grado;
              
        $.ajax({

            url: "ajax/buscar_l_eureka_p.php",
            type: "POST",
            data: dataString,
            dataType: "html",
            success: function (resp) {
                 
                $("#libro_ed").html(resp);                        
                if(valor =="") {
                    $("#libro_ed").html("");
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

    $('#libro_ed').on('change',function(){
        $value=$("#materiad").val()+"/"+$("#gradod").val()+"/"+$(this).val()+"/"+$("#grado_otrod").val();
        $("#libs_aod").val($value);
                              
    });

    var m = 1;
    $("#agregar_aod").click(function(){

      if (m>98) {
        $("#agregar_aod").addClass("d-none");
    }

    $("#agg_aod"+m).removeClass("d-none");
      

    m++;

    <?php for ($i=1; $i < 100; $i++) { ?>

        $('#gradod<?php echo $i; ?>').on('change',function(){
            var valor = $(this).val();
            var materia=$("#materiad<?php echo $i; ?>").val();
            //alert(valor);
            if (valor==17) {
                $(".g_otrod<?php echo $i; ?>").removeClass("d-none");
                $(".g_otrod<?php echo $i; ?>").addClass("show");
                $("#grado_otrod<?php echo $i; ?>").attr("required","required");
                     
            }else {
                $(".g_otrod<?php echo $i; ?>").addClass("d-none");
                $(".g_otrod<?php echo $i; ?>").removeClass("show");
                $("#grado_otrod<?php echo $i; ?>").removeAttr("required");
            }
            var dataString = 'mat_gra='+materia+"/"+valor;
            $.ajax({

                url: "ajax/buscar_l_eureka_p.php",
                type: "POST",
                data: dataString,
                success: function (resp) {
                     
                    $("#libro_ed<?php echo $i; ?>").html(resp);                        
                    console.log(resp);
                    if(valor =="") {
                        $("#libro_ed<?php echo $i; ?>").html("");
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

        $('#materiad<?php echo $i; ?>').on('change',function(){
          var valor = $(this).val();
          var grado = $("#gradod<?php echo $i; ?>").val()
          //alert(valor);
          var dataString = 'mat_gra='+valor+'/'+grado;
                
          $.ajax({

            url: "ajax/buscar_l_eureka_p.php",
            type: "POST",
            data: dataString,
            dataType: "html",
            success: function (resp) {
                    
              $("#libro_ed<?php echo $i; ?>").html(resp);                        
              if(valor =="") {
                $("#libro_ed<?php echo $i; ?>").html("");
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

        $('#libro_ed<?php echo $i; ?>').on('change',function(){
          $value=$("#materiad<?php echo $i; ?>").val()+"/"+$("#gradod<?php echo $i; ?>").val()+"/"+$(this).val()+"/"+$("#grado_otrod<?php echo $i; ?>").val();
          $("#libs_aod<?php echo $i; ?>").val($value);
                   
        });

      <?php } ?>
 
      
    });

    var formatNumber = {
        separador: ".", // separador para los miles
        sepDecimal: ',', // separador para los decimales
        formatear:function (num){
            num +='';
            var splitStr = num.split('.');
            var splitLeft = splitStr[0];
            var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
            var regx = /(\d+)(\d{3})/;
            while (regx.test(splitLeft)) {
                splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
            }
            return this.simbol + splitLeft +splitRight;
        },
        new:function(num, simbol){
            this.simbol = simbol ||'';
            return this.formatear(num);
        }
    }

    //seleccionar todo para aprobar
    $('#seleccionar_pre').click(function(){
        if( $('#seleccionar_pre').is(':checked') ) {
            for (i=0;i<document.f2.elements.length;i++)
            if(document.f2.elements[i].type == "checkbox")
                 document.f2.elements[i].checked=1 
        }else{
          
          for (i=0;i<document.f2.elements.length;i++)
          	if(document.f2.elements[i].type == "checkbox")
                document.f2.elements[i].checked=0 

        }
    })

    var total_vp_d=0;

     $('.venta1_d').each(function(){

        total_vp_d+=parseFloat($(this).val()) || 0;
        total_vp_d=Math.round(total_vp_d * 100) / 100;

     });
                                        
    $('#total_vp_d').text(formatNumber.new(total_vp_d));

    	total_uni_vr_d=0;

      	$('.uni_vr_d').each(function(){

        total_uni_vr_d+=parseFloat($(this).val()) || 0;

        total_uni_vr_d=Math.round(total_uni_vr_d * 100) / 100;
                                              

    });

    $('#total_vr').text(formatNumber.new(total_uni_vr_d));

   	var cumplimiento=total_uni_vr_d / total_vp_d;

    $('#cumplimiento').text(cumplimiento.toFixed(2));

</script>