<?php
  ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
	require_once("../php/aut.php");
	include("../conexion/bdd.php");
?>
<div class="pd-20">

<?php

  $sql = "SELECT id, adjunto, nombre FROM adjuntos WHERE id_colegio='".$_GET['colegio']."' AND id_periodo='".$_GET['periodo']."' AND tipo!=1";
  $req = $bdd->prepare($sql);
  $req->execute();

  $adjuntos = $req->fetchAll();


?>
  <form action="php/adjuntos.php" method="POST" enctype="multipart/form-data">
                              
    <div class="row">
        <div class="col-sm-4 col-sm-offset-2">
            <div class="form-group">
              <label class="control-label no-padding-right" for="nombre"> Nombre del docmente <small style="color:red;"> *</small></label>
              <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del docmente" required>
            </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label no-padding-right" for="lista"> Adjuntar <small style="color:red;"> *</small> </label>
            <input type="file" class="form-control" name="lista" required>
            <input type="hidden" name="colegio" value='<?php echo $_GET['colegio'] ?>'>
            <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
            <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">

          </div>
        </div>
                                   
    </div>
    <?php if($_SESSION["tipo"] !=2) { ?>
     
      <br><center><button class="btn btn-primary">Subir</button></center><br>
      
    <?php } ?>
  </form>
                              
  <div class="row">
    <div class="col-sm-6 offset-sm-2">
      <table class="table table-bordered table-hover table-condensed">
        <thead>
          <th>Descripción</th>
          <th>Archivo</th>
          <th>Acciones</th>
        </thead>
        <tbody>
          <?php

            foreach ($adjuntos as $adjunto) {
              list($antes,$archivo)=explode("_", $adjunto["adjunto"]);                                  
              echo '<tr>
                <td>'.$adjunto["nombre"].'</td>
                <td><a href="adjuntos/'.$adjunto["adjunto"].'" target="_blank" download='.$archivo.' >'.$archivo.'</a></td>';
                if($_SESSION["tipo"] !=2) {
                  echo'<td><a href="#" data-adj="'.$adjunto["id"].'" class="btn btn-danger btn-xs eliminar_ad"><i class="ace-icon fa fa-trash-o bigger-120"></a></td>';
                }
              echo'</tr>';

            }

          ?>
                                         
        </tbody>
      </table>
    </div>
  </div>

  <script>
    $(".eliminar_ad").click(function(e){
      e.preventDefault();
      var adj= $(this).attr('data-adj');
      if (confirm("¿Seguro que desea eliminar este documento")) {
        window.location="php/eliminar_adjuntos.php?id_ad="+adj+"&cod_colegio=<?php echo $_GET['codigo']; ?>"+"&periodo=<?php echo $_GET['periodo']; ?>"
      }

    })
  </script>