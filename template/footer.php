<div class="footer-wrap pd-20 mb-20 card-box d-print-none">
					Ink-pulse - CRM de
					
						Eureka Contenidos Educativos
				</div>


<div class="modal fade bs-example-modal-xl" id="modal_pedidos" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            	<div class="modal-header">
                	<h4 class="modal-title" id="myLargeModalLabel">
                        Pedido de venta
               		</h4>
                 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form action="colegios_pedidos.php" method="POST" class="miFormulario">
	                <div class="modal-body">
	                	<center>
	                    <div class="form-group">
							<label class="control-label no-padding-right" for="periodo"> Seleccione el periodo:<small style="color:red;"> *</small> </label><br>
							<select name="periodo" id="periodo">
								<?php  

									$sql = "SELECT id, periodo FROM periodos ORDER BY id DESC";

									$req = $bdd->prepare($sql);
									$req->execute();
									$periodos = $req->fetchAll();

									foreach ($periodos as $periodo) {

										echo '<option value="'.$periodo["id"].'">'.$periodo["periodo"].'</option>';
									}

								?>
							</select>
						</div>           
					

	    			     <button class="btn btn-success">Siguiente</button></center>
	    			 </center>
	            	</div>
            	</form>  
            <div class="modal-footer">
            
            </div>

         </div>

    </div>
</div>

<div class="modal fade bs-example-modal-xl" id="modal_devols" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            	<div class="modal-header">
                	<h4 class="modal-title" id="myLargeModalLabel">
                        Devoluciones de venta
               		</h4>
                 	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <form action="colegios_devols.php" method="POST" class="miFormulario">
	                <div class="modal-body">
	                	<center>
	                    <div class="form-group">
							<label class="control-label no-padding-right" for="periodo"> Seleccione el periodo:<small style="color:red;"> *</small> </label><br>
							<select name="periodo" id="periodo">
								<?php  

									$sql = "SELECT id, periodo FROM periodos ORDER BY id DESC";

									$req = $bdd->prepare($sql);
									$req->execute();
									$periodos = $req->fetchAll();

									foreach ($periodos as $periodo) {

										echo '<option value="'.$periodo["id"].'">'.$periodo["periodo"].'</option>';
									}

								?>
							</select>
						</div>           
					

	    			     <button class="btn btn-success">Siguiente</button></center>
	    			 </center>
	            	</div>
            	</form>  
            <div class="modal-footer">
            
            </div>

         </div>

    </div>
</div>