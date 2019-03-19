<?php
$usuario = new Clases\Usuarios();
$carrito = new Clases\Carrito();
$producto_nav = new Clases\Productos();
$banner = new Clases\Banner();
$categoria = new Clases\Categorias();
$countCarrito = count($carrito->return());
$carro = $carrito->return();
if (isset($_GET['logout'])) {
    $usuario->logout();
}
///Banners
$categoria->set("area", "banners");
$categorias_banners = $categoria->listForArea('');
foreach ($categorias_banners as $catB) {
    if ($catB['titulo'] == "Botonera") {
        $banner->set("categoria", $catB['cod']);
        $banner_data_botonera = $banner->listForCategory('RAND()', 1);
    }
}
?>
<?php
if (!empty($banner_data_botonera) && CANONICAL == URL . '/index') {
    foreach ($banner_data_botonera as $banB) {
        ?>
        <!-- Banner Popup Start -->
        <div class="popup_banner" id="banner">
            <span class="popup_off_banner" onclick="$(this).parent().hide();">×</span>
            <a href="<?= $banB['data']['link'] ?>">
                <div class="banner_popup_area">
                    <img src="<?= URL . '/' . $banB['imagenes'][0]['ruta']; ?>" alt="<?= $banB['data']['nombre']; ?>">
                </div>
            </a>
        </div>
        <!-- Banner Popup End -->
        <?php
    }
}
?>
<!-- Main Header Area Start Here -->
<header>
    <!-- Header Top Start Here -->
    <div class="header-top-area">
        <div class="container">
            <!-- Header Top Start -->
            <div class="header-top">
                <ul>
                    <li><span><i class="ion-android-call"></i> <?= TELEFONO ?></li>
                    <li><span><i class="ion-android-drafts"></i> <?= EMAIL ?></li>
                </ul>
                <ul>
                    <?php
                    if (!empty($_SESSION['usuarios'])) {
                        ?>
                        <li>
                            <a href="#">Mi cuenta<i class="lnr lnr-chevron-down"></i></a>
                            <!-- Dropdown Start -->
                            <ul class="ht-dropdown">
                                <li><a href="<?= URL ?>/sesion/cuenta">Perfil</a></li>
                                <li><a href="<?= URL ?>/sesion/pedidos">Pedidos</a></li>
                                <li><a href="<?= URL ?>?logout">Salir</a></li>
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
                                <li><a href="<?= URL ?>/ingreso">Iniciar sesión</a></li>
                                <li><a href="<?= URL ?>/registro">Registrar Usuario</a></li>
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
                        <a href="<?= URL ?>/index"><img src="<?= URL ?>/assets/img/logo/logo.png" alt="logo-image"></a>
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
                <div class="col-lg-4 col-md-12 hidden-xs">
                    <div class="cart-box mt-all-30">
                        <ul class="d-flex justify-content-lg-end justify-content-center align-items-center">
                            <li>
                                <a href="<?= URL ?>/carrito"><i class="lnr lnr-cart"></i>
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
                                                        if ($car['id'] != 'Metodo-Pago' && $car['id'] != 'Envio-Seleccion') {
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
    <div class="header-bottom  header-sticky fondo-nav">
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
                                <li><a href="<?= URL ?>/index">Inicio</a></li>
                                <li><a href="<?= URL ?>/productos">Productos</a></li>
                                <li><a href="<?= URL ?>/blogs">Blog</a></li>
                                <li><a href="<?= URL ?>/contacto">Contacto</a></li>
                                <li><a href="<?= URL ?>/c/empresa">Sobre nosotros</a></li>
                                <li><a href="<?= URL ?>/carrito">Carrito</a></li>
                                <?php
                                if (!empty($_SESSION['usuarios'])) {
                                    ?>
                                    <li><a href="<?= URL ?>/sesion/cuenta">Perfil</a></li>
                                    <li><a href="<?= URL ?>/sesion/pedidos">Pedidos</a></li>
                                    <li><a href="<?= URL ?>?logout">Salir</a></li>
                                    <?php
                                } else {
                                    ?>
                                    <li><a href="<?= URL ?>/ingreso">Iniciar sesión</a></li>
                                    <li><a href="<?= URL ?>/registro">Registrar Usuario</a></li>
                                    <?php
                                }
                                ?>

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