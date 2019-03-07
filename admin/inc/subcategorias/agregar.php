<?php
$categorias    = new Clases\Categorias();
$subcategorias = new Clases\Subcategorias();
$categoriaCod  = isset($_GET["cod"]) ? $_GET["cod"] : '';
$categorias->set("cod",$categoriaCod);
$dataCategoria = $categorias->view();
$cate          = $categorias->list("");

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = substr(md5(uniqid(rand())), 0, 10);
    $subcategorias->set("cod", $cod);
    $subcategorias->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $subcategorias->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $subcategorias->add();
    $funciones->headerMove(URL . "/index.php?op=categorias");
}
?>

<div class="col-md-12">
    <h4>
        Subcategorías
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br/>
            <input type="text" name="titulo">
        </label>
        <label class="col-md-4">
            Categoria:<br/>
            <select name="categoria">
                <option value="<?= $dataCategoria["cod"]; ?>" selected>
                    <?= $dataCategoria["titulo"]; ?>
                </option>
                 <option>---------------</option>
                <?php
                foreach ($cate as $categoria) {
                    echo "<option value='".$categoria["cod"]."'>".$categoria["titulo"]."</option>";
                }
                ?>
            </select>
        </label>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Subcategoría" />
        </div>
    </form>
</div>
