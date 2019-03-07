<?php
$contenidos = new Clases\Contenidos();

if (isset($_POST["agregar"])) {
    $contenidos->set("cod", $funciones->antihack_mysqli(isset($_POST["codigo"]) ? $_POST["codigo"] : ''));
    $contenidos->set("contenido", $funciones->antihack_mysqli(isset($_POST["contenido"]) ? $_POST["contenido"] : ''));
    $contenidos->add();
    $funciones->headerMove(URL . "/index.php?op=contenidos");
}
?>
<div class="mt-20">
    <div class="col-lg-12">
        <h4>Subir Contenidos</h4>
        <hr/>
    </div>
    <form method="post">
        <label class="col-lg-12">Título:
            <br/>
            <input type="text" name="codigo" value=""  />
        </label>
        <label class="col-lg-12" >Desarrollo:
            <br/>
            <textarea name="contenido" class="ckeditorTextarea"></textarea>
        </label>
        <div class="clearfix"></div>
        <div class="col-lg-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Agregar Contenido" />
        </div>
    </form>
</div>
