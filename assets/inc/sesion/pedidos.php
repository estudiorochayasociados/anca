<?php
//Clases
$usuario = new Clases\Usuarios();
$pedidos = new Clases\Pedidos();

$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();

$filterPedidosAgrupados = array("usuario = '" . $usuarioData['cod'] . "' GROUP BY cod");
$pedidosArrayAgrupados = $pedidos->list($filterPedidosAgrupados);

$filterPedidosSinAgrupar = array("usuario = '" . $usuarioData['cod'] . "'");
$pedidosArraySinAgrupar = $pedidos->list($filterPedidosSinAgrupar);
asort($pedidosArraySinAgrupar);
?>
<?php
if (empty($pedidosArrayAgrupados)) {
    ?>
    <div class="container centro">
        <h4>No hay pedidos todavía.</h4>
    </div>
    <?php
} else {
    ?>
    <div class="col-md-12 mb-10">
        <?php foreach ($pedidosArrayAgrupados as $key => $value): ?>
            <?php $usuarios->set("cod", $value["usuario"]); ?>
            <?php $usuarioData = $usuarios->view(); ?>
            <?php $precioTotal = 0; ?>
            <?php $fecha = explode(" ", $value["fecha"]); ?>
            <?php $fecha1 = explode("-", $fecha[0]); ?>
            <?php $fecha1 = $fecha1[2] . '-' . $fecha1[1] . '-' . $fecha1[0] . '-'; ?>
            <?php $fecha = $fecha1 . $fecha[1]; ?>
            <div class="card">
                <a data-toggle="collapse" href="#collapse<?= $value["cod"] ?>" aria-expanded="false" aria-controls="collapse<?= $value["cod"] ?>" class="collapsed color_a">
                    <div class="card-header bg-info card-headerNew" role="tab" id="heading">
                        <div class="row">
                            <div class="col-md-10">
                                <span class="blanco">Pedido <?= $value["cod"] ?></span>
                                <span class="hidden-xs hidden-sm blanco">- Fecha <?= $fecha ?></span>
                            </div>
                            <div class="col-md-2">
                                <?php if ($value["estado"] == 0): ?>
                                    <br class="visible-xs">
                                    <button class="btn btn-lg btn-danger bm-30 pt-40 pb-40 boton hidden-xs">
                                        Estado: Carrito no cerrado
                                    </button>
                                    <button class="btn-danger pull-right boton-mobile visible-xs">
                                        Estado: Carrito no cerrado
                                    </button>
                                <?php elseif ($value["estado"] == 1): ?>
                                    <br class="visible-xs">
                                    <button style="padding:5px;font-size:13px"
                                            class="btn btn-lg btn-warning bm-30 pt-40 pb-40 boton hidden-xs">
                                        Estado: Pago pendiente
                                    </button>
                                    <button class="btn btn-lg btn-warning boton-mobile bm-30 pt-40 pb-40 pull-right visible-xs">
                                        Estado: Pago pendiente
                                    </button>
                                <?php elseif ($value["estado"] == 2): ?>
                                    <br class="visible-xs">
                                    <button class="btn btn-lg btn-success bm-30 pt-40 pb-40 boton hidden-xs">
                                        Estado: Pago aprobado
                                    </button>
                                    <button class="btn btn-lg btn-success bm-30 pt-40 pb-40 boton-mobile pull-right visible-xs">
                                        Estado: Pago aprobado
                                    </button>
                                <?php elseif ($value["estado"] == 3): ?>
                                    <br class="visible-xs">
                                    <button class="btn btn-lg btn-primary bm-30 pt-40 pb-40 boton hidden-xs">
                                        Estado: Pago enviado
                                    </button>
                                    <button class="btn btn-lg btn-primary bm-30 pt-40 pb-40 boton-mobile pull-right visible-xs">
                                        Estado: Pago enviado
                                    </button>
                                <?php elseif ($value["estado"] == 4): ?>
                                    <br class="visible-xs">
                                    <button class="btn btn-lg btn-danger bm-30 pt-40 pb-40 boton hidden-xs">
                                        Estado: Pago rechazado
                                    </button>
                                    <button class="btn btn-lg btn-danger bm-30 pt-40 pb-40 boton-mobile pull-right visible-xs">
                                        Estado: Pago rechazado
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <div id="collapse<?= $value["cod"] ?>" class="collapse" role="tabpanel"
                     aria-labelledby="headingOne" aria-expanded="false" style="height: 0px;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>
                                            Producto
                                        </th>
                                        <th class="hidden-xs">
                                            Cantidad
                                        </th>
                                        <th class="hidden-xs">
                                            Precio
                                        </th>
                                        <th>
                                            Precio Final
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($pedidosArraySinAgrupar as $key2 => $value2): ?>
                                        <?php if ($value2['cod'] == $value["cod"]): ?>
                                            <tr>
                                                <td><?= $value2["producto"] ?>
                                                    <p class="visible-xs">Cantidad: <?= $value2["cantidad"] ?></p>
                                                    <p class="visible-xs">Precio: $<?= $value2["precio"] ?></p>
                                                </td>
                                                <td class="hidden-xs"><?= $value2["cantidad"] ?></td>
                                                <td class="hidden-xs">$<?= $value2["precio"] ?></td>
                                                <td>$<?= $value2["precio"] * $value2["cantidad"] ?></td>
                                                <?php $precioTotal = $precioTotal + ($value2["precio"] * $value2["cantidad"]); ?>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td><b>TOTAL DE LA COMPRA</b></td>
                                        <td class="hidden-xs"></td>
                                        <td class="hidden-xs"></td>
                                        <td><b>$<?= $precioTotal ?></b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <span style="font-size:16px">
                    <b class="mb-10">FORMA DE PAGO:</b>
                        <br class="visible-xs">
                        <?php if ($value["tipo"] == 0): ?>
                            Transferencia bancaria
                        <?php elseif ($value["tipo"] == 1): ?>
                            Coordinar con vendedor
                        <?php elseif ($value["tipo"] == 2): ?>
                            Tarjeta de crédito o débito
                        <?php endif; ?>
                </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
?>
