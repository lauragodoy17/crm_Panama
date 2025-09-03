<?php
    ini_set('display_errors', 1);

    ini_set('display_startup_errors', 1);

    error_reporting(E_ALL);

    include("../lib/autoload-phpspreadsheet.php");
    include("../conexion/bdd.php");
    use PhpOffice\PhpSpreadsheet\IOFactory;
    //set_time_limit(300);
    header("Content-Type:text/html;charset=utf-8"); 
    $dir_subida = $_SERVER['DOCUMENT_ROOT'] .'/adjuntos/excel_cli/';
    $fichero_subido = $dir_subida . basename($_FILES['archivo']['name']);
    if($_FILES["archivo"]["type"]=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            echo '<pre>';
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido)) {
                    //leer excel
            $spreadsheet = IOFactory::load($fichero_subido);
            $sheet = $spreadsheet->getSheet(0);

            $highestRow = $sheet->getHighestDataRow();
            $libro= array();
            for ($row = 4; $row <= $highestRow; $row++){
                
                    $n_cliente=$sheet->getCell("C".$row)->getValue();
                    $documento=$sheet->getCell("D".$row)->getValue();
                    $direccion=$sheet->getCell("E".$row)->getValue();
                    $telefonos=$sheet->getCell("F".$row)->getValue();
                    $ciudad=$sheet->getCell("G".$row)->getValue();


                    $sql = "SELECT id FROM clientes WHERE documento='".$documento."'";

                    $req = $bdd->prepare($sql);
                    $req->execute();

                    $cliente = $req->fetch();

                    if ($cliente["id"] =="") {

                        $sql_z = "INSERT INTO clientes (cliente,documento,direccion,telefonos,ciudad) VALUES ('".$n_cliente."', '".$documento."', '".$direccion."', '".$telefonos."', '".$ciudad."')";

                    }else{

                        $sql_z = "UPDATE clientes SET cliente='".$n_cliente."', documento='".$documento."', direccion='".$direccion."', telefonos='".$telefonos."', ciudad='".$ciudad."' WHERE id='".$cliente["id"]."'";

                    }

                    $query_z = $bdd->prepare( $sql_z );
                    if ($query_z == false) {
                     print_r($bdd->errorInfo());
                     die ('Erreur prepare');
                    }

                    $sth_z = $query_z->execute();
                    if ($sth_z == false) {
                     print_r($query_z->errorInfo());
                     die ('Erreur execute');
                    }
                    //echo $isbn."-".$existencia."<br>";
            
                //echo $sheet->getCell("A".$row)->getValue()." - ";
                //echo $isbn." - ";
                //echo $existencia." - ";
                //echo $sheet->getCell("C".$row)->getValue();
                //echo "<br>";
                
                
                
            }
            echo "<script>alert('Los clientes se han actualizado');window.location='../clientes_op.php'</script>";
        } else {
            echo "Ha ocurrido un error, vuelva a intentarlo, si el error persiste comuniquese con los desarrolladores";
        }
        //echo 'Más información de depuración:';
        //print_r($_FILES);
    }
    else {
        echo "No es un archivo Excel Soportado por el sistema";
    
    }
    
    
        
    
    
?>