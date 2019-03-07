<?php
require_once "../Config/Autoload.php";
Config\Autoload::runAdmin();

$template = new Clases\TemplateAdmin();
$template->set("title", "Admin");
$template->set("description", "Admin");
$template->set("keywords", "Inicio");
$template->set("favicon", "url");
$template->themeInit();
$admin    = new Clases\Admin();
$funciones= new Clases\PublicFunction();


if (!isset($_SESSION["admin"])) {
	$admin->loginForm();
} else {
	$op = isset($_GET["op"]) ? $_GET["op"] : 'inicio';
	$accion = isset($_GET["accion"]) ? $_GET["accion"] : 'ver';
	
	if($op != '') {
		if($op == "salir") {
			session_destroy();
			$funciones->headerMove(URL."/index.php");
		} else {
			include "inc/".$op."/".$accion.".php";		
		}
	}
}

$template->themeEnd();
