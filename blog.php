<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$novedades = new Clases\Novedades();
$imagen = new Clases\Imagenes();
$categoria = new Clases\Categorias();
//Blog
$cod = $funciones->antihack_mysqli(isset($_GET["cod"]) ? $_GET["cod"] : '');
$novedades->set("cod", $cod);
$novedades_data = $novedades->view();
$fecha = explode("-", $novedades_data['fecha']);
$imagen->set("cod", $cod);
$imagenes_data = $imagen->listForProduct();
//
if (!empty($novedades_data['imagenes'][0]['ruta'])) {
    $ruta_ = URL . "/" . $novedades_data['imagenes'][0]['ruta'];
} else {
    $ruta_ = '';
}
$template->set("title", TITULO . " | " . ucfirst(strip_tags($novedades_data['titulo'])));
$template->set("description", ucfirst(substr(strip_tags($novedades_data['desarrollo']), 0, 160)));
$template->set("keywords", ucfirst(strip_tags($novedades_data['titulo'])));
$template->set("imagen", $ruta_);
$template->set("favicon", FAVICON);
$template->themeInit();
?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-area mt-30">
    <div class="container">
        <div class="breadcrumb">
            <ul class="d-flex align-items-center">
                <li><a href="<?=URL?>/index">Inicio</a></li>
                <li><a href="<?=URL?>/blogs">Blog</a></li>
                <li class="active"><a href=""><?= ucfirst($novedades_data['titulo']); ?></a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- Single Blog Page Start Here -->
<div class="single-blog mt-15">
    <div class="container">
        <div class="row">
            <!-- Single Blog Sidebar Description Start -->
            <div class="col-lg-12 order-1 order-lg-2 mb-10">
                <div class="single-sidebar-desc mb-all-40">
                    <div class="sidebar-post-content">
                        <h3 class="sidebar-lg-title"><?= ucfirst($novedades_data['titulo']); ?></h3>
                        <ul class="post-meta d-sm-inline-flex">
                            <li><span> <?= $fecha[2] . '/' . $fecha[1] . '/' . $fecha[0] ?></span></li>
                        </ul>
                    </div>
                    <div id="carouselEx" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $activo = 0;
                            foreach ($imagenes_data as $img) {
                                ?>
                                <div class="carousel-item <?php if ($activo == 0) {
                                    echo 'active';
                                    $activo++;
                                } ?>" style=" height: 600px; background: url(<?= URL . '/' . $img['ruta'] ?>) no-repeat center center/contain;">
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        if (@count($imagenes_data)>1){
                            ?>
                        <a class="carousel-control-prev" href="#carouselEx" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselEx" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="sidebar-desc mb-50 mt-15">
                        <p>
                            <?= ucfirst($novedades_data['desarrollo']); ?>
                        </p>
                    </div>
                    <div class="mt-25" style="float: right;">
                        <div class="">
                            <h5 class="">Compartir :</h5>
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
            <!-- Single Blog Sidebar Description End -->
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Single Blog Page End Here -->
<?php
$template->themeEnd();
?>
