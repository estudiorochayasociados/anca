<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$enviar = new Clases\Email();
//
$template->set("title", TITULO . " | Inicio");
$template->set("description", "");
$template->set("keywords", "");
$template->set("favicon", FAVICON);
$template->themeInit();
?>
<!-- Breadcrumb Start -->
<div class="breadcrumb-area mt-30">
    <div class="container">
        <div class="breadcrumb">
            <ul class="d-flex align-items-center">
                <li><a href="<?=URL?>/index">Inicio</a></li>
                <li class="active"><a>Contacto</a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- Contact Email Area Start -->
<div class="contact-area ptb-100 ptb-sm-60">
    <div class="container">
        <h3 class="mb-20">Contacto</h3>
        <p class="text-capitalize mb-20">Complete el siguiente formulario para poder contactarse con nosotros.</p>
        <?php
        if (isset($_POST["enviar"])) {
            $nombre = $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
            $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
            $consulta = $funciones->antihack_mysqli(isset($_POST["mensaje"]) ? $_POST["mensaje"] : '');

            $mensajeFinal = "<b>Nombre</b>: " . $nombre . " <br/>";
            $mensajeFinal .= "<b>Email</b>: " . $email . "<br/>";
            $mensajeFinal .= "<b>Consulta</b>: " . $consulta . "<br/>";

            //USUARIO
            $enviar->set("asunto", "Realizaste tu consulta");
            $enviar->set("receptor", $email);
            $enviar->set("emisor", EMAIL);
            $enviar->set("mensaje", $mensajeFinal);
            if ($enviar->emailEnviar() == 1) {
                echo '<div class="alert alert-success" role="alert">¡Consulta enviada correctamente!</div>';
            }

            $mensajeFinalAdmin = "<b>Nombre</b>: " . $nombre . " <br/>";
            $mensajeFinalAdmin .= "<b>Email</b>: " . $email . "<br/>";
            $mensajeFinalAdmin .= "<b>Consulta</b>: " . $consulta . "<br/>";
            //ADMIN
            $enviar->set("asunto", "Consulta Web");
            $enviar->set("receptor", EMAIL);
            $enviar->set("mensaje", $mensajeFinalAdmin);
            if ($enviar->emailEnviar() == 0) {
                echo '<div class="alert alert-danger" role="alert">¡No se ha podido enviar la consulta!</div>';
            }
        }
        ?>
        <form id="contact-form" class="contact-form" method="post">
            <div class="address-wrapper row">
                <div class="col-md-12">
                    <div class="address-fname">
                        <input class="form-control" type="text" name="nombre" placeholder="Nombre">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="address-email">
                        <input class="form-control" type="email" name="email" placeholder="Email">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="address-textarea">
                        <textarea name="mensaje" class="form-control" placeholder="Su mensaje...."></textarea>
                    </div>
                </div>
            </div>
            <div class="footer-content mail-content clearfix">
                <div class="send-email float-md-right">
                    <input value="Enviar" class="return-customer-btn" name="enviar" type="submit">
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Contact Email Area End -->
<?php
$template->themeEnd();
?>