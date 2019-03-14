<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$template->set("title", TITULO." | Dejanos tus datos");
$template->set("description", "Dejanos tus datos");
$template->set("keywords", "Dejanos tus datos");
$template->set("favicon", FAVICON);
$template->themeInit();
$carrito = new Clases\Carrito();
$usuarios = new Clases\Usuarios();
$usuarioSesion = $usuarios->view_sesion();

$cod_pedido = $_SESSION["cod_pedido"];
$tipo_pedido = $funciones->antihack_mysqli(isset($_GET["metodos-pago"]) ? $_GET["metodos-pago"] : '');
if ($tipo_pedido == '') {
    $funciones->headerMove(URL . "/carrito");
}
if (!empty($usuarioSesion)) {
    $funciones->headerMove(URL . "/checkout/" . $cod_pedido . "/" . $tipo_pedido);
}
$error='';
?>
    <!-- Breadcrumb Start -->
    <div class="breadcrumb-area mt-30">
        <div class="container">
            <div class="breadcrumb">
                <ul class="d-flex align-items-center">
                    <li><a href="<?= URL ?>/index">Inicio</a></li>
                    <li><a href="">Carrito</a></li>
                    <li class="active"><a href="">Pagar</a></li>
                </ul>
            </div>
        </div>
        <!-- Container End -->
    </div>
    <!-- Breadcrumb End -->
    <!--================Categories Banner Area =================-->
    <section class="solid_banner_area mt-10">
        <div class="container">
            <div class="solid_banner_inner navegador">
                <h3>Compra N°: <?= $cod_pedido ?></h3>
                <ul>
                    <li>Llená el siguiente formulario para poder finalizar tu compra <span class="em em---1"></span></li>
                </ul>
            </div>
        </div>
    </section>
    <!--================End Categories Banner Area =================-->

    <div class="container mt-30">
        <?php
        if (isset($_POST["registrarmeBtn"])) {
            $error = 0;
            $cod = substr(md5(uniqid(rand())), 0, 10);
            $nombre = $funciones->antihack_mysqli(isset($_POST["nombre"]) ? $_POST["nombre"] : '');
            $apellido = $funciones->antihack_mysqli(isset($_POST["apellido"]) ? $_POST["apellido"] : '');
            $doc = $funciones->antihack_mysqli(isset($_POST["doc"]) ? $_POST["doc"] : '');
            $email = $funciones->antihack_mysqli(isset($_POST["email"]) ? $_POST["email"] : '');
            $password1 = $funciones->antihack_mysqli(isset($_POST["password1"]) ? $_POST["password1"] : '');
            $password2 = $funciones->antihack_mysqli(isset($_POST["password2"]) ? $_POST["password2"] : '');
            $postal = $funciones->antihack_mysqli(isset($_POST["postal"]) ? $_POST["postal"] : '');
            $direccion = $funciones->antihack_mysqli(isset($_POST["direccion"]) ? $_POST["direccion"] : '');
            $localidad = $funciones->antihack_mysqli(isset($_POST["localidad"]) ? $_POST["localidad"] : '');
            $provincia = $funciones->antihack_mysqli(isset($_POST["provincia"]) ? $_POST["provincia"] : '');
            $pais = $funciones->antihack_mysqli(isset($_POST["pais"]) ? $_POST["pais"] : '');
            $telefono = $funciones->antihack_mysqli(isset($_POST["telefono"]) ? $_POST["telefono"] : '');
            $celular = $funciones->antihack_mysqli(isset($_POST["celular"]) ? $_POST["celular"] : '');
            $invitado = $funciones->antihack_mysqli(isset($_POST["invitado"]) ? $_POST["invitado"] : '1');
            $fecha = $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d"));

            $usuarios->set("nombre", $nombre);
            $usuarios->set("apellido", $apellido);
            $usuarios->set("doc", $doc);
            $usuarios->set("email", $email);
            $usuarios->set("password", $password1);
            $usuarios->set("postal", $postal);
            $usuarios->set("direccion", $direccion);
            $usuarios->set("localidad", $localidad);
            $usuarios->set("provincia", $provincia);
            $usuarios->set("pais", $pais);
            $usuarios->set("telefono", $telefono);
            $usuarios->set("celular", $celular);
            $usuarios->set("invitado", $invitado);
            $usuarios->set("fecha", $fecha);

            $email_data = $usuarios->validate();
            if (!empty($email_data)) {
                $cod = $email_data['cod'];
            } else {
                $cod = substr(md5(uniqid(rand())), 0, 10);
            }
            $usuarios->set("cod", $cod);
            switch ($invitado) {
                //checkbox marcado
                case 0:
                    //si existe el email, edita
                    if (!empty($email_data)) {
                        //pregunta si esta registrado
                        if ($email_data['invitado'] == 0) {
                            $error="Ya existe un usuario registrado con este email.";
                        } else {
                            //si invitado es 1
                            if ($password1 != $password2) {
                                $error="Error las contraseñas no coinciden.";
                            } else {
                                $usuarios->edit();
                                $usuarios->login();
                                $funciones->headerMove(URL . "/checkout/" . $cod_pedido . "/" . $tipo_pedido);
                            }
                        }
                    } else {
                        //si no existe, agrega el usuario
                        if ($password1 != $password2) {
                            $error="Error las contraseñas no coinciden.";
                        } else {
                            $usuarios->add();
                            $usuarios->login();
                            $funciones->headerMove(URL . "/checkout/" . $cod_pedido . "/" . $tipo_pedido);
                        }
                    }
                    break;
                //checkbox desmarcado
                case 1:
                    //si el email exite
                    if (!empty($email_data)){
                        //si el email tiene invitado 1
                        if ($email_data['invitado'] == 1) {
                            $usuarios->edit();
                            $usuarios->login();
                            $funciones->headerMove(URL . "/checkout/" . $cod_pedido . "/" . $tipo_pedido);
                        }else{
                            $error="Ya existe un usuario registrado con este email.";
                        }
                    }else{
                        //el email no existe
                        $usuarios->invitado_sesion();
                        $funciones->headerMove(URL . "/checkout/" . $cod_pedido . "/" . $tipo_pedido);
                    }
                    break;
            }
        }
        ?>
        <div class="col-md-12">
            <div class="<?php if (empty($error)){ echo 'oculto'; } ?>alert alert-warning" role="alert"><?=$error;?></div>
            <form method="post" class="row">
                <div class="row">
                    <input type="hidden" value="<?= $tipo_pedido ?>" name="metodos-pago"/>
                    <div class="col-md-6">Nombre:<br/>
                        <input class="form-control  mb-10" type="text"
                               value="<?php echo isset($_POST["nombre"]) ? $_POST["nombre"] : '' ?>"
                               placeholder="Escribir nombre" name="nombre" required/>
                    </div>
                    <div class="col-md-6">Apellido:<br/>
                        <input class="form-control  mb-10" type="text"
                               value="<?php echo isset($_POST["apellido"]) ? $_POST["apellido"] : '' ?>"
                               placeholder="Escribir apellido" name="apellido" required/>
                    </div>
                    <div class="col-md-12">Email:<br/>
                        <input class="form-control  mb-10" type="email"
                               value="<?php echo isset($_POST["email"]) ? $_POST["email"] : '' ?>"
                               placeholder="Escribir email" name="email" required/>
                    </div>
                    <div class="col-md-12">Teléfono:<br/>
                        <input class="form-control  mb-10" type="text"
                               value="<?php echo isset($_POST["telefono"]) ? $_POST["telefono"] : '' ?>"
                               placeholder="Escribir telefono" name="telefono" required/>
                    </div>

                    <div class="col-md-4">Provincia
                        <div class="input-group">
                            <select class="pull-right form-control h40" name="provincia" id="provincia" required>
                                <option value="" selected disabled>Provincia</option>
                                <?php $funciones->provincias() ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">Localidad
                        <div class="input-group">
                            <select class="form-control h40" name="localidad" id="localidad" required>
                                <option value="" selected disabled>Localidad</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">Dirección:<br/>
                        <input class="form-control  mb-10" type="text"
                               value="<?php echo isset($_POST["direccion"]) ? $_POST["direccion"] : '' ?>"
                               placeholder="Escribir dirección" name="direccion" required/>
                    </div>
                    <label class="col-md-12 col-xs-12 mt-10 mb-10 crear" style="font-size:16px">
                        <input type="checkbox" name="invitado" value="0" onchange="$('.password').slideToggle()">
                        ¿Deseas crear una cuenta de usuario y dejar tus datos grabados para la próxima compra?
                    </label>
                    <div class="col-md-6 col-xs-6 password" style="display: none;">Contraseña:<br/>
                        <input class="form-control  mb-10" type="password"
                               value=""
                               placeholder="Escribir password" name="password1"/>
                    </div>
                    <div class="col-md-6 col-xs-6 password" style="display: none;">Repetir Contraseña:<br/>
                        <input class="form-control  mb-10" type="password"
                               value=""
                               placeholder="Escribir repassword" name="password2"/>
                    </div>

                    <label class="col-md-12 col-xs-12 mt-10 mb-10" style="font-size:16px">
                        <input type="checkbox" name="factura" value="0" onchange="$('.factura').slideToggle()">
                        Solicitar FACTURA A
                    </label>
                    <div class="col-md-12 col-xs-12 factura" style="display: none;">CUIT:<br/>
                        <input class="form-control  mb-10" type="number"
                               value="<?php echo isset($_POST["doc"]) ? $_POST["doc"] : '' ?>"
                               placeholder="Escribir CUIT" name="doc"/>
                    </div>
                    <div class="col-md-12 col-xs-12 mb-50">
                        <input class="btn btn-success" type="submit" value="¡Finalizar la compra!"
                               name="registrarmeBtn"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
$template->themeEnd();
?>