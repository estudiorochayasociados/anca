<?php

namespace Clases;

class Imagenes
{

    //Atributos
    public $id;
    public $link;
    public $ruta;
    public $cod;
    private $con;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }

    public function get($atributo)
    {
        return $this->$atributo;
    }

    public function add()
    {
        $sql   = "INSERT INTO `imagenes`(`ruta`, `cod`) VALUES ('{$this->ruta}', '{$this->cod}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql   = "UPDATE `imagenes` SET ruta = '{$this->ruta}', cod = '{$this->cod}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql    = "SELECT * FROM `imagenes` WHERE id = '{$this->id}'";
        $imagen = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($imagen)) {
            $sqlDelete = "DELETE FROM `imagenes` WHERE `id` = '{$this->id}'";
            $query     = $this->con->sqlReturn($sqlDelete);
            unlink("../" . $row["ruta"]);
        }
    }

    public function deleteAll()
    {
        $sql    = "SELECT * FROM `imagenes` WHERE cod = '{$this->cod}' ORDER BY cod DESC";
        $imagen = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($imagen)) {
            $sqlDelete = "DELETE FROM `imagenes` WHERE cod = '{$this->cod}'";
            $query     = $this->con->sql($sqlDelete);
            unlink("../" . $row["ruta"]);
        }
    }

    public function view()
    {
        $sql      = "SELECT * FROM `imagenes` WHERE cod = '{$this->cod}' ORDER BY id ASC";
        $imagenes = $this->con->sqlReturn($sql);
        $row      = mysqli_fetch_assoc($imagenes);
        if ($row===NULL) {
            $row['ruta']      =  "assets/archivos/sin_imagen.jpg";
        return $row;
        }else {
        return $row;
        }
    }

    function listForProduct() {
        $array = array();
        $sql   = "SELECT * FROM `imagenes` WHERE cod = '{$this->cod}' ORDER BY id ASC";
        $notas = $this->con->sqlReturn($sql);

       // if ($notas) {
       //     while ($row = mysqli_fetch_assoc($notas)) {
       //         $array[] = $row;
       //     }
       //     return $array;
       // }
        if ($notas===NULL) {
            $row['ruta']      =  "assets/archivos/sin_imagen.jpg";
        return $row;
        }else {
            while ($row = mysqli_fetch_assoc($notas)) {
                 $array[] = $row;
             }
             return $array;
        }
    }
    
    function list($filter) {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql   = "SELECT * FROM `imagenes` $filterSql  ORDER BY id ASC";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    public function imagenesAdmin()
    {
        $sql      = "SELECT * FROM `imagenes` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $imagenes = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($imagenes)) {
            echo "<div class='col-md-2 mb-20 mt-20'>";
            echo "<img src='../" . $row["ruta"] . "' width='100%'  class='mb-20' />";
            echo "<a href='" . URL . "/index.php?op={$this->link}&cod=" . $row["cod"] . "&borrarImg=" . $row["id"] . "' class='btn btn-primary'>BORRAR IMAGEN</a>";
            echo "<div class='clearfix'></div>";
            echo "</div>";
        };
    }

}
