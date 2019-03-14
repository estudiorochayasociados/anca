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
    <h4>No hay pedidos todavía.</h4>
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
                    <div class="card-header bg-info" role="tab" id="heading">
                        <span class="blanco">Pedido <?= $value["cod"] ?></span>
                        <span class="hidden-xs hidden-sm blanco">- Fecha <?= $fecha ?></span>
                        <?php if ($value["estado"] == 0): ?>
                            <br class="visible-xs">
                            <span style="padding:5px;font-size:13px;width: 24%;text-align: center;"
                                  class="btn-danger pull-right hidden-xs">
                                Estado: Carrito no cerrado
                             </span>
                            <span style="padding:5px;font-size:13px;width: 100%;text-align: center;"
                                  class="btn-danger pull-right visible-xs">
                                Estado: Carrito no cerrado
                             </span>
                        <?php elseif ($value["estado"] == 1): ?>
                            <br class="visible-xs">
                            <span style="padding:5px;font-size:13px;width: 24%;text-align: center;"
                                  class="btn-warning pull-right hidden-xs">
                                Estado: Pago pendiente
                             </span>
                            <span style="padding:5px;font-size:13px;width: 100%;text-align: center;"
                                  class="btn-warning pull-right visible-xs">
                                Estado: Pago pendiente
                             </span>
                        <?php elseif ($value["estado"] == 2): ?>
                            <br class="visible-xs">
                            <span style="padding:5px;font-size:13px;width: 24%;text-align: center;"
                                  class="btn-success pull-right hidden-xs">
                                Estado: Pago aprobado
                             </span>
                            <span style="padding:5px;font-size:13px;width: 100%;text-align: center;"
                                  class="btn-success pull-right visible-xs">
                                Estado: Pago aprobado
                             </span>
                        <?php elseif ($value["estado"] == 3): ?>
                            <br class="visible-xs">
                            <span style="padding:5px;font-size:13px;width: 24%;text-align: center;"
                                  class="btn-primary pull-right hidden-xs">
                                Estado: Pago enviado
                             </span>
                            <span style="padding:5px;font-size:13px;width: 100%;text-align: center;"
                                  class="btn-primary pull-right visible-xs">
                                Estado: Pago enviado
                             </span>
                        <?php elseif ($value["estado"] == 4): ?>
                            <br class="visible-xs">
                            <span style="padding:5px;font-size:13px;width: 24%;text-align: center;"
                                  class="btn-danger pull-right hidden-xs">
                                Estado: Pago rechazado
                             </span>
                            <span style="padding:5px;font-size:13px;width: 100%;text-align: center;"
                                  class="btn-danger pull-right visible-xs">
                                Estado: Pago rechazado
                             </span>
                        <?php endif; ?>
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
                        <hr>
                        <span style="font-size:16px">
                    <b class="mb-10">FORMA DE PAGO</b>
                        <br class="visible-xs">
                    <span class="alert-info" style="border-radius: 10px; padding: 10px;">
                        <?php if ($value["tipo"] == 0): ?>
                            Transferencia bancaria
                        <?php elseif ($value["tipo"] == 1): ?>
                            Coordinar con vendedor
                        <?php elseif ($value["tipo"] == 2): ?>
                            Tarjeta de crédito o débito
                        <?php endif; ?>
                    </span>
                </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
?>
