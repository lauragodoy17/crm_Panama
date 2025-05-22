<?php

  require_once("../php/aut.php");
  include("../conexion/bdd.php");
?>

<div class="pd-20">
                        <form action="php/poblacion.php" method="POST">
                        <div class="table-responsive">
                          <style>
                            
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

                              $sql = "SELECT MAX(paralelos) as nunfila FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$_GET['periodo']}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $nunfila = $req->fetch();

                              if ($nunfila['nunfila'] <1) {

                                $sql_pa="SELECT id FROM periodos WHERE id_calendario='{$_GET['id_calendario']}' ORDER BY id DESC LIMIT 1 OFFSET 1;";

                                $req_pa = $bdd->prepare($sql_pa);
                                $req_pa->execute();
                                $pa = $req_pa->fetch();

                                $periodo_po=$pa['id'];

                                $sql = "SELECT MAX(paralelos) as nunfila FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$pa['id']}'";
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

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=1 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $prejardin = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=2 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $jardin = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=3 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $transicion = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=4 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $primero = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=5 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $segundo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=6 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $tercero = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=7 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $cuarto = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=8 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $quinto = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=9 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $sexto = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=10 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $septimo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=11 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $octavo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=12 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $noveno = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=13 AND paralelos='{$p}'";
                              $req = $bdd->prepare($sql);
                              $req->execute();
                              $decimo = $req->fetch();

                              $sql = "SELECT alumnos FROM `grados_paralelos` WHERE id_colegio='{$_GET['colegio']}' AND id_periodo='{$periodo_po}' AND id_grado=14 AND paralelos='{$p}'";
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
                        <button class="btn d-none" type="button" id="quitar">Quitar paralelo</button>
                        <input type="hidden" name="id_colegio" value='<?php echo $_GET['colegio'] ?>'>
                        <input type="hidden" name="periodo" value="<?php echo $_GET['periodo'] ?>">
                        <input type="hidden" name="cod_colegio" value="<?php echo $_GET['codigo'] ?>">
                        <center><br><button class="btn btn-primary">Guardar</button></center>
                        </form>
                      </div>
                      <script>
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

          $('#quitar').removeClass("d-none");
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
                      </script>