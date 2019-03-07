<?php
$envios = new Clases\Envios();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$envios->set("cod", $cod);
$envios_ = $envios->view();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = $envios_["cod"];
    $envios->set("cod", $cod);
    $envios->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $envios->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    $envios->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : ''));
    $envios->edit();
    $funciones->headerMove(URL . "/index.php?op=envios&accion=modificar&cod=$cod");
}
?>

<div class="col-md-12 ">
    <h4>
        Envios
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br/>
            <input type="text" value="<?= $envios_["titulo"] ?>" name="titulo">
        </label>
        <label class="col-md-4">
            Peso:<br/>
            <input type="text" value="<?= $envios_["peso"] ?>" name="peso">
        </label>
        <label class="col-md-4">
            Precio:<br/>
            <input type="text" value="<?= $envios_["precio"] ?>" name="precio">
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Envio"/>
        </div>
    </form>
</div>