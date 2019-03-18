<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", TITULO . " | Carrito de compra");
$template->set("description", "Carrito de compra " . TITULO);
$template->set("keywords", "Carrito de compra " . TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();
//Clases
$productos = new Clases\Productos();
$imagenes = new Clases\Imagenes();
$categorias = new Clases\Categorias();
$banners = new Clases\Banner();
$carrito = new Clases\Carrito();
$envios = new Clases\Envios();
$pagos = new Clases\Pagos();
$carro = $carrito->return();
$carroEnvio = $carrito->checkEnvio();
if (count($carro) == 0) {
    $funciones->headerMove(URL . "/productos");
}
?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-area mt-30">
        <div class="container">
            <div class="breadcrumb">
                <ul class="d-flex align-items-center">
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li class="active"><a href="">Tu carrito</a></li>
                </ul>
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Breadcrumb End -->
    <!-- Cart Main Area Start -->
    <div class="cart-main-area mt-15">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-10">
                    <div class="envio">
                        <?php
                        $metodos_de_envios = $envios->list(array("peso >= " . $carrito->peso_final() . " OR peso = 0"));
                        if ($carroEnvio == '') {
                            echo "<h3>Seleccioná el envió que más te convenga:</h3>";
                            if (isset($_POST["envio"])) {
                                if ($carroEnvio != '') {
                                    $carrito->delete($carroEnvio);
                                }
                                $envio_final = $_POST["envio"];
                                $envios->set("cod", $envio_final);
                                $envio_final_ = $envios->view();
                                $carrito->set("id", "Envio-Seleccion");
                                $carrito->set("cantidad", 1);
                                $carrito->set("titulo", $envio_final_["titulo"]);
                                $carrito->set("precio", $envio_final_["precio"]);
                                $carrito->add();
                                $funciones->headerMove(CANONICAL . "");
                            }
                            ?>
                            <div class="clearfix"></div>
                            <form method="post" class="" id="envio">
                                <select name="envio" class="form-control mt-10" id="envio" onchange="this.form.submit()">
                                    <option value="" selected disabled>Elegir envío</option>
                                    <?php
                                    foreach ($metodos_de_envios as $metodos_de_envio_) {
                                        if ($metodos_de_envio_["precio"] == 0) {
                                            $metodos_de_envio_precio = "¡Gratis!";
                                        } else {
                                            $metodos_de_envio_precio = "$" . $metodos_de_envio_["precio"];
                                        }
                                        echo "<option value='" . $metodos_de_envio_["cod"] . "'>" . $metodos_de_envio_["titulo"] . " -> " . $metodos_de_envio_precio . "</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                            <hr/>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12">
                    <!-- Form Start -->
                    <!-- Table Content Start -->
                    <div class="table-content table-responsive mb-45">
                        <table>
                            <thead>
                            <tr>
                                <th class="product-remove"></th>
                                <th class="product-thumbnail hidden-xs">Imagen</th>
                                <th class="product-name">Producto</th>
                                <th class="product-price hidden-xs">Precio</th>
                                <th class="product-quantity hidden-xs">Cantidad</th>
                                <th class="product-subtotal">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($_GET["remover"])) {
                                $carroPago = $carrito->checkPago();
                                if ($carroPago != '') {
                                    $carrito->delete($carroPago);
                                }
                                $carroEnvio = $carrito->checkEnvio();
                                if ($carroEnvio != '') {
                                    $carrito->delete($carroEnvio);
                                }
                                $carrito->delete($_GET["remover"]);
                                $funciones->headerMove(URL . "/carrito");
                            }

                            $i = 0;
                            $precio = 0;
                            foreach ($carro as $key => $carroItem) {

                                $precio += ($carroItem["precio"] * $carroItem["cantidad"]);
                                $opciones = @implode(" - ", $carroItem["opciones"]);
                                if ($carroItem["id"] == "Envio-Seleccion" || $carroItem["id"] == "Metodo-Pago") {
                                    $clase = "text-bold";
                                    $none = "hidden";
                                } else {
                                    $clase;
                                    $none = "";
                                }
                                $productos->set("id", $carroItem['id']);
                                $pro = $productos->view();
                                $imagenes->set("cod", $pro['cod']);
                                $img = $imagenes->view();
                                ?>
                                <tr>
                                    <td class="product-remove">
                                        <a href="<?= URL ?>/carrito.php?remover=<?= $key ?>">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td class="product-thumbnail hidden-xs"
                                        style="width:70px;height:100px;background:url(<?= URL . '/' . $img['ruta']; ?>) no-repeat center center/contain">
                                    </td>
                                    <td class="product-name">
                                        <span class="amount hidden-xs"><?= mb_strtoupper($carroItem["titulo"]); ?></span>
                                        <span class="amount d-md-none">
                                            <?= mb_strtoupper($carroItem["titulo"]); ?>
                                                <p class="<?= $none ?>">Precio: <?= "$" . $carroItem["precio"]; ?></p>
                                                <p class="<?= $none ?>">Cantidad: <?= $carroItem["cantidad"]; ?></p>
                                        </span>
                                    </td>
                                    <td class="product-price hidden-xs">
                                        <span class="amount <?= $none ?>"><?= "$" . $carroItem["precio"]; ?></span>
                                    </td>
                                    <td class="product-quantity hidden-xs ">
                                        <span class="amount <?= $none ?>"><?= $carroItem["cantidad"]; ?></span>
                                    </td>
                                    <td class="product-subtotal">
                                        <span class="amount"><?php
                                            if ($carroItem["precio"] != 0) {
                                                echo "$" . ($carroItem["precio"] * $carroItem["cantidad"]);
                                            } else {
                                                echo "¡Gratis!";
                                            }
                                            ?></span>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Table Content Start -->
                    <div class="row mb-10">
                        <!-- Cart Button Start -->
                        <div class="col-md-8 col-sm-12 <?php if ($carroEnvio!=''){echo "fondo"; } ?>">
                            <form class="" method="post">
                                <!---->
                                <?php
                                $metodo = $funciones->antihack_mysqli(isset($_POST["metodos-pago"]) ? $_POST["metodos-pago"] : '');
                                $metodo_get = $funciones->antihack_mysqli(isset($_GET["metodos-pago"]) ? $_GET["metodos-pago"] : '');

                                if ($metodo != '') {
                                    $key_metodo = $carrito->checkPago();
                                    $carrito->delete($key_metodo);
                                    $pagos->set("cod", $metodo);
                                    $pago__ = $pagos->view();
                                    $precio_final_metodo = $carrito->precio_total();
                                    if ($pago__["aumento"] != 0 || $pago__["disminuir"] != '') {
                                        if ($pago__["aumento"]) {
                                            $numero = (($precio_final_metodo * $pago__["aumento"]) / 100);
                                            $carrito->set("id", "Metodo-Pago");
                                            $carrito->set("cantidad", 1);
                                            $carrito->set("titulo", "CARGO +" . $pago__['aumento'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                            $carrito->set("precio", $numero);
                                            $carrito->add();
                                        } else {
                                            $numero = (($precio_final_metodo * $pago__["disminuir"]) / 100);
                                            $carrito->set("id", "Metodo-Pago");
                                            $carrito->set("cantidad", 1);
                                            $carrito->set("titulo", "DESCUENTO -" . $pago__['disminuir'] . "% / " . mb_strtoupper($pago__["titulo"]));
                                            $carrito->set("precio", "-" . $numero);
                                            $carrito->add();
                                        }

                                        $funciones->headerMove(CANONICAL . "/" . $metodo);
                                    }
                                }
                                ?>
                                <div class="cart_totals_area">
                                    <?php
                                    if ($carroEnvio != '') {
                                        ?>
                                        <h4 class="metodos">Metodos de pago</h4>
                                        <?php
                                    } elseif ($carroEnvio == '') {
                                        ?>
                                        <h4>Elegir envío</h4>
                                        <?php
                                    }
                                    ?>
                                    <div class="cart_t_list">
                                        <div class="media">
                                            <div class="media-body">
                                                <?php
                                                if ($carroEnvio == '') {
                                                    ?>
                                                    <span class="btn boton-envio " onclick="$('#envio').addClass('alert alert-danger');">¿CÓMO PEREFERÍS EL ENVÍO DEL PEDIDO?</span>
                                                    <p class="checkout text-bold">¡Necesitamos que nos digas como querés realizar <br/>tu envío para que lo tengas listo cuanto antes!</p>
                                                    <?php
                                                } else {
                                                    $lista_pagos = $pagos->list(array(" estado = 0 "));
                                                    foreach ($lista_pagos as $pago) {
                                                        $precio_total = $carrito->precioSinMetodoDePago();
                                                        if ($pago["aumento"] != 0 || $pago["disminuir"] != 0) {
                                                            if ($pago["aumento"] > 0) {
                                                                $precio_total = (($precio_total * $pago["aumento"]) / 100) + $precio_total;
                                                            } else {
                                                                $precio_total = $precio_total - (($precio_total * $pago["disminuir"]) / 100);
                                                            }
                                                        }
                                                        ?>
                                                        <div class="radioButtonPay mb-10 metodos">
                                                            <input type="radio"
                                                                   id="<?= ($pago["cod"]) ?>"
                                                                   name="metodos-pago"
                                                                   value="<?= ($pago["cod"]) ?>"
                                                                   onclick="this.form.submit()" <?php if ($metodo_get === $pago["cod"]) {
                                                                echo " checked ";
                                                            } ?>>
                                                            <label for="<?= ($pago["cod"]) ?>">
                                                                <b><?= mb_strtoupper($pago["titulo"]) ?></b>
                                                            </label>
                                                            <p>
                                                                <?= $pago["leyenda"] . " | Total: $" . $precio_total; ?>
                                                            </p>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!---->
                            </form>
                        </div>
                        <!-- Cart Button Start -->
                        <!-- Cart Totals Start -->
                        <div class="col-md-4 col-sm-12">
                            <?php if ($metodo_get != '') { ?>
                                <div class="cart_totals float-md-right text-md-right">
                                    <br/>
                                    <table class="float-md-right">
                                        <tbody>
                                        <tr class="order-total">
                                            <th>Total</th>
                                            <td>
                                                <strong><span class="amount"><?= number_format($carrito->precio_total(), "2", ",", "."); ?></span></strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="wc-proceed-to-checkout">
                                        <a href="<?= URL ?>/pagar/<?= $metodo_get ?>">PAGAR EL CARRITO</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Cart Totals End -->
                    </div>
                    <!-- Row End -->
                    <!-- Form End -->
                </div>
            </div>
            <!-- Row End -->
        </div>
    </div>
<?php
$template->themeEnd();
?>