<?php
$pagos = new Clases\Pagos();
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$pagos->set("cod", $cod);
$pagos_ = $pagos->view();

if (isset($_POST["agregar"])) {
    $pagos->set("cod", $pagos_["cod"]);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : ''));
    $pagos->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : ''));
    $pagos->set("aumento", $funciones->antihack_mysqli(isset($_POST["aumento"]) ? $_POST["aumento"] : ''));
    $pagos->set("disminuir", $funciones->antihack_mysqli(isset($_POST["disminuir"]) ? $_POST["disminuir"] : ''));
    $pagos->set("defecto", $funciones->antihack_mysqli(isset($_POST["defecto"]) ? $_POST["defecto"] : ''));
    $pagos->edit();
    $funciones->headerMove(URL . "/index.php?op=pagos");
}
?>

<div class="col-md-12 ">
    <h4>Pagos</h4>
    <hr/>
    <form method="post" class="row">
        <label class="col-md-12">Método de pago:<br/>
            <input type="text" name="titulo" value="<?= $pagos_['titulo'] ?>" />
        </label>
        <label class="col-md-12">Descripción del método de pago:<br/>
            <textarea name="leyenda"><?= $pagos_['leyenda'] ?></textarea>
        </label>
        <label class="col-md-3">
            Estado
            <select name="estado"   class="form-control">
                <option></option>
                <option value="0" <?php if($pagos_['estado'] == 0) {echo "selected";} ?> >Activo</option>
                <option value="1" <?php if($pagos_['estado'] == 1) {echo "selected";} ?> >Desactivado</option>
            </select>
        </label>
        <label class="col-md-3">
            Aumento (%)<br/>
            <input type="number" name="aumento" value="<?= $pagos_['aumento'] ?>" />
        </label>
        <label class="col-md-3">
            Disminuir(%)<br/>
            <input type="number" name="disminuir" value="<?= $pagos_['disminuir'] ?>" />
        </label>
        <label class="col-md-3">
            Defecto
            <select name="defecto" class="form-control">
                <option></option>
                <option value="0" <?php if($pagos_["defecto"] == 0) { echo "selected"; } ?>>Carrito no cerrado</option>
                <option value="1" <?php if($pagos_["defecto"] == 1) { echo "selected"; } ?>>Pendiente</option>
                <option value="2" <?php if($pagos_["defecto"] == 2) { echo "selected"; } ?>>Exitoso</option>
                <option value="3" <?php if($pagos_["defecto"] == 3) { echo "selected"; } ?>>Enviado</option>
                <option value="4" <?php if($pagos_["defecto"] == 4) { echo "selected"; } ?>>Rechazado</option>
            </select>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Modificar Pagos"/>
        </div>
    </form>
</div>
