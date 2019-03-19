<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
////Clases
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$usuario = new Clases\Usuarios();
$enviar = new Clases\Email();
//
$template->set("title", TITULO . " | Registro");
$template->set("description", "Registro de usuario en".TITULO);
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
                <li class="active"><a href="#">Registro</a></li>
            </ul>
        </div>
    </div>
    <!-- Container End -->
</div>
<!-- Breadcrumb End -->
<!-- Register Account Start -->
<div class="register-account  mt-15 mb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="register-title">
                    <h3 class="mb-10">Registro de cuenta</h3>
                </div>
            </div>
        </div>
        <!-- Row End -->
        <div class="row">
            <div class="col-sm-12">
                <?php
                if (isset($_POST["registrar"])) {
                    if ($_POST["password"] == $_POST["password2"]) {
                        $nombre = $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
                        $apellido = $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
                        $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
                        $telefono = $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : '');
                        $password = $funciones->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');
                        $direccion = $funciones->antihack_mysqli(isset($_POST["direccion_reg"]) ? $_POST["direccion_reg"] : '');
                        $localidad = $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : '');
                        $provincia = $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : '');
                        $cod = substr(md5(uniqid(rand())), 0, 10);
                        $fecha = getdate();
                        $fecha = $fecha['year'] . '-' . $fecha['mon'] . '-' . $fecha['mday'];

                        $usuario->set("cod", $cod);
                        $usuario->set("nombre", $nombre);
                        $usuario->set("apellido", $apellido);
                        $usuario->set("email", $email);
                        $usuario->set("direccion", $direccion);
                        $usuario->set("telefono", $telefono);
                        $usuario->set("localidad", $localidad);
                        $usuario->set("provincia", $provincia);
                        $usuario->set("password", $password);
                        $usuario->set("fecha", $fecha);

                        if ($usuario->add() == 0) {
                            ?>
                            <br/>
                            <div class="alert alert-danger" role="alert">El email ya está registrado.</div>
                            <?php
                        } else {
                            $usuario->set("password", $password);
                            $usuario->login();

                            //Envio de mail al usuario
                            $mensaje = 'Gracias por registrarse ' . ucfirst($nombre) . '<br/>';
                            $asunto = TITULO . ' - Registro';
                            $receptor = $email;
                            $emisor = EMAIL;
                            $enviar->set("asunto", $asunto);
                            $enviar->set("receptor", $receptor);
                            $enviar->set("emisor", $emisor);
                            $enviar->set("mensaje", $mensaje);
                            $enviar->emailEnviar();
                            //Envio de mail a la empresa
                            $mensaje2 = 'El usuario ' . ucfirst($nombre) . ' ' . ucfirst($apellido) . ' acaba de registrarse en nuestra plataforma' . '<br/>';
                            $asunto2 = TITULO . ' - Registro';
                            $receptor2 = EMAIL;
                            $emisor2 = EMAIL;
                            $enviar->set("asunto", $asunto2);
                            $enviar->set("receptor", $receptor2);
                            $enviar->set("emisor", $emisor2);
                            $enviar->set("mensaje", $mensaje2);
                            $enviar->emailEnviar();

                            $funciones->headerMove(URL.'/sesion');
                        }
                    } else {
                        ?>
                        <br/>
                        <div class="alert alert-danger" role="alert">Las contraseñas no coinciden.</div>
                        <?php
                    }
                }
                ?>
                <form class="form-register" id="registro" method="post">
                    <fieldset>
                        <legend>Tus datos</legend>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2" for="f-name"><span class="require">*</span>Nombre</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Nombre" name="nombre"
                                       required>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2" for="l-name"><span class="require">*</span>Apellido</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Apellido" name="apellido"
                                       required>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2" for="email"><span class="require">*</span>Email</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control" placeholder="Email" name="email"
                                       required>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2" for="number"><span class="require">*</span>Teléfono</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control" placeholder="Teléfono" name="telefono"
                                       required>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2"><span class="require">*</span>Dirección</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" placeholder="Dirección" name="direccion_reg"
                                       required>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2"><span class="require">*</span>Provincia</label>
                            <div class="col-md-10">
                                <select class="pull-right form-control h40" name="provincia" id="provincia" required>
                                    <option value="" disabled>Provincia</option>
                                    <?php $funciones->provincias() ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2"><span class="require">*</span>Localidad</label>
                            <div class="col-md-10">
                                <select class="form-control h40" name="localidad" id="localidad" required>
                                    <option value="" disabled>Localidad</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Tu contraseña</legend>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2" for="pwd"><span class="require">*</span>Password:</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" placeholder="Contraseña" name="password"
                                       required>
                            </div>
                        </div>
                        <div class="form-group d-md-flex align-items-md-center">
                            <label class="control-label col-md-2" for="pwd-confirm"><span class="require">*</span>Confirm Password</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control" placeholder="Confirmar Contraseña"
                                       name="password2"
                                       required>
                            </div>
                        </div>
                    </fieldset>
                    <div class="terms">
                        <div class="float-md-right">
                            <!--
                            <span>I have read and agree to the <a href="#" class="agree"><b>Privacy Policy</b></a></span>
                            <input type="checkbox" name="agree" value="1"> &nbsp;-->
                            <button type="submit" name="registrar" class="return-customer-btn">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Row End -->
    </div>
    <!-- Container End -->
</div>
<!-- Register Account End -->
<?php
$template->themeEnd();
?>
