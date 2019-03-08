<?php
$funcionesNav = new Clases\PublicFunction();
//Clases
$enviar = new Clases\Email();
$imagenesNav = new Clases\Imagenes();
$usuario = new Clases\Usuarios();
$categoriasNav = new Clases\Categorias();
$carrito = new Clases\Carrito();
//
if (isset($_POST["login"])):
    $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
    $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');

    $usuario->set("email", $email);
    $usuario->set("password", $password);

    if ($usuario->login() == 0):
        ?>
        <script>
            $(document).ready(function () {
                $('#errorLogin').html('<br/><div class="alert alert-warning" role="alert">Email o contraseña incorrecta.</div>');
                $('#login').modal("show");
            });
        </script>
    <?php
    else:
        $funcionesNav->headerMove(CANONICAL);
    endif;
endif;
?>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myLogin" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="login_title">
                    <h2>Iniciar Sesión</h2>
                    <p>Si aún no estas registrado, haz click <a href="#registrar" onclick="$('.modal').hide();$('body').addClass('modal-open')" data-toggle="modal">aquí</a>.</p>
                </div>
                <div id="errorLogin"></div>
                <form class="login_form row" id="login" method="post">
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="email" placeholder="Correo electrónico" data-validation="email" name="email"
                               required>
                    </div>
                    <div class="col-lg-12 form-group mb-5">
                        <input class="form-control" type="password" placeholder="Contraseña" name="password"
                               required>
                    </div>
                    <div class="col-lg-12 mt-10 mb-10">
                        <p><a href="#recuperar" onclick="$('.modal').hide();$('body').addClass('modal-open')" data-toggle="modal">¿Olvidaste tu contraseña?</a></p>
                    </div>
                    <div class="col-lg-12 form-group">
                        <button type="submit" name="login" class="btn update_btn form-control">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
<!-- REGISTRAR -->
<?php
if (isset($_POST["registrar"])):
    if ($_POST["password"] == $_POST["password2"]):
        $nombre = $funcionesNav->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
        $apellido = $funcionesNav->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
        $email = $funcionesNav->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
        $telefono = $funcionesNav->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : '');
        $password = $funcionesNav->antihack_mysqli(isset($_POST["password"]) ? $_POST["password"] : '');
        $cod = substr(md5(uniqid(rand())), 0, 10);
        $fecha = getdate();
        $fecha = $fecha['year'] . '-' . $fecha['mon'] . '-' . $fecha['mday'];

        $usuario->set("cod", $cod);
        $usuario->set("nombre", $nombre);
        $usuario->set("apellido", $apellido);
        $usuario->set("email", $email);
        $usuario->set("telefono", $telefono);
        $usuario->set("password", $password);
        $usuario->set("fecha", $fecha);

        if ($usuario->add() == 0):
            ?>
            <script>
                $(document).ready(function () {
                    $("#errorRegistro").html('<br/><div class="alert alert-warning" role="alert">El email ya está registrado.</div>');
                    $('#registrar').modal("show");
                });
            </script>
        <?php
        else:
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
            $funcionesNav->headerMove(CANONICAL);
        endif;
    else:
        ?>
        <script>
            $(document).ready(function () {
                $("#errorRegistro").html('<br/><div class="alert alert-warning" role="alert">Las contraseñas no coinciden.</div>');
                $('#registrar').modal("show");
            });
        </script>
    <?php
    endif;
endif;
?>
<div class="modal fade" id="registrar" role="dialog" aria-labelledby="registrar" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="login_title">
                    <h2>Registro</h2>
                </div>
                <p id="errorRegistro"></p>
                <form class="login_form row" id="registro" method="post">
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="text" placeholder="Nombre" name="nombre"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="text" placeholder="Apellido" name="apellido"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="email" placeholder="Email" data-validation="email" name="email"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="number" placeholder="Teléfono" name="telefono"
                               required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <input class="form-control" type="password" placeholder="Contraseña" name="password"
                               required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <input class="form-control" type="password" placeholder="Confirmar Contraseña"
                               name="password2"
                               required>
                    </div>
                    <div class="col-lg-12 form-group">
                        <button type="submit" name="registrar" class="btn subs_btn form-control">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Register modal -->
<!-- Recuperar -->
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
            <script>
                $(document).ready(function () {
                    $("#errorRecuperar").html('<br/><div class="alert alert-success" role="alert">Enviado con éxito.</div>');
                    $('#recuperar').modal("show");
                });
            </script>
            <?php
        }else{
            ?>
            <script>
                $(document).ready(function () {
                    $("#errorRecuperar").html('<br/><div class="alert alert-warning" role="alert">Ocurrió un error, intente de nuevo.</div>');
                    $('#recuperar').modal("show");
                });
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            $(document).ready(function () {
                $("#errorRecuperar").html('<br/><div class="alert alert-warning" role="alert">El email no existe.</div>');
                $('#recuperar').modal("show");
            });
        </script>
        <?php
    }
}
?>

<div class="modal fade" id="recuperar"  role="dialog" aria-labelledby="recuperar" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="login_title">
                    <h2>Recuperar contraseña</h2>
                </div>
                <p id="errorRecuperar"></p>
                <form class="login_form row" id="recuperar" method="post">
                    <div class="col-lg-12 form-group">
                        <input class="form-control" type="email" placeholder="Email" data-validation="email" name="email"
                               required>
                    </div>

                    <div class="col-lg-12 form-group">
                        <button type="submit" name="recuperar" class="btn subs_btn form-control">Recuperar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Recuperar modal -->

