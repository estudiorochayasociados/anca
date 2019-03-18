<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$novedades = new Clases\Novedades();
$imagen = new Clases\Imagenes();
$categoria = new Clases\Categorias();
//
$template->set("title", TITULO . " | Blogs");
$template->set("description", "Blog de " . TITULO);
$template->set("keywords", "Blog de " . TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();

$pagina = $funciones->antihack_mysqli(isset($_GET["pagina"]) ? $_GET["pagina"] : '0');

$cantidad = 6;

if ($pagina > 0) {
    $pagina = $pagina - 1;
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

if (isset($_GET['pagina'])):
    $url = $funciones->eliminar_get(CANONICAL, 'pagina');
else:
    $url = CANONICAL;
endif;

$novedades_data = $novedades->listWithOps("", "", $cantidad * $pagina . ',' . $cantidad);
$numeroPaginas = $novedades->paginador("", $cantidad);
?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-area mt-30">
    <div class="container">
        <div class="breadcrumb">
            <ul class="d-flex align-items-center">
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="active"><a href="">Blogs</a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- Blog Page Start Here -->
<div class="blog mt-15">
    <div class="container">
        <div class="main-blog">
            <div class="row">
                <?php
                if (!empty($novedades_data)) {
                    foreach ($novedades_data as $nov) {
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
                        <div class="col-lg-6 col-sm-12">
                            <div class="single-latest-blog">
                                <div class="blog-img">
                                    <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                                        <div style="height:200px;background:url(<?= $nov['imagenes']['0']['ruta']; ?>) no-repeat center center/cover;">

                                        </div>
                                    </a>
                                </div>
                                <div class="blog-desc">
                                    <h4>
                                        <a href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                                            <?= ucfirst($nov['data']['titulo']); ?>
                                        </a>
                                    </h4>
                                    <p><?= ucfirst(substr(strip_tags($nov['data']['desarrollo']), 0, 150)); ?>...</p>
                                    <a class="readmore" href="<?= URL . '/blog/' . $funciones->normalizar_link($nov['data']["titulo"]) . '/' . $nov['data']['cod'] ?>">
                                        Ver
                                    </a>
                                </div>
                                <div class="blog-date">
                                    <span><?= $fecha_[2] ?></span>
                                    <?= $mes ?>
                                </div>
                            </div>
                        </div>
                        <!-- Single Blog End -->
                        <?php
                    }
                }
                ?>
            </div>
            <!-- Row End -->
            <div class="row">
                <div class="col-sm-12">
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
                    <!-- Product Pagination Info -->
                </div>
            </div>
            <!-- Row End -->
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Blog Page End Here -->
<?php
$template->themeEnd();
?>
