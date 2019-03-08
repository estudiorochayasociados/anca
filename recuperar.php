<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
$enviar = new Clases\Email();
//
$template->set("title", TITULO . " | Recuperación de contraseña");
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
                <li><a href="<?= URL ?>/index">Inicio</a></li>
                <li class="active"><a href="forgot-password.html">Recuperar contraseña</a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- Register Account Start -->
<div class="Lost-pass mt-15">
    <div class="container">
        <div class="register-title">
            <h3 class="mb-10 custom-title">Recuperar contraseña</h3>
        </div>
        <?php
        if (isset($_POST["recuperar"])) {
            $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
            $usuario->set("email", $email);
            $data = $usuario->validate();
            if (!empty($data)) {
                //Envio de mail al usuario
                $mensaje = 'Su contraseña recuperada es ' . $data['password'] . '<br/>';
                $asunto = TITULO . ' - Recuperación de contraseña';
                $receptor = $email;
                $emisor = EMAIL;
                $enviar->set("asunto", $asunto);
                $enviar->set("receptor", $receptor);
                $enviar->set("emisor", $emisor);
                $enviar->set("mensaje", $mensaje);

                if ($enviar->emailEnviar() == 1) {
                    ?>
                    <br/>
                    <div class="alert alert-success" role="alert">Enviado con éxito.</div>
                    <?php
                } else {
                    ?>
                    <br/>
                    <div class="alert alert-warning" role="alert">Ocurrió un error, intente de nuevo.</div>
                    <?php
                }
            } else {
                ?>
                <br/>
                <div class="alert alert-warning" role="alert">El email no existe.</div>
                <?php
            }
        }
        ?>
        <form class="password-forgot clearfix" id="recuperar" method="post">
            <fieldset>
                <legend>Your Personal Details</legend>
                <div class="form-group d-md-flex">
                    <label class="control-label col-md-2" for="email"><span class="require">*</span>Email</label>
                    <div class="col-md-10">
                        <input type="email" class="form-control" placeholder="Email" name="email"
                               required>
                    </div>
                </div>
            </fieldset>
            <div class="buttons newsletter-input">
                <div class="float-left float-sm-left">
                    <a class="customer-btn mr-20" href="<?= URL ?>/ingresar">Volver</a>
                </div>
                <div class="float-right float-sm-right">
                    <button type="submit" name="recuperar" class="return-customer-btn">Recuperar</button>
                </div>
            </div>
        </form>
    </div>
    <!-- Container End -->
</div>
<!-- Register Account End -->
<?php
$template->themeEnd();
?>
