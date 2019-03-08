<?php
$usuario = new Clases\Usuarios();
$carrito = new Clases\Carrito();
$producto_nav = new Clases\Productos();
$countCarrito = count($carrito->return());
$carro = $carrito->return();
if (isset($_GET['logout'])) {
    $usuario->logout();
}
?>
<!-- Banner Popup Start -->
<div class="popup_banner">
    <span class="popup_off_banner">×</span>
    <div class="banner_popup_area">
        <img src="<?= URL ?>/assets/img/banner/pop-banner.jpg" alt="">
    </div>
</div>
<!-- Banner Popup End -->
<!-- Newsletter Popup Start -->
<div class="popup_wrapper">
    <div class="test">
        <span class="popup_off">Close</span>
        <div class="subscribe_area text-center mt-60">
            <h2>Newsletter</h2>
            <p>Subscribe to the Truemart mailing list to receive updates on new arrivals, special offers and other discount information.</p>
            <div class="subscribe-form-group">
                <form action="#">
                    <input autocomplete="off" type="text" name="message" id="message" placeholder="Enter your email address">
                    <button type="submit">subscribe</button>
                </form>
            </div>
            <div class="subscribe-bottom mt-15">
                <input type="checkbox" id="newsletter-permission">
                <label for="newsletter-permission">Don't show this popup again</label>
            </div>
        </div>
    </div>
</div>
<!-- Newsletter Popup End -->

<!-- Main Header Area Start Here -->
<header>
    <!-- Header Top Start Here -->
    <div class="header-top-area">
        <div class="container">
            <!-- Header Top Start -->
            <div class="header-top">
                <ul>
                    <li><span><i class="ion-android-call"></i> <?= TELEFONO ?></li>
                    </span>
                    <li><span><i class="ion-android-drafts"></i> <?= EMAIL ?></li>
                    </li>
                </ul>
                <ul>
                    <?php
                    if (!empty($_SESSION['usuarios'])) {
                        ?>
                        <li>
                            <a href="#">Mi cuenta<i class="lnr lnr-chevron-down"></i></a>
                            <!-- Dropdown Start -->
                            <ul class="ht-dropdown">
                                <li><a href="">Perfil</a></li>
                                <li><a href="">Pedidos</a></li>
                                <li><a href="<?=URL?>?logout">Salir</a></li>
                            </ul>
                            <!-- Dropdown End -->
                        </li>
                        <?php
                    } else {
                        ?>
                        <li>
                            <a href="#">Cuenta<i class="lnr lnr-chevron-down"></i></a>
                            <!-- Dropdown Start -->
                            <ul class="ht-dropdown">
                                <li><a href="<?= URL ?>/ingreso">Ingresar</a></li>
                                <li><a href="<?= URL ?>/registro">Registrar</a></li>
                            </ul>
                            <!-- Dropdown End -->
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <!-- Header Top End -->
        </div>
        <!-- Container End -->
    </div>
    <!-- Header Top End Here -->
    <!-- Header Middle Start Here -->
    <div class="header-middle ptb-15">
        <div class="container">
            <div class="row align-items-center no-gutters">
                <div class="col-lg-3 col-md-12">
                    <div class="logo mb-all-30">
                        <a href="index.html"><img src="<?= URL ?>/assets/img/logo/logo.png" alt="logo-image"></a>
                    </div>
                </div>
                <!-- Categorie Search Box Start Here -->
                <div class="col-lg-5 col-md-8 ml-auto mr-auto col-10">
                    <div class="categorie-search-box">
                        <form action="#">
                            <input type="text" name="search" placeholder="Buscar producto">
                            <button><i class="lnr lnr-magnifier"></i></button>
                        </form>
                    </div>
                </div>
                <!-- Categorie Search Box End Here -->
                <!-- Cart Box Start Here -->
                <div class="col-lg-4 col-md-12">
                    <div class="cart-box mt-all-30">
                        <ul class="d-flex justify-content-lg-end justify-content-center align-items-center">
                            <li>
                                <a href="#"><i class="lnr lnr-cart"></i>
                                    <span class="my-cart">
                                        <?php if (!empty($_SESSION['carrito'])) {
                                            echo '<span class="total-pro">' . @count($_SESSION['carrito']) . '</span>';
                                        } ?>
                                    </span>
                                </a>
                                <ul class="ht-dropdown cart-box-width">
                                    <li>
                                        <?php
                                        if (!empty($carro)) {
                                            $precio = 0;
                                            foreach ($carro as $car) {
                                                $precio += ($car["precio"] * $car["cantidad"]);
                                                ?>
                                                <!-- Cart Box Start -->
                                                <div class="single-cart-box">
                                                    <div class="cart-content">
                                                        <h6><a href=""><?= ucfirst(substr(strip_tags($car['titulo']), 0, 40)); ?> </a></h6>
                                                        <?php
                                                        if ($car['precio'] > 0) {
                                                            ?>
                                                            <span class="cart-price">$<?= $car['precio'] ?></span>
                                                            <?php
                                                        } else {
                                                            if ($car['id'] != 'Metodo-Pago') {
                                                                ?>
                                                                <span class="cart-price">Gratis!</span>
                                                                <?php
                                                            }
                                                            ?>
                                                            <?php
                                                        }
                                                        if ($car['id'] != 'Metodo-Pago' || $car['id'] != 'Envio-Seleccion') {
                                                            ?>
                                                            <span>Cantidad: <?= ucfirst($car['cantidad']) ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- Cart Box End -->
                                                <?php
                                            }
                                            ?>

                                            <!-- Cart Footer Inner Start -->
                                            <div class="cart-footer">
                                                <ul class="price-content">
                                                    <li>Total <span>$<?= $precio; ?></span></li>
                                                </ul>
                                                <div class="cart-actions text-center">
                                                    <a class="cart-checkout" href="<?= URL ?>/carrito">Pagar</a>
                                                </div>
                                            </div>
                                            <!-- Cart Footer Inner End -->
                                            <?php
                                        } else {
                                            ?>
                                            <!-- Cart Box Start -->
                                            <div class="single-cart-box">
                                                <div class="cart-content" style="text-align: center;">
                                                    <h6><a href="">El carrito se encuentra vacío </a></h6>
                                                </div>
                                            </div>
                                            <!-- Cart Box End -->
                                            <?php
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Cart Box End Here -->
            </div>
            <!-- Row End -->
        </div>
        <!-- Container End -->
    </div>
    <!-- Header Middle End Here -->
    <!-- Header Bottom Start Here -->
    <div class="header-bottom  header-sticky">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-9 col-lg-8 col-md-12 ">
                    <nav class="d-none d-lg-block">
                        <ul class="header-bottom-list d-flex">
                            <li><a href="<?= URL ?>/index">Inicio</a></li>
                            <li><a href="<?= URL ?>/productos">Productos</a></li>
                            <li><a href="<?= URL ?>/blogs">Blog</a></li>
                            <li><a href="<?= URL ?>/contacto">Contacto</a></li>
                            <li><a href="<?= URL ?>/c/empresa">Sobre nosotros</a></li>
                        </ul>
                    </nav>
                    <div class="mobile-menu d-block d-lg-none">
                        <nav>
                            <ul>
                                <li><a href="index.html">home</a>
                                    <!-- Home Version Dropdown Start -->
                                    <ul>
                                        <li><a href="index.html">Home Version 1</a></li>
                                        <li><a href="index-2.html">Home Version 2</a></li>
                                        <li><a href="index-3.html">Home Version 3</a></li>
                                        <li><a href="index-4.html">Home Version 4</a></li>
                                    </ul>
                                    <!-- Home Version Dropdown End -->
                                </li>
                                <li><a href="shop.html">shop</a>
                                    <!-- Mobile Menu Dropdown Start -->
                                    <ul>
                                        <li><a href="product.html">product details</a></li>
                                        <li><a href="compare.html">compare</a></li>
                                        <li><a href="cart.html">cart</a></li>
                                        <li><a href="checkout.html">checkout</a></li>
                                        <li><a href="wishlist.html">wishlist</a></li>
                                    </ul>
                                    <!-- Mobile Menu Dropdown End -->
                                </li>
                                <li><a href="blog.html">Blog</a>
                                    <!-- Mobile Menu Dropdown Start -->
                                    <ul>
                                        <li><a href="single-blog.html">blog details</a></li>
                                    </ul>
                                    <!-- Mobile Menu Dropdown End -->
                                </li>
                                <li><a href="#">pages</a>
                                    <!-- Mobile Menu Dropdown Start -->
                                    <ul>
                                        <li><a href="register.html">register</a></li>
                                        <li><a href="login.html">sign in</a></li>
                                        <li><a href="forgot-password.html">forgot password</a></li>
                                        <li><a href="404.html">404</a></li>
                                    </ul>
                                    <!-- Mobile Menu Dropdown End -->
                                </li>
                                <li><a href="about.html">about us</a></li>
                                <li><a href="contact.html">contact us</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Row End -->
        </div>
        <!-- Container End -->
    </div>
</header>
<!-- Main Header Area End Here -->