<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
//
$template->set("title", TITULO . " | Error");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", FAVICON);
$template->themeInit();

?>
<!-- Error 404 Area Start -->
<div class="error404-area ptb-100 ptb-sm-60">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-wrapper text-center">
                    <div class="error-text">
                        <h1>404</h1>
                        <h2>Página no encontrada</h2>
                    </div>
                    <div class="error-button">
                        <a href="<?=URL?>/index">Volver a la página principal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Error 404 Area End -->
<?php
$template->themeEnd();
?>
