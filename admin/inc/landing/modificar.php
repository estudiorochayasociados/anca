<?php
$landing = new Clases\Landing();
$imagenes  = new Clases\Imagenes();
$zebra     = new Clases\Zebra_Image();

$cod       = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$landing->set("cod", $cod);
$landingInd = $landing->view();
$imagenes->set("cod", $landingInd["cod"]);
$imagenes->set("link", "landing&accion=modificar");

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'landing'"));

if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=landing&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = $landingInd["cod"];
    //$landing->set("id", $id);
    $landing->set("cod", $cod);
    $landing->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $landing->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $landing->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));
    $landing->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : ''));
    $landing->set("description", trim($funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : '')));
    $landing->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));

    foreach ($_FILES['files']['name'] as $f => $name) {
        $imgInicio = $_FILES["files"]["tmp_name"][$f];
        $tucadena  = $_FILES["files"]["name"][$f];
        $partes    = explode(".", $tucadena);
        $dom       = (count($partes) - 1);
        $dominio   = $partes[$dom];
        $prefijo   = substr(md5(uniqid(rand())), 0, 10);
        if ($dominio != '') {
            $destinoFinal     = "../assets/archivos/" . $prefijo . "." . $dominio;
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

    $landing->edit();
    $funciones->headerMove(URL . "/index.php?op=landing");
}
?>

<div class="col-md-12 ">
    <h4>
        Landing
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br/>
            <input type="text" value="<?=$landingInd["titulo"]?>" name="titulo">
        </label>
        <label class="col-md-4">
            Categoría:<br/>
            <select name="categoria">
                <option></option>
                <?php
                foreach ($data as $categoria) {
                    if($landingInd["categoria"] == $categoria["cod"]) {
                        echo "<option value='".$categoria["cod"]."' selected>".$categoria["titulo"]."</option>";
                    } else {
                        echo "<option value='".$categoria["cod"]."'>".$categoria["titulo"]."</option>";
                    } 
                }
                ?>
            </select>
        </label>
        <label class="col-md-4">
            Fecha:<br/>
            <input type="date" name="fecha" value="<?=$landingInd["fecha"]?>">
        </label>

        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea">
                <?=$landingInd["desarrollo"];?>
            </textarea>
        </label>
        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Palabras claves dividas por ,<br/>
            <input type="text" name="keywords" value="<?=$landingInd["keywords"]?>">
        </label>
        <label class="col-md-12">
            Descripción breve<br/>
            <textarea name="description"><?=$landingInd["description"]?></textarea>
        </label>
        <br/>
        <div class="col-md-12">
            <div class="row">
                <?php
                $imagenes->imagenesAdmin();
                ?>
            </div>
        </div>
        <div class="clearfix">
        </div>
        <label class="col-md-12">
            Imágenes:<br/>
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
        </label>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Landing" />
        </div>
    </form>
</div>