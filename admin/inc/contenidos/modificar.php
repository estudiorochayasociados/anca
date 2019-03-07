<?php
$contenidos = new Clases\Contenidos();

$id = $funciones->antihack_mysqli(isset($_GET['cod']) ? $_GET['cod'] : '');

$contenidos->set("id", $id);
$data = $contenidos->view();
if (isset($_POST["agregar"])) {
    //$contenidos->set("cod", $funciones->antihack_mysqli(isset($_POST["codigo"]) ? $_POST["codigo"] : ''));
    $contenidos->set("contenido", $funciones->antihack_mysqli(isset($_POST["contenido"]) ? $_POST["contenido"] : ''));
    $contenidos->edit();
    $funciones->headerMove(URL . "/index.php?op=contenidos");
}
?>
<div class="mt-20">
    <div class="col-lg-12">
        <h4>Modificar Contenidos</h4>
        <hr/>
    </div>
    <form method="post">
        <label class="col-lg-12">Título:
            <br/>
            <input type="text" name="titulo" value="<?=strtoupper($data["cod"]);?>" readonly />
        </label>
        <label class="col-lg-12" >Desarrollo:
            <br/>
            <textarea name="contenido" class="ckeditorTextarea"><?=$data["contenido"];?></textarea>
        </label>
        <div class="clearfix"></div>
        <div class="col-lg-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Contenido" />
        </div>
    </form>
</div>
