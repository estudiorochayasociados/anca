<?php
$code_mercadolibre = isset($_GET["code"]) ? $_GET["code"] : '';

$productos = new Clases\Productos();
$imagenes  = new Clases\Imagenes();
$zebra     = new Clases\Zebra_Image();

$categorias = new Clases\Categorias();
$data = $categorias->list(array("area = 'productos'"));

$appId = MELI_ID;
$secretKey = MELI_SECRET;
$redirectURI = URL . "/index.php?op=productos&accion=agregar";
$siteId = 'MLA';
$url = 'https://api.mercadolibre.com/oauth/token';
$context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query(
            array(
                'grant_type' => 'client_credentials',
                'client_id' => $appId,
                'client_secret' => $secretKey,
            )
        ),
        'timeout' => 60,
    ),
));

$resp = json_decode(file_get_contents($url, false, $context));
$meli = new Meli($appId, $secretKey, $resp->access_token, $resp->refresh_token);

if (isset($_POST["agregar"])) {
    $count = 0;
    $cod   = substr(md5(uniqid(rand())), 0, 10);

    $productos->set("cod", $funciones->antihack_mysqli(isset($cod) ? $cod : ''));
    $productos->set("titulo", $funciones->antihack_mysqli(isset($_POST["titulo"]) ? $_POST["titulo"] : ''));
    $productos->set("cod_producto", $funciones->antihack_mysqli(isset($_POST["cod_producto"]) ? $_POST["cod_producto"] : ''));
    $productos->set("precio", $funciones->antihack_mysqli(isset($_POST["precio"]) ? $_POST["precio"] : ''));
    $productos->set("precio_descuento", $funciones->antihack_mysqli(isset($_POST["precio_descuento"]) ? $_POST["precio_descuento"] : '0'));
    $productos->set("stock", $funciones->antihack_mysqli(isset($_POST["stock"]) ? $_POST["stock"] : ''));
    $productos->set("desarrollo", $funciones->antihack_mysqli(isset($_POST["desarrollo"]) ? $_POST["desarrollo"] : ''));

    $productos->set("variable4", $funciones->antihack_mysqli(isset($_POST["peso"]) ? $_POST["peso"] : ''));
    $productos->set("variable5", $funciones->antihack_mysqli(isset($_POST["altura"]) ? $_POST["altura"] : ''));
    $productos->set("variable6", $funciones->antihack_mysqli(isset($_POST["ancho"]) ? $_POST["ancho"] : ''));
    $productos->set("variable7", $funciones->antihack_mysqli(isset($_POST["profundidad"]) ? $_POST["profundidad"] : ''));

    $productos->set("categoria", $funciones->antihack_mysqli(isset($_POST["categoria"]) ? $_POST["categoria"] : ''));
    $productos->set("subcategoria", $funciones->antihack_mysqli(isset($_POST["subcategoria"]) ? $_POST["subcategoria"] : ''));
    $productos->set("keywords", $funciones->antihack_mysqli(isset($_POST["keywords"]) ? $_POST["keywords"] : ''));
    $productos->set("description", $funciones->antihack_mysqli(isset($_POST["description"]) ? $_POST["description"] : ''));
    $productos->set("fecha", $funciones->antihack_mysqli(isset($_POST["fecha"]) ? $_POST["fecha"] : date("Y-m-d")));
    $productos->set("meli", $funciones->antihack_mysqli(isset($_POST["meli"]) ? $_POST["meli"] : ''));
    $productos->set("url", $funciones->antihack_mysqli(isset($_POST["url"]) ? $_POST["url"] : ''));
    $img_meli = '';
    foreach ($_FILES['files']['name'] as $f => $name) {
        $imgMELI = array();
        $imgInicio = $_FILES["files"]["tmp_name"][$f];
        $tucadena = $_FILES["files"]["name"][$f];
        $partes = explode(".", $tucadena);
        $dom = (count($partes) - 1);
        $dominio = $partes[$dom];
        $prefijo = substr(md5(uniqid(rand())), 0, 10);
        if ($dominio != '') {
            $destinoFinal = "../assets/archivos/" . $prefijo . "." . $dominio;
            move_uploaded_file($imgInicio, $destinoFinal);
            chmod($destinoFinal, 0777);
            $destinoRecortado = "../assets/archivos/recortadas/a_" . $prefijo . "." . $dominio;

            $zebra->source_path = $destinoFinal;
            $zebra->target_path = $destinoRecortado;
            $zebra->jpeg_quality = 80;
            $zebra->preserve_aspect_ratio = true;
            $zebra->enlarge_smaller_images = true;
            $zebra->preserve_time = true;

            if ($zebra->resize(800, 700, ZEBRA_IMAGE_NOT_BOXED)) {
                unlink($destinoFinal);
            }

            $imagenes->set("cod", $cod);
            $imagenes->set("ruta", str_replace("../", "", $destinoRecortado));
            $img_meli .= '{"source":"' . URLSITE . str_replace("../", "/", $destinoRecortado) . '"},';
            $imagenes->add();

        }
        $count++;
    }

    if ($meli != '') {
        $productos->set("img", substr($img_meli, 0, -1));
        $add_meli = $productos->add_meli();
        $productos->set("meli", $add_meli["id"]);
    }

    $productos->add();
    $funciones->headerMove(URL . "/index.php?op=productos");
}
?>

<div class="col-md-12">
    <h4>
        Productos
    </h4>
    <hr/>
    <form method="post" class="row" enctype="multipart/form-data">
        <label class="col-md-4">
            Título:<br/>
            <input type="text" name="titulo">
        </label>
        <label class="col-md-4">
            Categoría:<br/>
            <select name="categoria">
                <option value="" disabled selected>-- categorías --</option> 
                <?php
                foreach ($data as $categoria) {
                    echo "<option value='".$categoria["cod"]."'>".$categoria["titulo"]."</option>";
                }
                ?>
            </select>
        </label>
        <label class="col-md-4">
            Stock:<br/>
            <input type="number" name="stock">
        </label>
        <div class="clearfix">
        </div>
        <label class="col-md-4">
            Código:<br/>
            <input type="text" name="cod_producto">
        </label>
        <label class="col-md-4">
            Precio:<br/>
            <input type="text" name="precio">
        </label>
        <!--
        <label class="col-md-3">
            Precio mayorista:<br/>
            <input type="text" name="precio_mayorista">
        </label>
        <label class="col-md-3">
            Peso:<br/>
            <input type="text" name="peso">
        </label>-->
        <label class="col-md-4">
            Precio Descuento:<br/>
            <input type="text" name="precio_descuento">
        </label>
        <label class="col-md-12">
            Url:<br/>
            <input type="text" name="url" id="url">
        </label>
        <label class="col-md-12">
            Desarrollo:<br/>
            <textarea name="desarrollo" class="ckeditorTextarea">
            </textarea>
        </label>
        <label class="col-md-12">
            Palabras claves dividas por ,<br/>
            <input type="text" name="keywords">
        </label>
        <label class="col-md-12">
            Descripción breve<br/>
            <textarea name="description">
            </textarea>
        </label>
        <br/>
        <div class="col-md-12">
            <div class="form-group form-check">
                <?php
                if (isset($_GET['code']) || isset($_SESSION['access_token'])) {
                    if (isset($_GET['code']) && !isset($_SESSION['access_token'])) {
                        try {
                            $user = $meli->authorize($_GET["code"], $redirectURI);
                            $_SESSION['access_token'] = $user['body']->access_token;
                            $_SESSION['expires_in'] = time() + $user['body']->expires_in;
                            $_SESSION['refresh_token'] = $user['body']->refresh_token;
                        } catch (Exception $e) {
                            echo "Exception: ", $e->getMessage(), "\n";
                        }
                    } else {
                        if ($_SESSION['expires_in'] < time()) {
                            try {
                                $refresh = $meli->refreshAccessToken();
                                $_SESSION['access_token'] = $refresh['body']->access_token;
                                $_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
                                $_SESSION['refresh_token'] = $refresh['body']->refresh_token;
                            } catch (Exception $e) {
                                echo "Exception: ", $e->getMessage(), "\n";
                            }
                        }
                    }
                    echo '<input type="checkbox" class="form-check-input" id="meli" name="meli"> <label class="form-check-label" for="meli">¿Publicar en MercadoLibre?</label>';
                } else {
                    echo '<div class="ml-0 pl-0 mt-20 mb-20"><a  target="_blank" href="' . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]) . '"><img src="' . URL . '/img/meli.png" width="30" /> ¿Ingresar a Mercadolibre para publicar el producto <i class="fa fa-square green">?</i></a></div>';
                }
                ?>
            </div>
        </div>
        
        <label class="col-md-7">
            Imágenes:<br/>
            <input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" />
        </label>
        <div class="clearfix">
        </div>
        <br/>
        <div class="col-md-12">
            <input type="submit" class="btn btn-primary" name="agregar" value="Crear Productos" />
        </div>
    </form>
</div>
