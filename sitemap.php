<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$funciones = new Clases\PublicFunction();
$productos = new Clases\Productos();
$contenidos= new Clases\Contenidos();
$servicios= new Clases\Servicios();
$portfolio= new Clases\Productos();
$landing= new Clases\Landing();
$novedades = new Clases\Novedades();
$otras = array("sesion","sesion/cuenta","sesion/pedidos","index","productos");

$xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';


foreach (($data=$novedades->list("")) as $novedad) {
    $cod = $novedad["cod"];
    $titulo = $funciones->normalizar_link($novedad["titulo"]);
    $xml .= '<url><loc>'.URL.'/blog/'.$titulo.'/'.$cod.'</loc><lastmod>'.$novedad["fecha"].'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$contenidos->list("")) as $contenido) {
    $titulo = $funciones->normalizar_link($contenido["cod"]);
    $xml .=  '<url><loc>'.URL.'/c/'.$titulo.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$contenidos->list("")) as $contenido) {
    $titulo = $funciones->normalizar_link($contenido["cod"]);
    $xml .=  '<url><loc>'.URL.'/c/'.$titulo.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$portfolio->list("")) as $port) {
    $cod = $port["cod"];
    $titulo = $funciones->normalizar_link($port["titulo"]);
    $xml .=  '<url><loc>'.URL.'/producto/'.$titulo.'/'.$cod.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$landing->list("")) as $land) {
    $cod = $land["cod"];
    $titulo = $funciones->normalizar_link($land["titulo"]);
    $xml .=  '<url><loc>'.URL.'/landing/'.$titulo.'/'.$cod.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach ($otras as $otro) {
    $xml .=  '<url><loc>'.URL.'/'.$otro.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>';
}


$xml .='</urlset>';

// Opcion 2
header("Content-Type: text/xml;");
echo $xml;


