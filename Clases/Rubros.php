<?php

namespace Clases;

class Rubros
{

    //Atributos
    public $id;
    public $cod;
    public $linea;
    public $rubro;
    public $descripcion;
    public $subcategoria;
    public $categoria;
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
        $sql   = "INSERT INTO `rubros`(`cod`, `linea`, `rubro`, `descripcion`, `subcategoria`, `categoria`) VALUES ('{$this->cod}', '{$this->linea}', '{$this->rubro}', '{$this->descripcion}', '{$this->subcategoria}', '{$this->categoria}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql   = "UPDATE `rubros` SET cod = '{$this->cod}', linea = '{$this->linea}', rubro = '{$this->rubro}', descripcion = '{$this->descripcion}', subcategoria = '{$this->subcategoria}', categoria = '{$this->categoria}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `rubros` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `rubros` WHERE id = '{$this->id}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
        return $row;
    }

    function list($filter,$order,$limit) {
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

        $sql = "SELECT * FROM `rubros` $filterSql  ORDER BY $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array ;
        }
    }

}
