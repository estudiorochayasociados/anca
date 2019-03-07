<?php
$banners = new Clases\Banner();
$imagenes  = new Clases\Imagenes();
//$filter = array();
$data      = $banners->list();
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            Banners
            <a class="btn btn-success pull-right" href="<?=URL?>/index.php?op=banners&accion=agregar">
                AGREGAR BANNER
            </a>
        </h4>
        <hr/>
        <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
        <hr/>
        <table class="table  table-bordered  ">
            <thead>
                <th>
                    Nombre
                </th>
                <th>
                    Ajustes
                </th>
            </thead>
            <tbody>
                <?php
                if (is_array($data)) {
                    foreach ($data as $val) {
                        echo "<tr>";
                        echo "<tr>";
                        echo "<td>";
                        echo strtoupper($val["nombre"]);
                        echo "</td>";
                        echo "<td>";
                        /*echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Agregar" href="' . URL . '/index.php?op=subcategorias&accion=agregar&cod=' . $val["cod"] . '">
                        <i class="fa fa-plus"></i> SUBCATEGORÍA</a>';*/
                        echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URL . '/index.php?op=banners&accion=modificar&cod=' . $val["cod"] . '"><i class="fa fa-cog"></i></a>';

                        echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URL . '/index.php?op=banners&accion=ver&borrar=' . $val["cod"] . '">
                        <i class="fa fa-trash"></i></a>';
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if (isset($_GET["borrar"])) {
    $cod = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
    $banners->set("cod", $cod);
    $imagenes->set("cod", $cod);
    $banners->delete();
    $imagenes->deleteAll();
    $funciones->headerMove(URL . "/index.php?op=banners");
}
?>