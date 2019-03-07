<?php
$contenidos = new Clases\Contenidos();
?>
<div class="mt-20">
    <div class="col-lg-12 col-md-12">
        <h4>
            Ver Contenidos
        </h4>
        <hr/>
        <input class="form-control" id="myInput" type="text" placeholder="Buscar..">
        <hr/>
        <table class="table  table-bordered  ">
            <thead>
                <th>
                    Título
                </th>
                <th>
                    Ajustes
                </th>
            </thead>
            <tbody>
                <?php
                $filter     = array();
                $data = $contenidos->list("");
                if (is_array($data)) {
                    for ($i = 0; $i < count($data); $i++) {
                        echo "<tr>";
                        echo "<td>" . strtoupper($data[$i]["cod"]) . "</td>";
                        echo "<td>";
                        echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="' . URL . '/index.php?op=contenidos&accion=modificar&cod=' . $data[$i]["id"] . '">
                        <i class="fa fa-cog"></i></a>';

                        echo '<a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="' . URL . '/index.php?op=contenidos&accion=ver&borrar=' . $data[$i]["id"] . '">
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
$borrar = $funciones->antihack_mysqli(isset($_GET["borrar"]) ? $_GET["borrar"] : '');
if ($borrar != '') {
    $contenidos->set("id", $borrar);
    $contenidos->delete();
    $funciones->headerMove(URL . "/index.php?op=contenidos");
}
?>

