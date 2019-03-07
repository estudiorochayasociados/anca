<?php
require_once "../../Config/Autoload.php";
Config\Autoload::runSitio2();
$funcion = new Clases\PublicFunction();
$funcion->localidades();
?>