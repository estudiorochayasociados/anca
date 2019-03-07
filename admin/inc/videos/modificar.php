<?php
$videos = new Clases\Videos();
$id     = $funciones->antihack_mysqli(isset($_GET['id']) ? $_GET['id'] : '');
$videos->set("id", $id);
$data = $videos->view();
if (isset($_POST["agregar"])) {
    $videos->set("id", $id);
    $videos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $videos->set("link", $funciones->antihack_mysqli(isset($_POST["link"]) ? $_POST["link"] : ''));
    $videos->edit();
    $funciones->headerMove(URL . "/index.php?op=videos");
}
?>
<div class="mt-20">
    <div class="col-md-12">
        <h4>Modificar Videos</h4>
        <hr/>
    </div>
    <form method="post">
        <label class="col-md-12">Título:
            <br/>
            <input type="text" name="titulo" value="<?=strtoupper($data["titulo"]);?>"  />
        </label>
        <label class="col-md-12" >Link de youtube:
            <br/>
            <input type="url" name="link" value="<?=strtoupper($data["link"]);?>"  />
        </label>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Video" />
        </div>
    </form>
</div>
