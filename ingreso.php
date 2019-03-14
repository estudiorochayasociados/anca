<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
//
$template->set("title", TITULO . " | Ingreso");
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
                    <li class="active"><a href="#">Ingreso</a></li>
                </ul>
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Breadcrumb End -->
    <!-- LogIn Page Start -->
    <div class="log-in ptb-100 ptb-sm-60">
        <div class="container">
            <div class="row">
                <!-- New Customer Start -->
                <div class="col-md-6">
                    <div class="well mb-sm-30">
                        <div class="new-customer">
                            <h3 class="custom-title">Eres nuevo?</h3>
                            <p>Regístrate en unos minutos.</p>
                            <a class="customer-btn" href="<?= URL ?>/registro">Registro</a>
                        </div>
                    </div>
                </div>
                <!-- New Customer End -->
                <!-- Returning Customer Start -->
                <div class="col-md-6">
                    <div class="well">
                        <div class="return-customer">
                            <h3 class="mb-10 custom-title">Ingreso</h3>
                            <?php
                            if (isset($_POST["login"])) {
                                $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                                $password = $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');

                                $usuario->set("email", $email);
                                $usuario->set("password", $password);

                                if ($usuario->login() == 0) {
                                    ?>
                                    <br/><div class="alert alert-danger" role="alert">Email o contraseña incorrecta.</div>
                                    <?php
                                } else {
                                    $funciones->headerMove(URL.'/sesion');
                                }
                            }
                            ?>
                            <form id="login" method="post">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" placeholder="Correo electrónico" name="email"
                                           required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input type="password" placeholder="Contraseña" name="password"
                                           required class="form-control">
                                </div>
                                <p class="lost-password"><a href="<?= URL ?>/recuperar">¿Olvidaste tu contraseña?</a></p>
                                <button type="submit" name="login" class="return-customer-btn">Ingresar</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Returning Customer End -->
            </div>
            <!-- Row End -->
        </div>
        <!-- Container End -->
    </div>
    <!-- LogIn Page End -->
<?php
$template->themeEnd();
?>