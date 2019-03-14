<?php
//Clases
$usuario = new Clases\Usuarios();
$usuario->set("cod", $_SESSION["usuarios"]["cod"]);
$usuarioData = $usuario->view();
?>
<div class="col-md-12 mb-10">
    <?php
    if (isset($_POST["guardar"])):

        $nombre = $funciones->antihack_mysqli(!empty($_POST["nombre"]) ? $_POST["nombre"] : '');
        $apellido = $funciones->antihack_mysqli(!empty($_POST["apellido"]) ? $_POST["apellido"] : '');
        $email = $funciones->antihack_mysqli(!empty($_POST["email"]) ? $_POST["email"] : '');
        $password = $funciones->antihack_mysqli(!empty($_POST["password"]) ? $_POST["password"] : '');
        $provincia = $funciones->antihack_mysqli(!empty($_POST["provincia"]) ? $_POST["provincia"] : '');
        $localidad = $funciones->antihack_mysqli(!empty($_POST["localidad"]) ? $_POST["localidad"] : '');
        $direccion = $funciones->antihack_mysqli(!empty($_POST["direccion"]) ? $_POST["direccion"] : '');
        $telefono = $funciones->antihack_mysqli(!empty($_POST["telefono"]) ? $_POST["telefono"] : '');
        $postal = $funciones->antihack_mysqli(!empty($_POST["postal"]) ? $_POST["postal"] : '');

        if (!empty($_POST["password"]) && !empty($_POST["password2"])):
            if ($_POST["password"] == $_POST['password2']):
                $password = $funciones->antihack_mysqli($_POST["password"]);
            else:
                echo '<div class="alert alert-warning" role="alert">Las contraseña no coinciden</div>';
                $password = $usuarioData['password'];
            endif;
        else:
            $password = $usuarioData['password'];
        endif;

        $usuario->set("cod", $usuarioData['cod']);
        $usuario->set("nombre", $nombre);
        $usuario->set("apellido", $apellido);
        $usuario->set("email", $email);
        $usuario->set("provincia", $provincia);
        $usuario->set("localidad", $localidad);
        $usuario->set("direccion", $direccion);
        $usuario->set("telefono", $telefono);
        $usuario->set("postal", $postal);
        $usuario->set("password", $password);
        $usuario->set("fecha", $usuarioData['fecha']);

        $usuario->edit();
        $funciones->headerMove(URL . '/sesion/cuenta');
    endif;
    ?>
    <form class="login_form" id="registro" method="post" autocomplete="off">
        <div class="row">
            <div class="col-md-6">Nombre
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['nombre'] ?>" type="text"
                           placeholder="Nombre" name="nombre"
                           required/>
                    <span class="input-group-addon">
                                            <i class="login_icon glyphicon glyphicon-user"></i>
                                        </span>
                </div>
            </div>
            <div class="col-md-6">Apellido
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['apellido'] ?>" type="text"
                           placeholder="Apellido"
                           name="apellido"
                           required/>
                    <span class="input-group-addon">
                                            <i class="login_icon glyphicon glyphicon-user"></i>
                                        </span>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-6">Email
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['email'] ?>" type="email"
                           placeholder="Email" name="email"
                           required/>
                    <span class="input-group-addon">
                                    <i class="login_icon glyphicon glyphicon-envelope"></i>
                                </span>
                </div>
            </div>
            <div class="col-md-6">Teléfono
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['telefono'] ?>" type="number"
                           placeholder="Teléfono"
                           name="telefono"
                           required/>
                    <span class="input-group-addon">
                                    <i class="login_icon glyphicon glyphicon-phone"></i>
                                </span>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-6">Provincia
                <div class="input-group">
                    <select class="pull-right form-control h40" name="provincia" id="provincia" required>
                        <option value="<?= $usuarioData['provincia'] ?>" selected><?= $usuarioData['provincia'] ?></option>
                        <option value="" disabled>Provincia</option>
                        <?php $funciones->provincias() ?>
                    </select>
                    <span class="input-group-addon"><i
                                class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
            <div class="col-md-6">Localidad
                <div class="input-group">
                    <select class="form-control h40" name="localidad" id="localidad" required>
                        <option value="<?= $usuarioData['localidad'] ?>"
                                selected><?= $usuarioData['localidad'] ?></option>
                        <option value="" disabled>Localidad</option>
                    </select>
                    <span class="input-group-addon"><i
                                class="login_icon glyphicon glyphicon-map-marker"></i></span>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-6">Dirección
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['direccion'] ?>" type="text"
                           placeholder="Dirección" name="direccion"
                           required/>
                    <span class="input-group-addon">
                                    <i class="login_icon glyphicon glyphicon-map-marker"></i>
                                </span>
                </div>
            </div>
            <div class="col-md-6">Código Postal
                <div class="input-group">
                    <input class="form-control h40" value="<?= $usuarioData['postal'] ?>" type="text"
                           placeholder="Código Postal"
                           name="postal"
                           required/>
                    <span class="input-group-addon">
                                    <i class="login_icon glyphicon glyphicon-map-marker"></i>
                                </span>
                </div>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-6">Contraseña
                <div class="input-group">
                    <input type="password" name="password" id="password_fake" class="hidden" autocomplete="off"
                           style="display: none;">
                    <input autocomplete="off" class="form-control h40" value=""
                           type="password" placeholder="Contraseña"
                           name="password"
                           />
                    <span class="input-group-addon">
                                    <i class="login_icon glyphicon glyphicon-lock"></i>
                                </span>
                </div>
            </div>
            <div class="col-md-6">Confirmar Contraseña
                <div class="input-group">
                    <input class="form-control h40" value="" type="password"
                           placeholder="Confirmar Contraseña"
                           name="password2"
                           />
                    <span class="input-group-addon">
                                    <i class="login_icon glyphicon glyphicon-lock"></i>
                                </span>
                </div>
            </div>
        </div>
        <br/>
        <button style="width: 100%;" type="submit" name="guardar" class="button-buy">Guardar</button>
    </form>
</div>