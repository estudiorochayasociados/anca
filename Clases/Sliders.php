<?php

namespace Clases;

class Sliders
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $subtitulo;
    public $categoria;
    public $link;
    public $fecha;
    private $con;
    private $imagenes;
    private $categorias;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes=new Imagenes();
        $this->categorias=new Categorias();
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
        $sql   = "INSERT INTO `sliders`(`cod`, `titulo`, `subtitulo`,  `categoria`,  `link`, `fecha`) VALUES ('{$this->cod}', '{$this->titulo}', '{$this->subtitulo}', '{$this->categoria}' , '{$this->link}','{$this->fecha}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql   = "UPDATE `sliders` SET cod = '{$this->cod}', titulo = '{$this->titulo}', subtitulo = '{$this->subtitulo}', categoria = '{$this->categoria}', link = '{$this->link}', fecha = '{$this->fecha}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `sliders` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `sliders` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
        return $row;
    }

    function list($filter) {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql   = "SELECT * FROM `sliders` $filterSql  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }
    function listWithOps($filter, $order, $limit)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        if ($order != '') {
            $orderSql = $order;
        } else {
            $orderSql = "id DESC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = "SELECT * FROM `sliders` $filterSql  ORDER BY $orderSql $limitSql";
        $sliders = $this->con->sqlReturn($sql);
        if ($sliders) {
            while ($row = mysqli_fetch_assoc($sliders)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));
                $cat = $this->categorias->view_row(array("cod = '" . $row['categoria'] . "'"));
                $array[] = array("data" => $row, "categorias" => $cat, "imagenes" => $img);
            }
            return $array;
        }
    }

    function listForCategory() {
        $array = array();
        $sql = "SELECT * FROM `sliders` WHERE categoria = '{$this->categoria}'  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));
                $cat = $this->categorias->view_row(array("cod = '" . $row['categoria'] . "'"));
                $array[] = array("data" => $row, "categorias" => $cat, "imagenes" => $img);
            }
            return $array;
        }
    }

    //App
    function listWithOpsApp($filter, $order, $limit)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        if ($order != '') {
            $orderSql = $order;
        } else {
            $orderSql = "id DESC";
        }

        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = "SELECT * FROM `sliders` $filterSql  ORDER BY $orderSql $limitSql";
        $sliders = $this->con->sqlReturn($sql);
        if ($sliders) {
            while ($row = mysqli_fetch_assoc($sliders)) {
                //Agregar url a las imagenes
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));
                $img_ = array();
                foreach ($img as $i) {
                    $i['ruta'] = URLSITE . '/' . $i['ruta'];
                    array_push($img_, $i);
                }
                $cat = $this->categorias->view_row(array("cod = '" . $row['categoria'] . "'"));
                $array[] = array("data" => $row, "categorias" => $cat, "imagenes" => $img_);
            }
            return $array;
        }
    }
}
