<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$banner = new Clases\Banner();
$categoria = new Clases\Categorias();
$producto = new Clases\Productos();
$novedad = new Clases\Novedades();
$slider = new Clases\Sliders();
//
////Datos
///Productos
$productos_datalast = $producto->listWithOps('', '', 10);
$productos_datarand = $producto->listWithOps('', 'RAND()', 10);
///Blogs
$novedades_datalast = $novedad->listWithOps('', '', 4);
///Banners
$categoria->set("area", "banners");
$categorias_banners = $categoria->listForArea('');
foreach ($categorias_banners as $catB) {
    if ($catB['titulo'] == "Rectangular 1/2") {
        $banner->set("categoria", $catB['cod']);
        $banner_data_rectangular = $banner->listForCategory('RAND()', 2);
    }
    if ($catB['titulo'] == "Largo") {
        $banner->set("categoria", $catB['cod']);
        $banner_data_largo = $banner->listForCategory('RAND()', 1);
    }
}
///Sliders
$categoria->set("area", "sliders");
$categorias_sliders = $categoria->listForArea('');
foreach ($categorias_sliders as $catS) {
    if ($catS['titulo'] == "Principal") {
        $slider->set("categoria", $catS['cod']);
        $sliders_data = $slider->listForCategory();
    }
}
///
//
$template->set("title", TITULO . " | Inicio");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", FAVICON);
$template->themeInit();

if (!empty($sliders_data)) {
    ?>
    <!-- Categorie Menu & Slider Area Start Here -->
    <div class="main-page-banner pb-50 off-white-bg home-3">
        <div id="carE" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $activo=0;
                foreach ($sliders_data as $sli) {
                    ?>
                    <div class="carousel-item <?php if ($activo == 0) { echo 'active'; $activo++; } ?>">
                        <img class="d-block w-100" src="<?= URL . '/' . $sli['imagenes']['0']['ruta']; ?>" alt="<?= $sli['data']['titulo']; ?>">
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
            if (@count($sliders_data > 1)) {
                ?>
                <a class="carousel-control-prev" href="#carE" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carE" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
    <!-- Categorie Menu & Slider Area End Here -->
    <?php
}

if (!empty($banner_data_largo)) {
    foreach ($banner_data_largo as $banL) {
        ?>
        <!-- Brand Banner Area Start Here -->
        <div class="image-banner pb-30 off-white-bg">
            <div class="container">
                <div class="col-img" style="height:60px;width:100%;background:url(<?= $banL['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">
                </div>
            </div>
            <!-- Container End -->
        </div>
        <!-- Brand Banner Area End Here -->
        <?php
    }
    ?>
    <?php
}

if (!empty($productos_datalast)) {
    ?>
    <!-- Trending Products End Here -->
    <div class="trendig-product pb-10 off-white-bg">
        <div class="container">
            <h2 class="section-ttitle2">Últimos productos</h2>
            <div class="trending-box">
                <div class="product-list-box">
                    <!-- Arrivals Product Activation Start Here -->
                    <div class="trending-pro-active owl-carousel">
                        <?php
                        foreach ($productos_datalast as $prod) {
                            ?>
                            <!-- Single Product Start -->
                            <div class="single-product">
                                <!-- Product Image Start -->
                                <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                    <div class="pro-img" style="height:300px;background:url(<?= $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/70%;">
                                    </div>
                                </a>
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
                    <!-- Arrivals Product Activation End Here -->
                </div>
                <!-- main-product-tab-area-->
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Trending Products End Here -->
    <?php
}

if (!empty($productos_datarand)) {
    ?>
    <!-- Trending Products End Here -->
    <div class="trendig-product pb-20 off-white-bg pb-sm-60">
        <div class="container">
            <h2 class="section-ttitle2">Productos recomendados </h2>
            <div class="trending-box">
                <div class="product-list-box">
                    <!-- Arrivals Product Activation Start Here -->
                    <div class="trending-pro-active owl-carousel">
                        <?php
                        foreach ($productos_datarand as $prod) {
                            ?>
                            <!-- Single Product Start -->
                            <div class="single-product">
                                <!-- Product Image Start -->
                                <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                    <div class="pro-img" style="height:300px;background:url(<?= $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/70%;">
                                    </div>
                                </a>
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
                    <!-- Arrivals Product Activation End Here -->
                </div>
                <!-- main-product-tab-area-->
            </div>
        </div>
        <!-- Container End -->
    </div>
    <?php
}

if (!empty($novedades_datalast)) {
    ?>
    <div class="blog-area ptb-95 off-white-bg ptb-sm-55">
        <div class="container">
            <div class="like-product-area">
                <h2 class="section-ttitle2 mb-30">Últimos blogs</h2>
                <!-- Latest Blog Active Start Here -->
                <div class="latest-blog-active owl-carousel">
                    <?php
                    foreach ($novedades_datalast as $nov) {
                        $fecha_ = explode('-', $nov['data']['fecha']);
                        switch ($fecha_[1]) {
                            case 1:
                                $mes = 'ENE';
                                break;
                            case 2:
                                $mes = 'FEB';
                                break;
                            case 3:
                                $mes = 'MAR';
                                break;
                            case 4:
                                $mes = 'ABR';
                                break;
                            case 5:
                                $mes = 'MAY';
                                break;
                            case 6:
                                $mes = 'HUN';
                                break;
                            case 7:
                                $mes = 'JUL';
                                break;
                            case 8:
                                $mes = 'AGO';
                                break;
                            case 9:
                                $mes = 'SEP';
                                break;
                            case 10:
                                $mes = 'OCT';
                                break;
                            case 11:
                                $mes = 'NOV';
                                break;
                            case 12:
                                $mes = 'DIC';
                                break;
                        }
                        ?>
                        <!-- Single Blog Start -->
                        <div class="single-latest-blog">
                            <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">

                            </a>
                            <div class="blog-img">
                                <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>"><img height="200" width="200" src="<?= $nov['imagenes']['0']['ruta']; ?>" alt="<?=$nov['data']["titulo"]?>;"></a>
                            </div>
                            <div class="blog-desc">
                                <h4>
                                    <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                                        <?= ucfirst(substr(strip_tags($nov['data']['titulo']), 0, 60)); ?>...
                                    </a>
                                </h4>
                                <p><?= ucfirst(substr(strip_tags($nov['data']['desarrollo']), 0, 150)); ?>...</p>
                                <a class="readmore" href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                                    Ver más
                                </a>
                            </div>
                            <div class="blog-date">
                                <span><?= $fecha_[2] ?></span>
                                <?= $mes ?>
                            </div>
                        </div>
                        <!-- Single Blog End -->
                        <?php
                    }
                    ?>

                </div>
                <!-- Latest Blog Active End Here -->
            </div>
            <!-- main-product-tab-area-->
        </div>
        <!-- Container End -->
    </div>
    <!-- Latest Blog s Area End Here -->
    <?php
}

if (!empty($banner_data_rectangular)) {
    if (@count($banner_data_rectangular) == 2) {
        ?>
        <!-- Brand Banner Area End Here -->
        <div class="big-banner pb-20 pt-20 pb-sm-60">
            <div class="container big-banner-box">
                <?php
                foreach ($banner_data_rectangular as $banM) {
                    ?>
                    <div class="col-img" style="height:220px;width:50%;background:url(<?= $banM['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">
                    </div>
                    <?php
                }
                ?>
            </div>
            <!-- Container End -->
        </div>
        <?php
    }
}
$template->themeEnd();
?>
