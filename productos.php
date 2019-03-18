<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
$banner = new Clases\Banner();
///Banners
$categoria->set("area", "banners");
$categorias_banners = $categoria->listForArea('');
foreach ($categorias_banners as $catB) {
    if ($catB['titulo'] == "Cuadrado") {
        $banner->set("categoria", $catB['cod']);
        $banner_data_cuadrado = $banner->listForCategory('RAND()', 1);
    }
}
//Categorias
$categoria->set("area", "productos");
$categorias_data = $categoria->listForArea('');
sort($categorias_data);
//Productos
$pagina = $funciones->antihack_mysqli(isset($_GET["pagina"]) ? $_GET["pagina"] : '0');
$categoria_get = $funciones->antihack_mysqli(isset($_GET["categoria"]) ? $_GET["categoria"] : '');
$titulo = $funciones->antihack_mysqli(isset($_GET["buscar"]) ? $_GET["buscar"] : '');
$orden_pagina = $funciones->antihack_mysqli(isset($_GET["order"]) ? $_GET["order"] : '');
//
$cantidad = 6;

if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}

if ($_GET) {
    if (@count($_GET) > 1) {
        if (isset($_GET["pagina"])) {
            $anidador = "&";
        }
    } else {
        if (isset($_GET["pagina"])) {
            $anidador = "?";
        } else {
            $anidador = "&";
        }
    }
} else {
    $anidador = "?";
}

if (!empty($categoria_get) || !empty($titulo)) {
    $filter = array();
} else {
    $filter = '';
}

if (!empty($categoria_get)) {
    $categoria->set("cod", $categoria_get);
    $categoria_data_filtro = $categoria->view();
    $cod = $categoria_data_filtro['cod'];
    array_push($filter, "categoria='$cod'");
}

if ($titulo != '') {
    $titulo_espacios = strpos($titulo, " ");
    if ($titulo_espacios) {
        $filter_title = array();
        $titulo_explode = explode(" ", $titulo);
        foreach ($titulo_explode as $titulo_) {
            array_push($filter_title, "(titulo LIKE '%$titulo_%'  || desarrollo LIKE '%$titulo_%')");
        }
        $filter_title_implode = implode(" OR ", $filter_title);
        array_push($filter, "(" . $filter_title_implode . ")");
    } else {
        array_push($filter, "(titulo LIKE '%$titulo%' || desarrollo LIKE '%$titulo%')");
    }
}

if (!empty($empresa_get)) {
    array_push($filter, "cod_empresa='$empresa_get'");
}

switch ($orden_pagina) {
    case "mayor":
        $order_final = "precio DESC";
        break;
    case "menor":
        $order_final = "precio ASC";
        break;
    case "ultimos":
        $order_final = "id DESC";
        break;
    default:
        $order_final = "id DESC";
        $orden_pagina = 'ultimos';
        break;
}

$productos_data = $producto->listWithOps($filter, $order_final, ($cantidad * $pagina) . ',' . $cantidad);
if (!empty($productos_data)) {
    $numeroPaginas = $producto->paginador($filter, $cantidad);
}
$template->set("title", TITULO . " | Productos");
$template->set("description", "Todos los productos de Anca");
$template->set("keywords", "Todos los productos de Anca");
$template->set("favicon", FAVICON);
$template->set("body", "home3");
$template->themeInit();

?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-area mt-30">
        <div class="container">
            <div class="breadcrumb">
                <ul class="d-flex align-items-center">
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li class="active"><a href="">Productos</a></li>
                </ul>
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Breadcrumb End -->
    <div class="container pt-15 pb-100">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar Shopping Option Start -->
                <div class="order-1 order-lg-1 hidden-xs">
                    <div class="sidebar">
                        <div class="sidebar-categorie mb-40 ">
                            <form method="get" action="<?= URL . '/productos' ?>">
                                <?php $funciones->variables_get_input("categoria"); ?>
                                <h3 class="sidebar-title">Categorías</h3>
                                <ul class="sidbar-style">
                                    <?php
                                    foreach ($categorias_data as $cat) {
                                        ?>
                                        <li class="form-check">
                                            <input id="<?= $cat['cod']; ?>" class="form-check-input" value="<?= $cat['cod']; ?>" name="categoria" type="checkbox" onclick="$('.form-check-input').removeAttr('checked');this.form.submit();" <?php if ($categoria_get == $cat['cod']) {
                                                echo "checked";
                                            } ?> >
                                            <label class="form-check-label" for="<?= $cat['cod']; ?>"><?= ucfirst($cat['titulo']); ?></label>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </form>
                        </div>
                        <!-- Sidebar Electronics Categorie End -->
                        <?php
                        if (!empty($banner_data_cuadrado)) {
                            foreach ($banner_data_cuadrado as $banC) {
                                ?>
                                <!-- Single Banner Start -->
                                <div class="col-img hidden-xs" style="height:300px;background:url(<?= $banC['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">
                                </div>
                                <!-- Single Banner End -->
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <!-- Sidebar Shopping Option End -->
            </div>
            <div class="col-md-9">
                <div class="grid-list-top border-default universal-padding d-md-flex justify-content-md-between align-items-center mb-30">
                    <div class="grid-list-view  mb-sm-15">
                        <div class="categorie-search-box">
                            <form method="get" id="buscar">
                                <?php $funciones->variables_get_input("buscar"); ?>
                                <input type="text" name="buscar" value="<?= isset($titulo) ? $titulo : ''; ?>" placeholder="Buscar un producto">
                                <button type="submit"><i class="lnr lnr-magnifier"></i></button>
                            </form>
                        </div>
                    </div>
                    <div class="main-toolbar-sorter clearfix visible-xs">
                        <form method="get" action="<?= URL . '/productos' ?>">
                            <div class="toolbar-sorter d-flex align-items-center">
                                <?php $funciones->variables_get_input("categoria"); ?>
                                <label>Categoría:</label>
                                <select name="categoria" onchange="this.form.submit()" class="form-control h40 sorter wide font-select">
                                    <?php
                                    foreach ($categorias_data as $cat) {
                                        ?>
                                        <option value="<?= $cat['cod']; ?>" <?php if ($categoria_get == $cat['cod']) {
                                            echo "selected";
                                        } ?>><?= ucfirst($cat['titulo']); ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <!-- Toolbar Short Area Start -->
                    <div class="main-toolbar-sorter clearfix">
                        <form method="get">
                            <div class="toolbar-sorter d-flex align-items-center">
                                <label>Ordenar:</label>
                                <?php $funciones->variables_get_input("order"); ?>
                                <select name="order" onchange="this.form.submit()" class="form-control h40 sorter wide font-select">
                                    <option value="ultimos" <?php if ($orden_pagina == "ultimos") {
                                        echo "selected";
                                    } ?>>Últimos
                                    </option>
                                    <option value="menor" <?php if ($orden_pagina == "menor") {
                                        echo "selected";
                                    } ?>>Menor a Mayor
                                    </option>
                                    <option value="mayor" <?php if ($orden_pagina == "mayor") {
                                        echo "selected";
                                    } ?>>Mayor a Menor
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <!-- Toolbar Short Area End -->
                </div>
                <div class="row">
                    <?php
                    foreach ($productos_data as $prod) {
                        ?>
                        <!-- Single Product Start -->
                        <div class="col-md-4 col-6">
                            <!-- Single Product Start -->
                            <div class="single-product borde">
                                <!-- Product Image Start -->
                                <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                    <div class="pro-img" style="height:200px;background:url(<?= $prod['imagenes']['0']['ruta']; ?>) no-repeat center center/contain;">
                                    </div>
                                </a>
                                <!-- Product Image End -->
                                <!-- Product Content Start -->
                                <div class="pro-content">
                                    <div class="pro-info centro">
                                        <h4>
                                            <a href="<?= URL . '/producto/' . $funciones->normalizar_link($prod['data']["titulo"]) . '/' . $prod['data']['cod'] ?>">
                                                <?= ucfirst($prod['data']['titulo']); ?>
                                            </a>
                                        </h4>
                                        <p>
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
                        </div>
                        <!-- Single Product End -->
                        <?php
                    }
                    ?>
                </div>
                <div class="container mb-10">
                    <div class="row">
                        <div class="pro-pagination">
                            <ul class="blog-pagination">
                                <?php
                                if (!empty($numeroPaginas)) {
                                    if ($numeroPaginas != 1 && $numeroPaginas != 0) {
                                        $url_final = $funciones->eliminar_get(CANONICAL, "pagina");
                                        $links = '';
                                        $links .= "<li><a class='page-numbers' href='" . $url_final . $anidador . "pagina=1'>1</a></li>";
                                        $i = max(2, $pagina - 5);

                                        if ($i > 2) {
                                            $links .= "<li><a class='page-numbers' href='#'>...</a></li>";
                                        }
                                        for (; $i <= min($pagina + 6, $numeroPaginas); $i++) {
                                            if ($pagina + 1 == $i) {
                                                $current = "active";
                                            } else {
                                                $current = "";
                                            }
                                            $links .= "<li class='$current'><a class='page-numbers' href='" . $url_final . $anidador . "pagina=" . $i . "'>" . $i . "</a></li>";
                                        }
                                        if ($i - 1 != $numeroPaginas) {
                                            $links .= "<li><a class='page-numbers' href='#'>...</a></li>";
                                            $links .= "<li><a class='page-numbers' href='" . $url_final . $anidador . "pagina=" . $numeroPaginas . "'>" . $numeroPaginas . "</a></li>";
                                        }
                                        echo $links;
                                        echo "";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$template->themeEnd(); ?>