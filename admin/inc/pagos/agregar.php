<?php
$pagos = new Clases\Pagos();
if (isset($_POST["agregar"])) {
    $count = 0;
    $cod = substr(md5(uniqid(rand())), 0, 10);
    $pagos->set("cod", $cod);
    $pagos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $pagos->set("leyenda", $funciones->antihack_mysqli(isset($_POST["leyenda"]) ? $_POST["leyenda"] : ''));
    $pagos->set("estado", $funciones->antihack_mysqli(isset($_POST["estado"]) ? $_POST["estado"] : ''));
    $pagos->set("aumento", $funciones->antihack_mysqli(isset($_POST["aumento"]) ? $_POST["aumento"] : ''));
    $pagos->set("disminuir", $funciones->antihack_mysqli(isset($_POST["disminuir"]) ? $_POST["disminuir"] : ''));
    $pagos->set("defecto", $funciones->antihack_mysqli(isset($_POST["defecto"]) ? $_POST["defecto"] : ''));
    $pagos->add();
    $funciones->headerMove(URL . "/index.php?op=pagos");
}
?>

<div class="col-md-12 ">
    <h4>Pagos</h4>
    <hr/>
    <form method="post" class="row">
        <label class="col-md-12">Método de pago:<br/>
            <input type="text" name="titulo">
        </label>
        <label class="col-md-12">Descripción del método de pago:<br/>
            <textarea name="leyenda"></textarea>
        </label>
        <label class="col-md-3">
            Estado
            <select name="estado" class="form-control">
                <option></option>
                <option value="0">Activo</option>
                <option value="1">Desactivado</option>
            </select>
        </label>
        <label class="col-md-3">
            Aumento (%)<br/>
            <input type="number" name="aumento" />
        </label>
        <label class="col-md-3">
            Disminuir(%)<br/>
            <input type="number" name="disminuir" />
        </label>
        <label class="col-md-3">
            Defecto
            <select name="defecto" class="form-control">
                <option></option>
                <option value="0">Carrito no cerrado</option>
                <option value="1">Pendiente</option>
                <option value="2">Exitoso</option>
                <option value="3">Enviado</option>
                <option value="4">Rechazado</option>
            </select>
        </label>
        <div class="clearfix"></div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Pagos"/>
        </div>
    </form>
</div>
