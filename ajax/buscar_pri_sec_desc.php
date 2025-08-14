<?php
	require_once("../php/aut.php");
	require_once('../conexion/bdd.php'); 

	$sql = "SELECT id,libro FROM libros WHERE pri_sec='".$_POST["pri_sec"]."' ORDER BY libro";
	$req = $bdd->prepare($sql);
	$req->execute();
	$libros = $req->fetchAll();

	foreach($libros as $lib) {;
		echo"<div class='row'>
        	<div class='form-group col-sm-3 offset-sm-3'>
        		<label id='l_pri_sec".$lib['id']."' for='".$lib['id']."' class='control-label'>Libro:</label>
        		<select name='pri_sec[]' id='".$lib['id']."' class='form-control custom-select2' width='200'>
        			<option value=".$lib["id"].">".$lib["libro"]."</option>
        		</select>";
           		
           	echo"</div>

           	<div class='form-group col-sm-3'>
                <label id='l_descuento_pri_sec".$lib['id']."' for='descuento1' class='control-label'>Descuento %<small style='color:red;'> *</small></label>
                <input type='number' class='form-control descuento_pri_sec' name='descuento_pri_sec[]'>
            </div>
           	<div class='form-group col-sm-3'>
                <label id='l_cantidad_pri_sec".$lib['id']."' for='cantidad1' class='control-label'>Cantidad<small style='color:red;'> *</small></label>
                <input type='number' class='form-control cantidad_pri_sec' name='cantidad_pri_sec[]'>
            </div>
         </div>";
	}
?>