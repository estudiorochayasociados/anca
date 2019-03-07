<?php
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$conexion = new Clases\Conexion();
$con = $conexion->con();
include "../vendor/phpoffice/phpexcel/Classes/PHPExcel.php";
$error = 0;
$query = '';
$headerTabla = "<thead><th>Articulo</th><th>Producto</th><th>Precio Minorista</th><th>Precio Mayorista</th><th>Categoria</th><th>Subcategoria</th></thead>";
$columnaImagen = 0;
$columnaDescripcion = 1;
$maximoColumnas = "D";
?>
<div class="col-md-12">
    <form action="index.php?op=productos&accion=importar" method="post" enctype="multipart/form-data">
        <h3>Importar productos de Excel a la Web (<a href="upload/modelo.xlsx" target="_blank">descargar modelo</a>)
        </h3>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <input type="file" name="uploadFile" class="form-control" value=""/><br/>
            </div>
            <div class="col-md-6">
                <input type="submit" name="submit" value="Ver archivo de Excel" class='btn  btn-info'/>
            </div>
        </div>
    </form>
    <?php
    if (isset($_POST['submit'])) {
        if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
            $allowedExtensions = array("xls", "xlsx");
            $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
            if (in_array($ext, $allowedExtensions)) {
                @mkdir("upload", 0644);
                $file_size = $_FILES['uploadFile']['size'] / 1024;
                $file = "upload/" . $_FILES['uploadFile']['name'];
                $isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
                if ($isUploaded) {
                    try {
                        $objPHPExcel = PHPExcel_IOFactory::load($file);
                    } catch (Exception $e) {
                        die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                    }
                    $sheet = $objPHPExcel->getSheet(0);
                    $total_rows = $sheet->getHighestRow();
                    $highest_column = $sheet->getHighestColumn();
                    if ($highest_column != $maximoColumnas) {
                        echo 'Error en el formato del excel, hay más de las 3 columnas permitidas';
                        $error = 1;
                    }

                    if ($error == 0) {
                        echo "<form method='post'><input type='submit' class='btn  btn-success' name='subir' value='Ya lo revisé solo queda guardar'></form>";
                    } else {
                        echo "hay algun error para poder subir";
                    }

                    echo "<hr/>Total de Productos: " . $total_rows;

                    echo '<h4>Datos traídos del excel:</h4>';
                    echo '<table cellpadding="5" cellspacing="1"   class=" table table-hover table-bordered responsive">';
                    echo $headerTabla;
                    $query = "insert into `productos` (`cod_producto`,`titulo`, `precio`, `precio_mayorista`,  `categoria`, `subcategoria`) VALUES ";
                    for ($row = 2; $row <= $total_rows; $row++) {
                        $single_row = $sheet->rangeToArray('A' . $row . ':' . $highest_column . $row, null, true, false);
                        if ($single_row[0][0] != '') {
                            $explotarId = explode("/", $single_row[0][0]);
                            $categoria = $explotarId[0];
                            $subcategoria = $explotarId[1];
                            echo "<tr>";
                            $query .= "(";
                            foreach ($single_row[0] as $key => $value) {
                                $value = trim(str_replace("'", "", $value));
                                $value = trim(str_replace('"', "", $value));
                                echo "<td>" . $value . "</td>";
                                $query .= "'" . trim($value) . "',";
                            }
                            $query = substr($query, 0, -1);
                            $query .= ",'" . trim($categoria) . "',";
                            echo "<td>" . $categoria . "</td>";
                            $query .= "'" . trim($subcategoria) . "'";
                            echo "<td>" . $subcategoria . "</td>";
                            $query .= "),";
                            echo "</tr>";
                        }
                    }
                    $query = substr($query, 0, -1);
                    echo '</table>';
                    unlink($file);
                    $_SESSION["query"] = $query;
                } else {
                    echo '<span class="alert alert-danger">Archivo no subido</span>';
                }
            } else {
                echo '<span class="alert alert-danger">El tipo de archivo no es aceptado</span>';
            }
        } else {
            echo '<span class="alert alert-danger">Seleccionar primero el archivo a subir.</span>';
        }
    }

    echo $query;

    if (isset($_POST["subir"])) {
        if (!empty($_SESSION["query"])) {
            mysqli_query($con, "DELETE FROM `productos`");
            mysqli_query($con, $_SESSION["query"]);
            if (mysqli_affected_rows($con) > 0) {
                echo '<span class="alert alert-success">Base de dato actualizada!</span>';
            } else {
                echo '<span class="alert alert-danger">No se pudo subir la base de datos.</span>';
            }
        }
    }
    ?>
</div>
