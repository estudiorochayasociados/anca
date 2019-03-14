<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$id = $funciones->antihack_mysqli(isset($_GET["id"]) ? $_GET["id"] : '');
$contenido = new Clases\Contenidos();
$contenido->set("cod", $id);
$contenido_data = $contenido->view();
$template->set("title", TITULO . " | ".ucfirst(strip_tags($contenido_data['cod'])));
$template->set("description", ucfirst(substr(strip_tags($contenido_data['contenido']), 0, 160)));
$template->set("keywords", TITULO . " | Empresa");
$template->set("favicon", FAVICON);
$template->themeInit();
?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-area mt-30">
    <div class="container">
        <div class="breadcrumb">
            <ul class="d-flex align-items-center">
                <li><a href="<?=URL?>/index">Inicio</a></li>
                <li class="active"><a ><?=ucfirst($contenido_data['cod'])?></a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- About Us Start Here -->
<div class="about-us mt-15 mb-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="about-desc">
                    <h3 class="mb-10 about-title"><?=ucfirst($contenido_data['cod']);?></h3>
                    <p class="mb-20"><?=ucfirst($contenido_data['contenido']);?></p>

                </div>
            </div>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- About Us End Here -->
<?php
$template->themeEnd();
?>
