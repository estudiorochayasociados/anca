<?php
$categorias = new Clases\Categorias();
$cod = isset($_GET["cod"]) ? $_GET["cod"] : '';
$categorias->set("cod", $cod);
$data = $categorias->view();
$imagenes = new Clases\Imagenes();
$zebra = new Clases\Zebra_Image();

if (isset($_POST["agregar"])) {
    $count = 0;
    $categorias->set("cod", $cod);
    $categorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $categorias->set("area", $funciones->antihack_mysqli(isset($_POST["area"]) ? $_POST["area"] : ''));
    $categorias->edit();
    $funciones->headerMove(URL . "/index.php?op=categorias");
}

$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$borrarImg = $funciones->antihack_mysqli(isset($_GET["borrarImg"]) ? $_GET["borrarImg"] : '');

$imagenes->set("cod", $data['cod']);
$imagenes->set("link", "modificar");


if ($borrarImg != '') {
    $imagenes->set("id", $borrarImg);
    $imagenes->delete();
    $funciones->headerMove(URL . "/index.php?op=categorias&accion=modificar&cod=$cod");
}

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $data["cod"];
    //$novedades->set("id", $id);
    $categorias->set("cod", $cod);
    $categorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $categorias->set("area", $funciones->antihack_mysqli(isset($_POST["area"]) ? $_POST["area"] : ''));
    $categorias->edit();
    $funciones->headerMove(URL . "/index.php?op=categorias");
}
?>

<div class="col-md-12">
    <h4>Categorías</h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">Título:<br/>
            <input type="text" value="<?= $data["titulo"] ?>" name="titulo">
        </label>
        <label class="col-md-4">Área:<br/>
            <select name="area">
                <option value="<?= $data["area"] ?>" selected><?= ucwords($data["area"]) ?></option>
                <option>---------------</option>
                <option value="sliders">Sliders</option>
                <option value="novedades">Novedades</option>
                <option value="productos">Productos</option>
                <option value="banners">Banners</option>
                <option value="landing">Landing</option>
            </select>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Categoría"/>
        </div>
    </form>
</div>
