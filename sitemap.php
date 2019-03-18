<?php
require_once "Config/Autoload.php";
Config\Autoload::runSitio();
$funciones = new Clases\PublicFunction();
$productos = new Clases\Productos();
$contenidos= new Clases\Contenidos();
$servicios= new Clases\Servicios();
$portfolio= new Clases\Portfolio();
$landing= new Clases\Landing();
$novedades = new Clases\Novedades();
$otras = array("sesion","sesion/logout","index","productos","productos?linea=accesorios&amp;id=2","productos?linea=accesorios&amp;rubro=articulos-de-ferreteria&amp;id=256","productos?linea=accesorios&amp;rubro=bandejas&amp;id=341","productos?linea=accesorios&amp;rubro=cinta-carton-corrugado-cobertores&amp;id=22","productos?linea=accesorios&amp;rubro=discos&amp;id=252","productos?linea=accesorios&amp;rubro=elementos-de-seguridad&amp;id=38","productos?linea=accesorios&amp;rubro=escaleras-caballetes-y-banquetas&amp;id=2","productos?linea=accesorios&amp;rubro=espatulas&amp;id=300","productos?linea=accesorios&amp;rubro=extensores&amp;id=308","productos?linea=accesorios&amp;rubro=fratachos&amp;id=25","productos?linea=accesorios&amp;rubro=ropa-de-trabajo&amp;id=593","productos?linea=accesorios&amp;rubro=vendas&amp;id=264","productos?linea=adhesivos-y-selladores&amp;id=236","productos?linea=adhesivos-y-selladores&amp;rubro=adhesivos&amp;id=237","productos?linea=adhesivos-y-selladores&amp;rubro=selladores&amp;id=236","productos?linea=belleza-automotor&amp;id=30","productos?linea=belleza-automotor&amp;rubro=belleza-automotor&amp;id=30","productos?linea=construccion-en-seco&amp;id=370","productos?linea=construccion-en-seco&amp;rubro=perfiles-soleras-y-montantes&amp;id=370","productos?linea=construccion-en-seco&amp;rubro=placas-y-accesorios&amp;id=371","productos?linea=diluyentes&amp;id=68","productos?linea=diluyentes&amp;rubro=aguarras&amp;id=172","productos?linea=diluyentes&amp;rubro=cemento&amp;id=738","productos?linea=diluyentes&amp;rubro=diluyentes&amp;id=128","productos?linea=diluyentes&amp;rubro=epoxi&amp;id=922","productos?linea=diluyentes&amp;rubro=piletas&amp;id=231","productos?linea=diluyentes&amp;rubro=solventes&amp;id=68","productos?linea=diluyentes&amp;rubro=thinner&amp;id=776","productos?linea=herramientas-electricas&amp;id=15","productos?linea=herramientas-electricas&amp;rubro=amoladoras-y-taladros&amp;id=248","productos?linea=herramientas-electricas&amp;rubro=aspiradoras-y-sopladoras&amp;id=954","productos?linea=herramientas-electricas&amp;rubro=bombas-y-motobombas&amp;id=977","productos?linea=herramientas-electricas&amp;rubro=compresores&amp;id=556","productos?linea=herramientas-electricas&amp;rubro=desmalezadora&amp;id=856","productos?linea=herramientas-electricas&amp;rubro=hidrolavadora&amp;id=555","productos?linea=herramientas-electricas&amp;rubro=lijadoras&amp;id=724","productos?linea=herramientas-electricas&amp;rubro=maquinas&amp;id=260","productos?linea=herramientas-electricas&amp;rubro=motosierras&amp;id=952","productos?linea=herramientas-electricas&amp;rubro=pistolas-de-pintado-accesorios-y-repuestos&amp;id=15","productos?linea=herramientas-electricas&amp;rubro=soldadoras&amp;id=967","productos?linea=herramientas-manuales&amp;id=13","productos?linea=herramientas-manuales&amp;rubro=articulos-de-ferreteria&amp;id=14","productos?linea=herramientas-manuales&amp;rubro=medicion&amp;id=13","productos?linea=impermeabilizantes&amp;id=121","productos?linea=impermeabilizantes&amp;rubro=frentes&amp;id=401","productos?linea=impermeabilizantes&amp;rubro=frentes-e-interiores&amp;id=174","productos?linea=impermeabilizantes&amp;rubro=frentes-techos-y-terrazas&amp;id=165","productos?linea=impermeabilizantes&amp;rubro=interior&amp;id=193","productos?linea=impermeabilizantes&amp;rubro=ladrillo&amp;id=136","productos?linea=impermeabilizantes&amp;rubro=membranas-asfalticas&amp;id=588","productos?linea=impermeabilizantes&amp;rubro=pintura-asfaltica&amp;id=121","productos?linea=impermeabilizantes&amp;rubro=techos-y-terrazas&amp;id=399","productos?linea=jardin&amp;id=1057","productos?linea=jardin&amp;rubro=jardin&amp;id=1057","productos?linea=lijas-y-abrasivos&amp;id=31","productos?linea=lijas-y-abrasivos&amp;rubro=lijas-y-abrasivos&amp;id=31","productos?linea=pinceles-y-rodillos&amp;id=19","productos?linea=pinceles-y-rodillos&amp;rubro=pinceles&amp;id=19","productos?linea=pinceles-y-rodillos&amp;rubro=rodillos&amp;id=283","productos?linea=pintura&amp;id=55","productos?linea=pintura&amp;rubro=aerosoles&amp;id=181","productos?linea=pintura&amp;rubro=aerosoles---barnices&amp;id=292","productos?linea=pintura&amp;rubro=automotor&amp;id=55","productos?linea=pintura&amp;rubro=barnices&amp;id=62","productos?linea=pintura&amp;rubro=entonadores-y-tintas&amp;id=116","productos?linea=pintura&amp;rubro=esmaltes&amp;id=57","productos?linea=pintura&amp;rubro=esmaltes-al-agua&amp;id=473","productos?linea=pintura&amp;rubro=ignifugas&amp;id=228","productos?linea=pintura&amp;rubro=industria&amp;id=691","productos?linea=pintura&amp;rubro=latex&amp;id=75","productos?linea=pintura&amp;rubro=piletas&amp;id=230","productos?linea=pintura&amp;rubro=pisos&amp;id=148","productos?linea=pintura&amp;rubro=plastificante-para-pisos&amp;id=163","productos?linea=pintura&amp;rubro=protectores-para-maderas&amp;id=127","productos?linea=preparacion-de-superficie&amp;id=12","productos?linea=preparacion-de-superficie&amp;rubro=acidos-desoxidantes-y-sal-de-limon&amp;id=63","productos?linea=preparacion-de-superficie&amp;rubro=antioxido-y-fondo-blanco&amp;id=125","productos?linea=preparacion-de-superficie&amp;rubro=convertidor-de-oxido-&amp;id=126","productos?linea=preparacion-de-superficie&amp;rubro=enduidos&amp;id=436","productos?linea=preparacion-de-superficie&amp;rubro=fijadores&amp;id=175","productos?linea=preparacion-de-superficie&amp;rubro=ladrillo&amp;id=140","productos?linea=preparacion-de-superficie&amp;rubro=masillas-y-pastinas&amp;id=12","productos?linea=preparacion-de-superficie&amp;rubro=removedores&amp;id=78","productos?linea=preparacion-de-superficie&amp;rubro=yesos-y-cementicios&amp;id=79","productos?linea=revestimientos-decorativos&amp;id=26","productos?linea=revestimientos-decorativos&amp;rubro=molduras&amp;id=26","productos?linea=revestimientos-decorativos&amp;rubro=papeles&amp;id=1016","productos?linea=revestimientos-decorativos&amp;rubro=pisos-flotantes&amp;id=1068","productos?linea=revestimientos-decorativos&amp;rubro=revestimientos-y-revoques-decorativos&amp;id=468","productos?linea=soluciones-para-el-hogar-&amp;id=239","productos?linea=soluciones-para-el-hogar-&amp;rubro=articulos-de-ferreteria&amp;id=250","productos?linea=soluciones-para-el-hogar-&amp;rubro=burletes-zocalos-y-topetinas&amp;id=239","productos?linea=soluciones-para-el-hogar-&amp;rubro=decoracion&amp;id=934");

$xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';


foreach (($data=$novedades->list("")) as $novedad) {
    $cod = $novedad["cod"];
    $titulo = $funciones->normalizar_link($novedad["titulo"]);
    $xml .= '<url><loc>'.URL.'/blog/'.$titulo.'/'.$cod.'</loc><lastmod>'.$novedad["fecha"].'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$productos->list_with_options("","titulo desc",'')) as $producto) {
    $cod = $producto["id"];
    $titulo = $funciones->normalizar_link($producto["titulo"]);
    $xml .=  '<url><loc>'.URL.'/producto/'.$titulo.'/'.$cod.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$contenidos->list("")) as $contenido) {
    $titulo = $funciones->normalizar_link($contenido["cod"]);
    $xml .=  '<url><loc>'.URL.'/c/'.$titulo.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$contenidos->list("")) as $contenido) {
    $titulo = $funciones->normalizar_link($contenido["cod"]);
    $xml .=  '<url><loc>'.URL.'/c/'.$titulo.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$servicios->list("")) as $servicio) {
    $cod = $servicio["cod"];
    $titulo = $funciones->normalizar_link($servicio["titulo"]);
    $xml .=  '<url><loc>'.URL.'/servicio/'.$titulo.'/'.$cod.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
}

foreach (($data=$portfolio->list("")) as $port) {
    $cod = $port["cod"];
    $titulo = $funciones->normalizar_link($port["titulo"]);
    $xml .=  '<url><loc>'.URL.'/portfolio/'.$titulo.'/'.$cod.'</loc><lastmod>'.date("Y-m-d").'</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>';
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


