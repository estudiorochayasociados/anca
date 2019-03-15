<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$carrito = new Clases\Carrito();
//Producto
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$producto->set("cod", $cod);
$producto_data = $producto->view_();
//Productos relacionados
$categoria_cod = $producto_data['data']['categoria'];
$filter = array("categoria='$categoria_cod'", "cod!='$cod'");
$productos_relacionados_data = $producto->listWithOps($filter, '', '10');
//
if (!empty($producto_data['imagenes'][0]['ruta'])) {
    $ruta_ = URL . "/" . $producto_data['imagenes'][0]['ruta'];
} else {
    $ruta_ = '';
}
$template->set("title", TITULO . " | " . ucfirst(strip_tags($producto_data['data']['titulo'])));
$template->set("description", ucfirst(strip_tags($producto_data['data']['description'])));
$template->set("keywords", ucfirst(strip_tags($producto_data['data']['titulo'])));
$template->set("imagen", $ruta_);
$template->set("favicon", FAVICON);
$template->themeInit();
//Carro
$carro = $carrito->return();
$url_limpia = CANONICAL;
$url_limpia = str_replace("?success", "", $url_limpia);
$url_limpia = str_replace("?error", "", $url_limpia);
?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-area mt-30">
        <div class="container">
            <div class="breadcrumb">
                <ul class="d-flex align-items-center">
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li><a href="<?= URL ?>/productos">Productos</a></li>
                    <li class="active"><a href=""><?= ucfirst($producto_data['data']['titulo']); ?></a></li>
                </ul>
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Breadcrumb End -->
    <!-- Product Thumbnail Start -->
    <div class="main-product-thumbnail pt-15">
        <div class="container">
            <div class="thumb-bg">
                <div class="row">
                    <!-- Main Thumbnail Image Start -->
                    <div class="col-lg-5 mb-all-40">
                        <!-- Thumbnail Large Image start -->
                        <div class="tab-content">
                            <?php
                            foreach ($producto_data['imagenes'] as $key => $img) {
                                ?>
                                <div id="imagen<?= $img['id']; ?>" class="tab-pane fade show <?php if ($key == 0) {
                                    echo 'active';
                                } ?>">
                                    <div style="height:300px;background:url(<?= URL . '/' . $img['ruta']; ?>) no-repeat center center/contain;">

                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- Thumbnail Large Image End -->
                        <?php
                        if (@count($producto_data['imagenes']) > 1) {
                            ?>
                            <!-- Thumbnail Image End -->
                            <div class="product-thumbnail mt-15">
                                <div class="thumb-menu owl-carousel nav tabs-area" role="tablist">
                                    <?php
                                    foreach ($producto_data['imagenes'] as $key => $img) {
                                        ?>
                                        <a class="<?php if ($key == 0) {
                                            echo 'active';
                                        } ?>" data-toggle="tab" href="#imagen<?= $img['id'] ?>">
                                            <div style="height:100px;background:url(<?= URL . '/' . $img['ruta']; ?>) no-repeat center center/contain;">

                                            </div>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- Thumbnail image end -->
                            <?php
                        }
                        ?>
                    </div>
                    <!-- Main Thumbnail Image End -->
                    <!-- Thumbnail Description Start -->
                    <div class="col-lg-7">
                        <div class="thubnail-desc fix">
                            <h3 id="title" class="product-header"><?= ucfirst($producto_data['data']['titulo']); ?></h3>
                            <div class="pro-price mtb-30">
                                <p class="d-flex align-items-center">
                                    <?php
                                    if ($producto_data['data']['precio_descuento'] > 0) {
                                        ?>
                                        <span class="prev-price"><?= $producto_data['data']['precio_descuento'] ?></span>
                                        <span class="price">$<?= $producto_data['data']['precio'] ?></span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="price">$<?= $producto_data['data']['precio'] ?></span>
                                        <?php
                                    }
                                    ?>
                                </p>
                            </div>
                            <?php
                            if (!empty($producto_data['data']['description'])) {
                                ?>
                                <p class="mb-20 pro-desc-details">
                                    <?= ucfirst($producto_data['data']['description']); ?>
                                </p>
                                <?php
                            }

                            if ($producto_data['data']['stock'] > 0) {
                                ?>
                                <?php
                                if (isset($_POST["enviar"])) {
                                    $carroEnvio = $carrito->checkEnvio();
                                    if ($carroEnvio != '') {
                                        $carrito->delete($carroEnvio);
                                    }

                                    $carroPago = $carrito->checkPago();
                                    if ($carroPago != '') {
                                        $carrito->delete($carroPago);
                                    }
                                    $carrito->set("id", $producto_data['data']['id']);
                                    $carrito->set("cantidad", $_POST["cantidad"]);
                                    $carrito->set("titulo", $producto_data['data']['titulo']);
                                    $carrito->set("precio", $producto_data['data']['precio']);
                                    $carrito->set("stock", $producto_data['data']['stock']);
                                    if (($producto_data['data']['precio_descuento'] <= 0) || $producto_data['data']["precio_descuento"] == '') {
                                        $carrito->set("precio", $producto_data['data']['precio']);
                                    } else {
                                        $carrito->set("precio", $producto_data['data']['precio_descuento']);
                                    }

                                    if (is_array($_SESSION["usuarios"])) {
                                        if ($_SESSION["usuarios"]["descuento"] == 1) {
                                            if ($producto_data['data']['precio'] != $producto_data['data']['precio_mayorista'] && $producto_data['data']['precio_mayorista'] != 0) {
                                                $carrito->set("precio", $producto_data['data']['precio_mayorista']);
                                            } else {
                                                $carrito->set("precio", $producto_data['data']['precio']);
                                            }
                                        } else {
                                            $carrito->set("precio", $producto_data['data']['precio']);
                                        }
                                    }


                                    if ($carrito->add()) {
                                        $funciones->headerMove($url_limpia."?success#title");
                                    } else {
                                        $funciones->headerMove($url_limpia."?error#title");
                                    }
                                }
                                if (strpos(CANONICAL, "success") == true) {
                                    echo "<div class='alert alert-success'>Agregaste un producto a tu carrito, querés <a href='" . URL . "/carrito'><b>pasar por caja</b></a> o <a href='" . URL . "/productos'><b>seguir comprando</b></a></div>";
                                }
                                if (strpos(CANONICAL, "error") == true) {
                                    echo "<div class='alert alert-danger'>No se puede agregar por falta de stock, compruebe si ya posee este producto en su carrito.</div>";
                                }
                                ?>
                                <form method="post">
                                    <div class="box-quantity d-flex hot-product2 mt-5">
                                        <input max="<?= $producto_data['data']['stock'] ?>"
                                               min="1"
                                               type="number"
                                               name="cantidad"
                                               id="sst"
                                               maxlength="12"
                                               value="1"
                                               title="Ingresar valores con respecto al stock"
                                               class="quantity mr-15"
                                               oninvalid="this.setCustomValidity('Stock disponible: <?= $producto_data['data']['stock'] ?>')"
                                               oninput="this.setCustomValidity('')"
                                               onkeydown="return (event.keyCode!=13);">
                                        <div class="pro-actions">
                                            <div class="actions-primary" onclick="this.form.submit();">
                                                <button class="button-buy" name="enviar">Añadir al carrito</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>
                            <div class="pro-ref mt-20">
                                <p>
                                    <?php
                                    if ($producto_data['data']['stock'] > 0) {
                                        ?>
                                        <span class="in-stock"><i class="ion-checkmark-round"></i> CON STOCK</span>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="non-stock"><i class="ion-android-alert"></i> SIN STOCK</span>
                                        <?php
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="socila-sharing mt-25">
                                <div class="shareing_icon">
                                    <h5 class="pt-40 pb-10">Compartir :</h5>
                                    <div class="a2a_kit a2a_kit_size_32 a2a_default_style pb-40">
                                        <a class="a2a_button_facebook"></a>
                                        <a class="a2a_button_twitter"></a>
                                        <a class="a2a_button_google_plus"></a>
                                        <a class="a2a_button_pinterest"></a>
                                        <a class="a2a_button_whatsapp"></a>
                                        <a class="a2a_button_facebook_messenger"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Thumbnail Description End -->
                </div>
                <!-- Row End -->
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Product Thumbnail End -->
    <!-- Product Thumbnail Description Start -->
    <div class="thumnail-desc pt-15 pb-15">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <ul class="main-thumb-desc nav tabs-area" role="tablist">
                        <li><a class="active" data-toggle="tab" href="#dtail">Descripción</a></li>
                    </ul>
                    <!-- Product Thumbnail Tab Content Start -->
                    <div class="tab-content thumb-content border-default">
                        <div id="dtail" class="tab-pane fade show active">
                            <p><?= ucfirst($producto_data['data']['desarrollo']); ?></p>
                        </div>
                    </div>
                    <!-- Product Thumbnail Tab Content End -->
                </div>
            </div>
            <!-- Row End -->
        </div>
        <!-- Container End -->
    </div>
    <!-- Product Thumbnail Description End -->
<?php
if (!empty($productos_relacionados_data)) {
    ?>
    <!-- Realted Products Start Here -->
    <div class="hot-deal-products off-white-bg pt-15 pb-90 pt-sm-60 pb-sm-50">
        <div class="container">
            <!-- Product Title Start -->
            <div class="post-title pb-30">
                <h2>Productos relacionados</h2>
            </div>
            <!-- Product Title End -->
            <!-- Hot Deal Product Activation Start -->
            <div class="hot-deal-active owl-carousel">
                <?php
                foreach ($productos_relacionados_data as $prod) {
                    ?>
                    <!-- Single Product Start -->
                    <div class="single-product">
                        <!-- Product Image Start -->
                        <div class="pro-img" style="padding: 5px;">
                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                <div style="height:260px;background:url(<?= URL . '/' . $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/contain;">

                                </div>
                            </a>
                        </div>
                        <!-- Product Image End -->
                        <!-- Product Content Start -->
                        <div class="pro-content">
                            <div class="pro-info">
                                <h4>
                                    <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                        <?= ucfirst(substr(strip_tags($prod['data']['titulo']), 0, 40)); ?>
                                    </a>
                                </h4>
                                <p style="text-align: center;">
                                    <?php
                                    if ($prod['data']['precio_descuento'] > 0) {
                                        ?>
                                        <span class="price">$ <?= $prod['data']['precio_descuento'] ?></span>
                                        <del class="prev-price">$ <?= $prod['data']['precio'] ?></del>
                                        <?php
                                    } else {
                                        ?>
                                        <span class="price">$ <?= $prod['data']['precio'] ?></span>
                                        <?php
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="pro-actions">
                                <div class="actions-primary">
                                    <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>" title="Ver">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- Product Content End -->
                    </div>
                    <!-- Single Product End -->
                    <?php
                }
                ?>
            </div>
            <!-- Hot Deal Product Active End -->
        </div>
        <!-- Container End -->
    </div>
    <!-- Realated Products End Here -->
    <?php
}
?>
<?php
$template->themeEnd();
?>