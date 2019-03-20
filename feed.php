<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
//
$template->set("title", TITULO . " | Redes sociales");
$template->set("description", "Redes sociales de ".TITULO);
$template->set("keywords", "Redes sociales de ".TITULO);
$template->set("favicon", FAVICON);
$template->themeInit();
?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-area mt-30 mb-10">
    <div class="container">
        <div class="breadcrumb">
            <ul class="d-flex align-items-center">
                <li><a href="<?=URL?>/index">Inicio</a></li>
                <li class="active"><a href="">Seguinos en nuestras redes</a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<div class="container cuerpoContenedor">
    <!-- The Javascript can be moved to the end of the html page before the </body> tag -->
    <!-- Place <div> tag where you want the feed to appear -->
    <div id="curator-feed"><a href="https://curator.io" target="_blank" class="crt-logo crt-tag">Powered by Curator.io</a></div>
    <!-- The Javascript can be moved to the end of the html page before the </body> tag -->
    <script type="text/javascript">
        /* curator-feed */
        (function(){
            var i, e, d = document, s = "script";i = d.createElement("script");i.async = 1;
            i.src = "https://cdn.curator.io/published/01eabcb5-051a-41a7-84f6-36499673ef64.js";
            e = d.getElementsByTagName(s)[0];e.parentNode.insertBefore(i, e);
        })();
    </script>
    <style>
        #curator-feed .crt-logo{
            position: absolute;
            right: 900000px;
        }
    </style>
</div>
<?php
?>
