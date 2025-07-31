<?php 
	require_once("php/aut.php");
	require_once('conexion/bdd.php'); 
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="theme-color" content="#52004F">
		<meta charset="utf-8" />
		<title>OP</title>

		<meta name="description" content="Sistema Bitácora" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="vendors/styles/core.css" />
		 <link rel="stylesheet" type="text/css" href="vendors/styles/style.css" />
		<style>
			@media print {
			  a[href]:after {
			    content: none
			  }
			}

			body{
				background: #FFF;
			}
			
		</style>
	</head>


	
	<body>

		<?php

			$sql = "SELECT o.id as opid, o.op_per,o.fecha, o.n_doc, o.solicitante, o.valor, o.guia, o.fecha_entrega, o.archivo, o.observaciones, o.estado, o.transportista, o.obs_envio, o.adjunto_envio, o.fecha_anu, o.motivo_anu, o.ciudad_destino, o.usuario_anu, o.id_pedido, o.id_pedido_dist,o.id_muestreo,o.id_devol_c,o.id_devol_p,o.id_devol_v,o.fecha_at, o.usuario_at , t.tipo, t.descrip, c.*, CONCAT(u.nombres,' ',u.apellidos) AS usuario FROM ordenes_pedidos o JOIN tipo_doc t ON o.tipo_doc=t.id JOIN clientes c ON c.id=o.cliente JOIN usuarios u ON u.id=o.usuario WHERE o.id='".$_GET["op"]."'";

			$req = $bdd->prepare($sql);
			$req->execute();

			$op = $req->fetch();
	

		?>

		<div class="main-container">
			<img src="vendors/images/logo_ink-pulse.png" alt="logo">
			<h2 align="center">Orden de pedido # <?php echo $op["opid"] ?></h2><br>
			<table class="table table-bordered">

				<tr>
					<td><b>Fecha y Hora:</b> <?php echo $op["fecha"] ?></td>
					<td><b>Usuario:</b> <?php echo $op["usuario"] ?></td>
					<!--<?php if ($op["op_per"] !=0) { ?>
						<td><b>OP personalizada:</b> <?php echo $op["op_per"] ?></td>
					<?php } ?>-->
				</tr>
				<tr>
					<td><b>Tipo documento:</b> <?php echo $op["tipo"]. " (".$op["descrip"].")" ?></td>
					<td><b>Número de documento:</b> <?php echo $op["n_doc"] ?></td>

					<?php if ($op["doc_alterno"] != "") { ?>
						<td><b>Documento alterno:</b> <?php echo $op["doc_alterno"] ?></td>
					<?php } ?>
					
				</tr>
				
			</table>
			<?php if ($op["estado"] ==4) { ?>
				<center><h3 style="color: red;">ANULADA</h3></center>
			<?php }elseif ($op["estado"] ==1) {?>
				<center><h3 style="color: blue;">Pendiente</h3></center>
			<?php }elseif ($op["estado"] ==2) {?>
				<center><h3 style="color: #20D31C;">Atendida</h3></center>
			<?php }?>
			<b>Cliente:</b> <?php echo $op["cliente"] ?><br><br>
			<b>Identificación:</b> <?php echo $op["documento"] ?><br>
			<b>Ciudad:</b> <?php echo $op["ciudad"] ?> <br>
			<b>Dirección:</b> <?php echo $op["direccion"] ?><br>
			<b>Teléfono:</b> <?php echo $op["telefonos"] ?><br>
			<b>Contacto:</b> <?php echo $op["solicitante"] ?><br>
			<b>Ciudad destino:</b> <?php echo $op["ciudad_destino"] ?><br>
			<?php if ($op["id_pedido"]==0) {
				list($antes,$archivo)=explode("_", $op["archivo"]);
			?>

				<span class="d-print-none"> <b>Archivo Adjunto:</b> <a href="adjuntos/<?php echo $op["archivo"] ?>" style="cursor: pointer;" target="_blank" download="archivo"><?php echo $archivo ?></a><br><br></span>
			<?php } ?>

			<?php if ($op["id_pedido"]!=0) {

					$sql = "SELECT estado FROM pedidos WHERE id='".$op["id_pedido"]."'";
					$req = $bdd->prepare($sql);
					$req->execute();

					$pedido= $req->fetch();
					if ($pedido["estado"] ==2) {
						echo'<b>Pedido de venta:</b> <a href="pedido_colegio_aprobado.php?id_pedido='.$op["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido"].'</a><br><br>';
					}else{
						echo'<b>Pedido de venta:</b> <a href="pedido_colegio_entregado.php?id_pedido='.$op["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido"].'</a><br><br>';
					}
					
                                	
                }

                if ($op["id_pedido_dist"]!=0) {

					$sql = "SELECT estado FROM pedidos2 WHERE id='".$op["id_pedido_dist"]."'";
					$req = $bdd->prepare($sql);
					$req->execute();

					$pedido= $req->fetch();
					if ($pedido["estado"] ==2) {
						echo'<b>Pedido distribuidor:</b> <a href="pedido_colegio_aprobado2.php?id_pedido='.$op["id_pedido_dist"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido_dist"].'</a><br><br>';
					}else{
						echo'<b>Pedido distribuidor:</b> <a href="pedido_colegio_entregado2.php?id_pedido='.$op["id_pedido_dist"].'" style="cursor: pointer;" target="_blank">#'.$op["id_pedido_dist"].'</a><br><br>';
					}					
                                	
                }

                if ($op["id_muestreo"]!=0) {

					$sql = "SELECT estado FROM muestreos WHERE id='".$op["id_muestreo"]."'";
					$req = $bdd->prepare($sql);
					$req->execute();

					$pedido= $req->fetch();
					if ($pedido["estado"] ==2) {
						echo'<b>Muestreo:</b> <a href="muestreo_colegio_resto.php?id_pedido='.$op["id_muestreo"].'&tp=3" style="cursor: pointer;" target="_blank">#'.$op["id_muestreo"].'</a><br><br>';
					}else{
						echo'<b>Muestreo:</b> <a href="muestreo_colegio_resto.php?id_pedido='.$op["id_muestreo"].'&tp=4" style="cursor: pointer;" target="_blank">#'.$op["id_muestreo"].'</a><br><br>';
					}					
                                	
                }

                if ($op["id_devol_c"]!=0) {

					$sql = "SELECT estado FROM devoluciones WHERE id='".$op["id_devol_c"]."'";
					$req = $bdd->prepare($sql);
					$req->execute();

					$pedido= $req->fetch();
					

					echo'<b>Devolución de cliente:</b> <a href="vista_devol.php?id_pedido='.$op["id_devol_c"].'&tipo=1" style="cursor: pointer;" target="_blank">#'.$op["id_devol_c"].'</a><br><br>';
                                	
                }

                if ($op["id_devol_p"]!=0) {

					$sql = "SELECT estado FROM devoluciones_prov WHERE id='".$op["id_devol_p"]."'";
					$req = $bdd->prepare($sql);
					$req->execute();

					$pedido= $req->fetch();

					echo'<b>Devolución de proveedor:</b> <a href="vista_devol.php?id_pedido='.$op["id_devol_p"].'&tipo=2" style="cursor: pointer;" target="_blank">#'.$op["id_devol_p"].'</a><br><br>';
									
                                	
                }

                if ($op["id_devol_v"]!=0) {

					$sql = "SELECT estado FROM devoluciones_v WHERE id='".$op["id_devol_v"]."'";
					$req = $bdd->prepare($sql);
					$req->execute();

					$pedido= $req->fetch();

					echo'<b>Devolución de venta:</b> <a href="vista_devol.php?id_pedido='.$op["id_devol_v"].'&tipo=2" style="cursor: pointer;" target="_blank">#'.$op["id_devol_v"].'</a><br><br>';
									
                                	
                }

                if ($op["id_pedido"]==0 && $op["id_pedido_dist"]==0 && $op["id_muestreo"]==0 && $op["id_devol_c"]==0 && $op["id_devol_p"]==0 && $op["id_devol_v"]==0 ) {
				
					$sql_ag = "SELECT id_pedido FROM op_pedidos_agrupados WHERE op='".$op["opid"]."'";
					$req_ag = $bdd->prepare($sql_ag);
					$req_ag->execute();
					$agps = $req_ag->fetchAll();
					echo "<b>Pedidos de venta agrupados: </b>";
					foreach ($agps as $agp) {

						echo'<a href="pedido_colegio_aprobado.php?id_pedido='.$agp["id_pedido"].'" style="cursor: pointer;" target="_blank">#'.$agp["id_pedido"].'</a>, ';
						
					}

					echo '<br><br>';
					

				}

			?>
			<b>Observaciones:</b> <?php echo $op["observaciones"] ?><br><br>

			<?php if ($op["estado"] ==2) {

				$sql = "SELECT CONCAT(nombres,' ',apellidos) AS usr_aten FROM usuarios WHERE id='".$op["usuario_at"]."' ";

				$req = $bdd->prepare($sql);
				$req->execute();

				$aten= $req->fetch();

			?>
				<hr>
				<b>Fecha atendida:</b> <?php echo $op["fecha_at"] ?> <br>
				<b>Usuario atendida:</b> <?php echo $aten["usr_aten"]; ?> <br>
				<b>Entregado a:</b> <?php echo $op["transportista"] ?> <br>
				<b>Guía:</b> <?php echo $op["guia"] ?> <br>
				<b>Valor:</b> <?php echo $op["valor"] ?><br>
				<b>Fecha de despacho:</b> <?php echo $op["fecha_entrega"] ?><br><br>
				<?php if ($op["estado"] ==2) { ?>
				<span class="d-print-none"> <b>Adjunto soporte de entrega:</b> <a href="adjuntos/envio/<?php echo $op["adjunto_envio"] ?>" style="cursor: pointer;" target="_blank"><?php echo $op["adjunto_envio"] ?></a><br><br></span>
				<?php } ?>

				<b>Observaciones de despacho:</b> <?php echo $op["obs_envio"] ?><br><br>
			<?php }?>

			<?php if ($op["estado"] ==4) { 

				$sql = "SELECT CONCAT(nombres,' ',apellidos) AS usr_anu FROM usuarios WHERE id='".$op["usuario_anu"]."' ";

				$req = $bdd->prepare($sql);
				$req->execute();

				$anu= $req->fetch();

			?>


				<b>Usuario anulación:</b> <?php echo $anu["usr_anu"] ?><br>
				<b>Fecha de anulación:</b> <?php echo $op["fecha_anu"] ?><br>
				<b>Motivo de anulación:</b> <?php echo $op["motivo_anu"] ?><br>
			<?php } ?>

			<center>
				<button class="btn btn-primary d-print-none" onclick="window.print();">Imprimir</button>
				<a href="lista_op.php" class="btn btn-info d-print-none" id="anular">Volver</a>
					<!--<?php if ($op["estado"] ==1) { ?>
						<a href="php/anular_op.php?op=<?php echo $op["opid"]; ?>" class="btn btn-danger d-print-none" id="anular">Anular</a>
					<?php } ?>-->
				
				<!--<a href="index.php" class="btn btn-warning d-print-none">Volver</a>-->
			</center>

		</div>
		
		<script src="assets/js/jquery-2.1.4.min.js"></script>
		<!--<script>
			$("#anular").click(function(e){

              e.preventDefault();
              
              if (confirm("¿Seguro que desea Anular esta OP?")) {
              	
              	window.location="php/anular_op.php?op=<?php echo $op["opid"]; ?>"
                  
              }

          })
		</script>-->
	</body>
</html>
