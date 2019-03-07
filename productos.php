<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
//
$template = new Clases\TemplateSite();
$funciones = new Clases\PublicFunction();
$producto = new Clases\Productos();
$categoria = new Clases\Categorias();
//
$template->set("title", TITULO . " | Productos");
$template->set("description", "Plumita S.R.L es una fábrica de Cortadoras y Bordeadoras de cesped, de muy alto nivel con distribución en todo el país.");
$template->set("keywords", "bordeadora, cortadora, cesped, pasto, yuyo, alto nivel, durardera, maquinas, compra");
$template->set("favicon", FAVICON);
$template->themeInit();
//Categorias
$categoria->set("area", "productos");
$categorias_data = $categoria->listForArea('');
sort($categorias_data);
//Productos
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : '0';
$categoria_get = isset($_GET["categoria"]) ? $_GET["categoria"] : '';
$titulo = isset($_GET["titulo"]) ? $_GET["titulo"] : '';
$orden_pagina = isset($_GET["order"]) ? $_GET["order"] : '';
$id = isset($_GET["id"]) ? $_GET["id"] : '';
//
$cantidad = 9;
if ($pagina > 0) {
    $pagina = $pagina - 1;
}

if (@count($filter) == 0) {
    $filter = '';
}
if (@count($_GET) >= 1) {
    $anidador = "&";
} else {
    $anidador = "?";
}

if (isset($_GET['pagina'])):
    $url = $funciones->eliminar_get(CANONICAL, 'pagina');
else:
    $url = CANONICAL;
endif;
//
if (!empty($categoria_get)) {
    $categoria->set("id", $id);
    $categoria_data_filtro = $categoria->view();
    $cod = $categoria_data_filtro['cod'];
    $filter = array("categoria='$cod'");
}
if ($titulo != '') {
    $filter= array();
    $titulo_espacios = strpos($titulo, " ");
    if ($titulo_espacios) {
        $filter_title = array();
        $titulo_explode = explode(" ", $titulo);
        foreach ($titulo_explode as $titulo_) {
            array_push($filter_title, "(titulo LIKE '%$titulo_%'  || desarrollo LIKE '%$titulo_%')");
        }
        $filter_title_implode = implode(" OR ", $filter_title);
        array_push($filter, "(" . $filter_title_implode . ")");
    } else {
        $filter = array("(titulo LIKE '%$titulo%' || desarrollo LIKE '%$titulo%')");
    }
}

switch ($orden_pagina) {
    case "mayor":
        $order_final = "precio DESC";
        break;
    case "menor":
        $order_final = "precio ASC";
        break;
    case "ultimos":
        $order_final = "id DESC";
        break;
    default:
        $order_final = "id DESC";
        break;
}
$productos_data = $producto->listWithOps($filter, $order_final, ($cantidad * $pagina) . ',' . $cantidad);
$numeroPaginas = $producto->paginador($filter, $cantidad);
$productos_data_random = $producto->listWithOps('', 'RAND()', '4');
$sesionCount = @count($_SESSION['usuarios']);
?>

<?php
$template->themeEnd(); ?>