<?php
$envios = new Clases\Envios();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = substr(md5(uniqid(rand())), 0, 10);
    $envios->set("cod", $cod);
    $envios->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $envios->set("peso", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : ''));
    $envios->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    $envios->add();
    $funciones->headerMove(URL . "/index.php?op=envios");
}
?>

<div class="col-md-12 ">
    <h4>Envios</h4>
    <hr/>
    <form method="post" class="row"  >
        <label class="col-md-4">Título:<br/>
            <input type="text" name="titulo">
        </label>
        <label class="col-md-4">Peso:<br/>
            <input type="text" name="peso">
        </label>
        <label class="col-md-4">Precio:<br/>
            <input type="text" name="precio">
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Envio" />
        </div>
    </form>
</div>
