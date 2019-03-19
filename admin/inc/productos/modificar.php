<?php
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$productos->set("cod", $cod);
$producto = $productos->view();
$imagenes->set("cod", $producto["cod"]);
$imagenes->set("link", "productos&accion=modificar");

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'productos'"));

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=productos&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $producto["cod"];
    $productos->set("id", $producto["id"]);
    $productos->set("cod", $producto["cod"]);
    $productos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $productos->set("cod_producto", $funciones->antihack_mysqli(isset($_POST["cod_producto"]) ? $_POST["cod_producto"] : ''));
    $productos->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    //$productos->set("precio_mayorista", $funciones->antihack_mysqli(isset($_POST["precio_mayorista"]) ? $_POST["precio_mayorista"] : ''));
    $productos->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : 0));
    $productos->set("precio_descuento", $funciones->antihack_mysqli(isset($_POST["precio_descuento"]) ? $_POST["precio_descuento"] : ''));
    $productos->set("stock", $funciones->antihack_mysqli(isset($_POST["stock"]) ? $_POST["stock"] : ''));
    $productos->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $productos->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $productos->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : ''));
    $productos->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));
    $productos->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : ''));
    $productos->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));
    $productos->set("meli", $funciones->antihack_mysqli(isset($_POST["meli"]) ? $_POST["meli"] : ''));
    $productos->set("url", $funciones->antihack_mysqli(isset($_POST["url"]) ? $_POST["url"] : ''));

    foreach ($_FILES['files']['name'] as $f => $name) {
        $imgInicio = $_FILES["files"]["tmp_name"][$f];
        $tucadena = $_FILES["files"]["name"][$f];
        $partes = explode(".", $tucadena);
        $dom = (count($partes) - 1);
        $dominio = $partes[$dom];
        $prefijo = substr(md5(uniqid(rand())), 0, 10);
        if ($dominio != '') {
            $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
            move_uploaded_file($imgInicio, $destinoFinal);
            chmod($destinoFinal, 0777);
            $destinoRecortado = "../assets/archivos/recortadas/a_" . $prefijo . "." . $dominio;

            $zebra->source_path = $destinoFinal;
            $zebra->target_path = $destinoRecortado;
            $zebra->jpeg_quality = 80;
            $zebra->preserve_aspect_ratio = true;
            $zebra->enlarge_smaller_images = true;
            $zebra->preserve_time = true;

            if ($zebra->resize(800, 700, ZEBRA_IMAGE_NOT_BOXED)) {
                unlink($destinoFinal);
            }

            $imagenes->set("cod", $cod);
            $imagenes->set("ruta", str_replace("../", "", $destinoRecortado));
            $imagenes->add();
        }

        $count++;
    }

    $productos->edit();
    $funciones->headerMove(URL . "/index.php?op=productos");
}
?>

<div class="col-md-12 ">
    <h4>Productos</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Título:<br/>
            <input type="text" name="titulo" value="<?= $producto["titulo"] ?>">
        </label>
        <label class="col-md-4">Categoría:<br/>
            <select name="categoria">
                <?php
                foreach ($data as $categoria) {
                    if ($producto["categoria"] == $categoria["cod"]) {
                        echo "<option value='" . $categoria["cod"] . "' selected>" . $categoria["titulo"] . "</option>";
                    } else {
                        echo "<option value='" . $categoria["cod"] . "'>" . $categoria["titulo"] . "</option>";
                    }
                }
                ?>
            </select>
        </label>
        <label class="col-md-4">Stock:<br/>
            <input type="number" name="stock" value="<?= $producto["stock"] ?>">
        </label>
        <div class="clearfix"></div>
        <label class="col-md-3">Código:<br/>
            <input type="text" name="cod_producto" value="<?= $producto["cod_producto"] ?>">
        </label>
        <label class="col-md-3">Precio:<br/>
            <input type="text" name="precio" value="<?= $producto["precio"] ?>">
        </label>
        <!--
        <label class="col-md-3">Precio mayorista:<br/>
            <input type="text" name="precio_mayorista" value="<?= $producto["precio_mayorista"] ?>">
        </label>-->
        <label class="col-md-3">Peso:<br/>
            <input type="text" name="peso" value="<?= $producto["peso"] ?>">
        </label>
        <label class="col-md-3">Precio Descuento:<br/>
            <input type="text" name="precio_descuento" value="<?= $producto["precio_descuento"] ?>">
        </label>
        <label class="col-md-3">Url:<br/>
            <input type="text" name="url" value="<?= $producto["url"] ?>" id="url">
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea"><?= $producto["desarrollo"] ?></textarea>
        </label>
        <div class="clearfix"></div>
        <label class="col-md-12">Palabras claves dividas por ,<br/>
            <input type="text" name="keywords" value="<?= $producto["keywords"] ?>">
        </label>
        <label class="col-md-12">Descripción breve<br/>
            <textarea name="description"><?= $producto["description"] ?></textarea>
        </label>
        <br/>
        <div class="col-md-12">
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="meli">
                <label class="form-check-label" for="meli">¿Publicar en MercadoLibre?</label>
            </div>
        </div>
        <br/>
        <div class="col-md-12">
            <div class="row">
                <?php
                $imagenes->imagenesAdmin();
                ?>
            </div>
        </div>
        <label class="col-md-7">Imágenes:<br/>
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*"/>
        </label>
        <br/>
        <div class="clearfix"><br/></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Productos"/>
        </div>
</div>