<?php

namespace Clases;

class TemplateAdmin
{

    public $title;
    public $keywords;
    public $description;
    public $favicon;
    public $canonical;

    public function themeInit()
    {
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        include("inc/header.inc.php");
        echo '</head>';
        echo '<body>';
        include "inc/nav.inc.php";
        echo '<div class="container-fluid pb-100">';
    }

    public function themeEnd()
    {
        echo '</div>';
        echo '</body>';
        include("inc/footer.inc.php");
        echo '</html >';
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }
}
