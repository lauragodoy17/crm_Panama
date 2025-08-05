<?php
	
	ini_set("display_errors", 1);

ini_set("display_startup_errors", 1);

error_reporting(E_ALL);

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
	<a href="#" class="btn btn-info" data-toggle="modal" data-target="#modal_presupuesto" type="button">Añadir libros</a><br><br>

	<div class="modal fade bs-example-modal-xl" id="modal_presupuesto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                <form action="php/areas_objetivas.php" method="POST" class="miFormulario">
                <div class="modal-body">
                               
				<div class="otra_ao">
						                          
					<center><h4>Añadir nuevo</h4></center><br>
					<h4>Libro #1:</h4>
					<div class="row">
						<div class="col-sm-3">
						    <div class="form-group">
							    <label id="l_materia" class="control-label no-padding-right" for="materia"> Materia:<small style="color:red;"> *</small></label>
								<select name="materia" id="materia" class="form-control materia">
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
							    <label for="grado" id="l_grado" class="control-label no-padding-right">Grado<small style="color:red;"> *</small></label>
							    <select name="grado" required id="grado" class="form-control grado">
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
						        <label for="grado_otro" id="l_grado_otro" class="control-label no-padding-right d-none g_otro">Grado otro<small style="color:red;"> *</small></label>
						        <select name="grado_otro" id="grado_otro" class="form-control g_otro d-none">
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
						        <label  for="libro_e" id="l_libro_e" class="control-label no-padding-right">Libro<small style="color:red;"> *</small></label>
						        <select name="libro_e" id="libro_e" class="form-control grado custom-select2" required></select>
						    </div>
						</div>
					</div>
						                                                        
					<input type="hidden" name="libs_ao[]" id="libs_ao">

					<?php for ($i=1; $i < 100; $i++) { ?>

						<div id="agg_ao<?php echo $i;?>" class="d-none">

							<h4>Libro #<?php echo $i+1;?>:</h4>
							<div class="row">
							    <div class="col-sm-3">
							        <div class="form-group">
							        	<label id="l_materia<?php echo $i;?>" class="control-label no-padding-right" for="materia<?php echo $i;?>"> Materia:<small style="color:red;"> *</small></label>

							            <select name="materia1" id="materia<?php echo $i;?>" class="form-control materia">
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
							            <label for="grado<?php echo $i;?>" id="l_grado<?php echo $i;?>" class="control-label no-padding-right">Grado<small style="color:red;"> *</small></label>
							                                    
							            <select name="grado1" id="grado<?php echo $i;?>" class="form-control grado">
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
								        <label for="grado_otro<?php echo $i;?>" id="l_grado_otro<?php echo $i;?>" class="control-label no-padding-right d-none g_otro<?php echo $i;?>">Grado otro<small style="color:red;"> *</small></label>
								                                    
								        <select name="grado_otro" id="grado_otro<?php echo $i;?>" class="form-control g_otro<?php echo $i;?> d-none">
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
									    <label  for="libro_e<?php echo $i;?>" id="l_libro_e<?php echo $i;?>" class="control-label no-padding-right">Libro<small style="color:red;"> *</small></label>
									                                      
									    <select name="libro_e" id="libro_e<?php echo $i;?>" class="form-control grado custom-select2"></select>
								    </div>

								</div>


							</div>
							                                                        
							<input type="hidden" name="libs_ao[]" id="libs_ao<?php echo $i;?>">

						</div>

					<?php } ?>

						                                  
					<input type="hidden" name="promotor" id="promotor" value="<?php echo $_GET['promotor'] ?>">
					<input type="hidden" name="id_colegio" id="cole" value="<?php echo $_GET['colegio'] ?>">
					<input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
					<input type="hidden" name="cod_zona" value="<?php echo $_GET['cod_zona'] ?>">
					<input type="hidden" name="sub_zona" value="<?php echo $_GET['sub_zona'] ?>">
					<input type="hidden" name="responsable" value="<?php echo $_GET['responsable'] ?>">
					<input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
				</div>
				<?php if($_SESSION["tipo"] !=2 && $_SESSION["tipo"] != 4) { ?>
					<?php if($_SESSION["zona"] ==$_GET['cod_zona'] || $_SESSION["tipo"] == 1) { ?>
						<?php if ($_GET["f_cierre"] > date("Y-m-d")) { ?>
						    <a id="agregar_ao" style="cursor: pointer;">Agregar Otro +</a>
						    <br><br><center><button class="btn btn-success miBoton">Guardar</button></center>
						<?php  } ?>
					<?php  } ?>
				<?php  } ?>

    			</form>       
            </div>
            <div class="modal-footer">
            
            </div>

         </div>

    </div>

	</div>

     <?php
                            
        $sql = "SELECT p.id as pid, p.cod_area, b.materia, c.grado,l.id, l.libro,l.id_materia, l.id_grado, l.pri_sec, l.precio, desc_max, desc_max_dist FROM presupuestos p JOIN libros l ON p.id_libro=l.id JOIN materias b ON l.id_materia=b.id JOIN grados c ON l.id_grado=c.id WHERE id_colegio='".$_GET["colegio"]."' AND id_periodo='".$_GET["periodo"]."' AND p.pre_aprob=1";
                            
        $req = $bdd->prepare($sql);
        $req->execute();
        $libros_p = $req->fetchAll();

		$sql_hp = "SELECT id FROM presupuestos WHERE id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["colegio"]."'";

        $req_hp = $bdd->prepare($sql_hp);
        $req_hp->execute();
        $num_hp= $req_hp->rowCount();

       
        echo "<form action='php/actualizar_presupuesto.php' method='POST' id='pp' class='miFormulario'>";
                                                                
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
                    <th>Probabilidad</th>
                    <th>Borrar</th>";
                                      
                     /*if ($_SESSION
                        ["tipo"] != 1) {
                                        
                         	echo "<th>Aprobado</th>";

                        }else{
                            echo "<th>Aprobado <input type='checkbox' id='seleccionar_pre'></th>";
                     }*/

                                      
                echo"</thead>
                <tbody>";
                 foreach ($libros_p as $libro_p) {

                    if ($libro_p["cod_area"] !="") {
                        $libro_p["id_grado"] = 17;
                    }

                    if ($libro_p["id_grado"] != 17) {
                        $sql_presup = "SELECT id, precio, tasa_compra, descuento, pre_aprob, aprobado, probabilidad FROM presupuestos WHERE id_libro='".$libro_p["id"]."' AND id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["colegio"]."'";
                            
                        $req_presup = $bdd->prepare($sql_presup);
                        $req_presup->execute();
                        $presup = $req_presup->fetch();
                    }else {
                        $sql_presup = "SELECT id, precio, tasa_compra, descuento, pre_aprob, aprobado, probabilidad FROM presupuestos WHERE cod_area='".$libro_p["cod_area"]."' AND id_periodo='".$_GET["periodo"]."' AND id_colegio='".$_GET["colegio"]."'";
                            
                        $req_presup = $bdd->prepare($sql_presup);
                        $req_presup->execute();
                        $presup = $req_presup->fetch();

                    }
                       
                    if ($libro_p["id_grado"] != 17) {
                                  
                        $sq_gp = "SELECT paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET['colegio']."' AND id_grado='".$libro_p["id_grado"]."' AND id_periodo='".$_GET["periodo"]."'";
                    }
                    else {

                      	$sql_go = "SELECT id_grado_otro FROM areas_objetivas WHERE codigo='".$libro_p["cod_area"]."'";

                        $req_go = $bdd->prepare($sql_go);
                        $req_go->execute();
                        $go = $req_go->fetch();

                       $sq_gp = "SELECT paralelos, SUM(alumnos) as alumnos FROM grados_paralelos WHERE id_colegio='".$_GET['colegio']."' AND id_grado='".$go["id_grado_otro"]."' AND id_periodo='".$_GET["periodo"]."'";
                                                                            
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

                            $libro_101=$libro_p["id"];
                            $libro_p["id"]=$libro_p["cod_area"];

                            $sql_otrg = "SELECT g.grado FROM grados g JOIN areas_objetivas a ON g.id=a.id_grado_otro WHERE a.codigo='".$libro_p["cod_area"]."'";

                            $req_otrg = $bdd->prepare($sql_otrg);
                            $req_otrg->execute();
                            $otrg = $req_otrg->fetch();

                            echo "<td>".$otrg["grado"]."</td>";
                        }
                                    
                        echo"<!--<td>".$gp["paralelos"]."</td>-->
                        <td id='alm_p".$libro_p["id"]."'>".$gp["alumnos"]."</td>";
                        if ($presup["tasa_compra"] !=0.00) {

                            $presup["tasa_compra"] = $presup["tasa_compra"] *100;
                            echo "<td><input type='text' size='2' name='tasa[]' id='tasa_p".$libro_p["id"]."' value='".$presup["tasa_compra"]."'> %</td>";
                        }else {
                                        
                            //tasa de compra nueva
                            if ($libro_p["id_grado"] < 4) {
                                echo "<td><input type='text' size='2' name='tasa[]' id='tasa_p".$libro_p["id"]."' value='".$gp_periodo["t_preescolar"]."' required> %</td>";
                            }elseif ($libro_p["id_grado"] < 9 && $libro_p["id_grado"] > 3) {
                                echo "<td><input type='text' size='2' name='tasa[]' id='tasa_p".$libro_p["id"]."' value='".$gp_periodo["t_primaria"]."' required> %</td>";
                            }elseif ($libro_p["id_grado"] > 8 && $libro_p["id_grado"] < 13) {
                                echo "<td><input type='text' size='2' name='tasa[]' id='tasa_p".$libro_p["id"]."' value='".$gp_periodo["t_6_9"]."' required> %</td>";
                            }else{
                                echo "<td><input type='text' size='2' name='tasa[]' id='tasa_p".$libro_p["id"]."' value='".$gp_periodo["t_10_11"]."' required> %</td>";
                            }
                                          
                        }
                        if ($presup["precio"] !="" && $presup["precio"] !=0) {

                            $precio=number_format($presup["precio"],0,",", ".");
                            echo "<td id='pvp_p".$libro_p["id"]."'>".$precio."</td>";
                            echo "<input type='hidden' id='pvp_s_p".$libro_p["id"]."' value='".$presup["precio"]."'>";
                        }else {
                            $precio=number_format($libro_p["precio"],0,",", ".");
                                echo "<td id='pvp_p".$libro_p["id"]."'>".$precio."</td>";
                                echo "<input type='hidden' id='pvp_s_p".$libro_p["id"]."' value='".$libro_p["precio"]."'>";
                        }
                        if ($presup["descuento"] !="") {
                            $presup_m = $presup["descuento"] * 100;
                            echo "<td><input type='text' size='2' name='descuento[]' id='descuento_p".$libro_p["id"]."' value='".$presup_m."'> %</td>";
							}else {
                                echo "<td><input type='text' size='2' name='descuento[]' value='20' id='descuento_p".$libro_p["id"]."' required> %</td>";
                            }
                            if ($presup["tasa_compra"] !="") {
                                $precio_neto= $libro_p["precio"] -( $libro_p["precio"] * $presup["descuento"]);
                                if ($presup["probabilidad"] != 3) {
                                    $venta_p= $precio_neto * floor($gp["alumnos"] * $presup["tasa_compra"]/100);
                                }else {
                                    $venta_p=0;
                                }
                                $precio_ne=number_format($precio_neto,2,",", ".");
                                echo "<td id='pn_p".$libro_p["id"]."'>".$precio_ne."</td>";
                                echo "<input type='hidden' id='pn_s_p".$libro_p["id"]."' value='".$precio_neto."'>";
                                 if ($presup["probabilidad"] != 3) {
                                    $venta_po=number_format($venta_p,0,",", ".");
                                }else{
                                	$venta_po=0;
                                }
                                echo"<td id='venta_p_p".$libro_p["id"]."' class='venta'>".$venta_po."</td>
                                <input type='hidden' id='venta_ps_p".$libro_p["id"]."' class='venta1_p' value='".$venta_p."'>";
                            }else {
                                echo "<td id='pn_p".$libro_p["id"]."'></td>
                                <td id='venta_p_p".$libro_p["id"]."' class='venta'></td>
                                <input type='hidden' id='venta_ps_p".$libro_p["id"]."' class='venta1_p'>";
                            }

                            $sql_prob = "SELECT * FROM probabilidades";
                            $req_prob = $bdd->prepare($sql_prob);
                            $req_prob->execute();
                            $probs = $req_prob->fetchAll();

                            if ($presup["probabilidad"] == 0) {
                                echo '<td>
                                    <select class="" name="proba[]" id="proba_p'.$libro_p["id"].'">
                                        <option value="">Seleccione</option>';
                                        foreach ($probs as $prob) {
                                            echo '<option value="'.$prob["id"].'">'.$prob["probabilidad"].' ( '.$prob["valor"].' % )</option>';
                                        }

                                    echo'</select>
                                </td>';
                            }else{
                                echo '<td>
                                    <select class="" name="proba[]" id="proba_p'.$libro_p["id"].'">
                                        <option value="">Seleccione</option>';
                                        foreach ($probs as $prob) {
                                         	if ($presup["probabilidad"] == $prob["id"]) {
                                                echo '<option value="'.$prob["id"].'" SELECTED>'.$prob["probabilidad"].' ( '.$prob["valor"].' % )</option>';
                                            }else{
                                                echo '<option value="'.$prob["id"].'">'.$prob["probabilidad"].' ( '.$prob["valor"].' % )</option>';
                                            }
                                                
                                        }

                                    echo'</select>
                                </td>';
                            }
                            echo "<td><input type='checkbox' name='b_presup[]' value='".$libro_p["pid"]."'></td>";
                            /*if ($_SESSION["tipo"] !=1) {
                                if ($presup["aprobado"]==1) {
                                    echo '<td class="text-success">Si</td>'; 
                                }else{
                                   	echo '<td class="text-danger">No</td>'; 
                                }

                                         
                            }else{
                                if ($presup["aprobado"]==1) {
                                	echo '<td><input type="checkbox" name="aprobar[]" value="'.$presup["id"].'" checked></td>'; 
                                }else{
                                    echo '<td><input type="checkbox" name="aprobar[]" value="'.$presup["id"].'"></td>'; 
                                }
                                          

                            }*/
                                        
                            
                            echo "<input type='hidden' name='presupuesto_p[]' id='presupuesto_p".$libro_p["id"]."'>";
                            
                                      

                            echo "<script>

                                $('#descuento_p".$libro_p["id"]."').keyup(function(){
                                    var pvp=parseInt($('#pvp_s_p".$libro_p["id"]."').val());
                                    var descuento=parseFloat($('#descuento_p".$libro_p["id"]."').val());";
                                    if ($_SESSION['tipo']!=6) {
	                                    echo "var desc_max=parseFloat(".$libro_p["desc_max_dist"].")* 100;";
	                                }else{
	                                        	echo "var desc_max=parseFloat(".$libro_p["desc_max"].")* 100;";
	                                }


                                     if (isset($libro_101)) {
                                        if ($libro_101 !=3481 && $libro_101 !=3482) {
	                                        echo "if (descuento > 69){
	                                            alert('el descuento no debe superar el 69%');
	                                            $('#descuento_p".$libro_p["id"]."').val('20');
	                                            $('#descuento_p".$libro_p["id"]."').focus();
	                                            descuento=20;
	                                        }
	                                        ";


	                                    }
                                    }else{
                                    	echo "if (descuento > 69){
	                                        alert('el descuento no debe superar el 69%');
	                                        $('#descuento_p".$libro_p["id"]."').val('20');
	                                        $('#descuento_p".$libro_p["id"]."').focus();
	                                        descuento=20;
	                                    }
	                                    ";
                                    }
                                            

                                    echo"

                                    if (desc_max > 0){
                                    	if (descuento > desc_max){

		                                    alert('el descuento no debe superar: '+desc_max);
		                                    $('#descuento_p".$libro_p["id"]."').val(desc_max);
		                                    $('#descuento_p".$libro_p["id"]."').focus();
		                                    descuento=desc_max;
	                                	}
                                    }
                                    
                                    descuento= descuento/100;
                                    var precio_neto= pvp - (pvp * descuento);
                                    if(isNaN(precio_neto)){
                                        precio_neto=0
                                    }

                                    $('#pn_p".$libro_p["id"]."').text(formatNumber.new(precio_neto));
                                       var tasa_c=parseInt($('#tasa_p".$libro_p["id"]."').val());
                                       tasa_c=tasa_c/100;
                                       var alumnos=parseInt($('#alm_p".$libro_p["id"]."').text());
                                       var vp= precio_neto *(Math.floor(alumnos*tasa_c))
                                       if(isNaN(vp)){
                                           	vp=0;
                                        }
                                        $('#venta_p_p".$libro_p["id"]."').text(formatNumber.new(vp));
                                        $('#venta_ps_p".$libro_p["id"]."').val(vp);
                                        var probab=$('#proba_p".$libro_p["id"]."').val();
                                        $('#presupuesto_p".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+probab);
                                        var total_vp_p=0;
                                        $('.venta1_p').each(function(){
                                            total_vp_p+=parseFloat($(this).val()) || 0;
                                            total_vp_p=Math.round(total_vp_p * 100) / 100;
                                        });                                  
                                        $('#total_vp_p').text(formatNumber.new(total_vp_p));

                                    })

                                    $('#tasa_p".$libro_p["id"]."').keyup(function(){
                                        var pvp=parseInt($('#pvp_s_p".$libro_p["id"]."').val());

                                        var descuento=parseFloat($('#descuento_p".$libro_p["id"]."').val());
                                        descuento= descuento/100;

                                        var precio_neto= pvp - (pvp * descuento);

                                        if(isNaN(precio_neto)){
                                            precio_neto=0
                                         }

                                        $('#pn_p".$libro_p["id"]."').text(formatNumber.new(precio_neto));

                                        var tasa_c=parseInt($('#tasa_p".$libro_p["id"]."').val());
                                        tasa_c=tasa_c/100;

                                        var alumnos=parseInt($('#alm_p".$libro_p["id"]."').text());

                                        var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                        if(isNaN(vp)){
                                            vp=0;
                                        }
                                        $('#venta_p_p".$libro_p["id"]."').text(formatNumber.new(vp));
                                        $('#venta_ps_p".$libro_p["id"]."').val(vp);
                                        var probab=$('#proba_p".$libro_p["id"]."').val();
                                        $('#presupuesto_p".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+probab);
                                        var total_vp_p=0;
                                        $('.venta1_p').each(function(){
                                            total_vp_p+=parseFloat($(this).val()) || 0;
                                            total_vp_p=Math.round(total_vp_p * 100) / 100;

                                        });                                 
                                       	$('#total_vp_p').text(formatNumber.new(total_vp_p));

                                    })

                                    $('#proba_p".$libro_p["id"]."').change(function(){
                                        var pvp=parseInt($('#pvp_s_p".$libro_p["id"]."').val());

                                        var descuento=parseFloat($('#descuento_p".$libro_p["id"]."').val());
                                        descuento= descuento/100;

                                        var precio_neto= pvp - (pvp * descuento);

                                        if(isNaN(precio_neto)){
                                            precio_neto=0
                                        }
                                        $('#pn_p".$libro_p["id"]."').text(formatNumber.new(precio_neto));

                                        var tasa_c=parseInt($('#tasa_p".$libro_p["id"]."').val());

                                        tasa_c=tasa_c/100;

                                        var alumnos=parseInt($('#alm_p".$libro_p["id"]."').text());

                                        var vp= precio_neto *(Math.floor(alumnos*tasa_c))

                                        if(isNaN(vp)){
                                            vp=0;
                                        }
                                        $('#venta_p_p".$libro_p["id"]."').text(formatNumber.new(vp));
                                        $('#venta_ps_p".$libro_p["id"]."').val(vp);
                                        var probab=$('#proba_p".$libro_p["id"]."').val();  
                                        $('#presupuesto_p".$libro_p["id"]."').val(".$libro_p["id"]."+'/'+tasa_c+'/'+descuento+'/'+pvp+'/'+probab);
                                        var total_vp_p=0;

                                        $('.venta1_p').each(function(){

                                            total_vp_p+=parseFloat($(this).val()) || 0;
                                            total_vp_p=Math.round(total_vp_p * 100) / 100;

                                        });
                                                                              
                                        $('#total_vp_p').text(formatNumber.new(total_vp_p));

                                    })




                          	</script>

                                      
                            </tr>";
                        
                            if($_SESSION["tipo"] !=2 ) {
                                /*if ($gp_periodo["f_cierre"] > date("Y-m-d")){
                                echo'<center><button class="btn btn-primary">Actualizar</button></center>
                                <input type="hidden" name="id_colegio" id="cole" value="'.$_GET["colegio"].'">
                              <input type="hidden" name="cod_colegio" value="'.$colegio["codigo"].'">
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
                            <td id='total_vp_p'></td>
                            <td></td>";
                            if ($gp_periodo["f_cierre"] > date("Y-m-d")) {
                            	echo"<td><button id='borrar' class='btn btn-sm btn-danger eliminar' href='#'><i class='fa fa-trash-o bigger-120'></i></button></td>";
                            }
                        echo"</tr>";
                    echo '</tbody>
                </table></div>
                <input type="hidden" name="id_colegio" id="cole" value="'.$_GET["colegio"].'">
                <input type="hidden" name="codigo" value="'.$_GET["codigo"].'">
                <input type="hidden" name="periodo" value="'.$_GET["periodo"].'">';
                if ($num_hp < 1) {
                      
                }
                else {
                    echo '<center>';
	                	if ($_SESSION["tipo"]==1) {
	                        /*echo '<button class="btn btn-success">Aprobar</button> ';
	                        echo '<button class="btn btn-danger" id="rechazar">Rechazar</button> ';*/
	                        echo '<button class="btn btn-primary" id="guardar_p">Guardar</button> ';
	                    }elseif($_SESSION["tipo"] !=2 && $_SESSION["tipo"] != 4) {
	                        if ($gp_periodo["f_cierre"] > date("Y-m-d")) {
	                            echo '<button class="btn btn-primary miBoton">Guardar</button> ';
	                        }
	                    }
                    echo '</center>';
                }
            echo "</form>";

            echo '<br><a class="btn btn-success" href="php/presupuesto_excel.php?cole='.$_GET['colegio'].'&periodo='.$_GET['periodo'].'">Exportar Excel</a>';
                                  
        ?>











</div>
<script src="../vendors/scripts/core.js"></script>
<script>

	 $('#grado').on('change',function(){
      var valor = $(this).val();
      var materia=$("#materia").val();
                 //alert(valor);
      if (valor==17) {
          $(".g_otro").removeClass("d-none");
          $("#grado_otro").attr("required","required");
                 
      }else {
        $(".g_otro").addClass("d-none");
        $("#grado_otro").removeAttr("required");
      }
      var dataString = 'mat_gra='+materia+"/"+valor;
      $.ajax({

        url: "ajax/buscar_l_eureka_p.php",
        type: "POST",
        data: dataString,
        success: function (resp) {
                 
          $("#libro_e").html(resp);                        
          console.log(resp);
          if(valor =="") {
            $("#libro_e").html("");
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

      $('#materia').on('change',function(){
        var valor = $(this).val();
        var grado = $("#grado").val()
        //alert(valor);
        var dataString = 'mat_gra='+valor+'/'+grado;
              
        $.ajax({

          url: "ajax/buscar_l_eureka_p.php",
          type: "POST",
          data: dataString,
          dataType: "html",
          success: function (resp) {
                  
            $("#libro_e").html(resp);                        
            if(valor =="") {
              $("#libro_e").html("");
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

      $('#libro_e').on('change',function(){
        $value=$("#materia").val()+"/"+$("#grado").val()+"/"+$(this).val()+"/"+$("#grado_otro").val();
        $("#libs_ao").val($value);
                 
      });

      //agregar mas areas objetivas
    var m = 1;
    $("#agregar_ao").click(function(){

      if (m>98) {
        $("#agregar_ao").addClass("d-none");
      }

      $("#agg_ao"+m).removeClass("d-none");
      

      m++;

      <?php for ($i=1; $i < 100; $i++) { ?>

        $('#grado<?php echo $i; ?>').on('change',function(){
          var valor = $(this).val();
          var materia=$("#materia<?php echo $i; ?>").val();
          //alert(valor);
          if (valor==17) {
              $(".g_otro<?php echo $i; ?>").removeClass("d-none");
              $("#grado_otro<?php echo $i; ?>").attr("required","required");
                     
          }else {
            $(".g_otro<?php echo $i; ?>").addClass("d-none");
            $("#grado_otro<?php echo $i; ?>").removeAttr("required");
          }
          var dataString = 'mat_gra='+materia+"/"+valor;
          $.ajax({

            url: "ajax/buscar_l_eureka_p.php",
            type: "POST",
            data: dataString,
            success: function (resp) {
                     
              $("#libro_e<?php echo $i; ?>").html(resp);                        
              console.log(resp);
              if(valor =="") {
                $("#libro_e<?php echo $i; ?>").html("");
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

        $('#materia<?php echo $i; ?>').on('change',function(){
          var valor = $(this).val();
          var grado = $("#grado<?php echo $i; ?>").val()
          //alert(valor);
          var dataString = 'mat_gra='+valor+'/'+grado;
                
          $.ajax({

            url: "ajax/buscar_l_eureka_p.php",
            type: "POST",
            data: dataString,
            dataType: "html",
            success: function (resp) {
                    
              $("#libro_e<?php echo $i; ?>").html(resp);                        
              if(valor =="") {
                $("#libro_e<?php echo $i; ?>").html("");
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

        $('#libro_e<?php echo $i; ?>').on('change',function(){
          $value=$("#materia<?php echo $i; ?>").val()+"/"+$("#grado<?php echo $i; ?>").val()+"/"+$(this).val()+"/"+$("#grado_otro<?php echo $i; ?>").val();
          $("#libs_ao<?php echo $i; ?>").val($value);
                   
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

    var total_vp=0;

      $('.venta1').each(function(){

        total_vp+=parseFloat($(this).val()) || 0;
        total_vp=Math.round(total_vp * 100) / 100;

      });
                                        
      $('#total_vp').text(formatNumber.new(total_vp));

      var total_vp_p=0;

      $('.venta1_p').each(function(){

        total_vp_p+=parseFloat($(this).val()) || 0;
        total_vp_p=Math.round(total_vp_p * 100) / 100;

      });

      $('#total_vp_p').text(formatNumber.new(total_vp_p));

      var total_vp_m=0;

      $('.venta1_m').each(function(){

        total_vp_m+=parseFloat($(this).val()) || 0;
        total_vp_m=Math.round(total_vp_m * 100) / 100;

      });

      $('#total_vp_m').text(formatNumber.new(total_vp_m));

      var total_vp_d=0;

      $("#borrar").click(function(e) {
  		e.preventDefault(); // Evita el envío inmediato

	  	if (confirm("¿Estás seguro de que deseas borrar?")) {
	    	$("#pp").attr("action", "php/borrar_presupuesto.php");
	    	$("#pp").submit(); // Envía el formulario después de confirmar
	  	}
	});
</script>