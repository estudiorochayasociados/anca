<?php

namespace Clases;

class Banner
{

    //Atributos
    public $id;
    public $cod;
    public $nombre;
    public $categoria;
    public $link;
    public $vistas;
    private $con;
    private $imagenes;
    private $categorias;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
        $this->categorias = new Categorias();
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
        $sql = "INSERT INTO `banners`(`cod`, `nombre`, `categoria`, `vistas`,`link`) VALUES ('{$this->cod}', '{$this->nombre}', '{$this->categoria}', '{$this->vistas}', '{$this->link}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql = "UPDATE `banners` SET cod = '{$this->cod}', nombre = '{$this->nombre}', categoria = '{$this->categoria}', vistas = '{$this->vistas}', link = '{$this->link}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function delete()
    {
        $sql = "DELETE FROM `banners` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `banners` WHERE cod = '{$this->cod}' ORDER BY cod DESC";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        return $row;
    }

    public function view_row($filter)
    {
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `banners` $filterSql ORDER BY cod DESC";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
        return $row;
    }

    public function list()
    {
        $array = array();
        $sql = "SELECT * FROM `banners`";
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
        $sql = "SELECT * FROM `banners` $filterSql  ORDER BY $orderSql $limitSql";
        $productos = $this->con->sqlReturn($sql);
        if ($productos) {
            while ($row = mysqli_fetch_assoc($productos)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));
                $cat = $this->categorias->view_row(array("cod = '" . $row['categoria'] . "'"));
                $array[] = array("data" => $row, "categorias" => $cat, "imagenes" => $img);
            }
            return $array;
        }
    }

    public function increaseViews()
    {
        $sql = "UPDATE `banners` SET vistas = '{$this->vistas}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    function listForCategory($order, $limit)
    {
        $array = array();
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
        $sql = "SELECT * FROM `banners` WHERE categoria = '{$this->categoria}'  ORDER BY $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));

                $array[] = array("data" => $row, "imagenes" => $img);
            }
            return $array;
        }
    }
}
