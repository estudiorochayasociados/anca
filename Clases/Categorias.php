<?php

namespace Clases;

class Categorias
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $area; 
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
        $sql   = "INSERT INTO `categorias`(`cod`, `titulo`, `area`) VALUES ('{$this->cod}', '{$this->titulo}', '{$this->area}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql   = "UPDATE `categorias` SET cod = '{$this->cod}', titulo = '{$this->titulo}', area = '{$this->area}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `categorias` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `categorias` WHERE cod = '{$this->cod}' || id = '{$this->id}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
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

        $sql   = "SELECT * FROM `categorias` $filterSql ORDER BY id DESC";
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

        $sql = "SELECT * FROM `categorias` $filterSql  ORDER BY id DESC";
         $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    function listWithOps($filter,$order,$limit) {
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

        $sql = "SELECT * FROM `categorias` $filterSql  ORDER BY $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array ;
        }
    }

    function listForArea($limit) {
        $array = array();
        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = "SELECT * FROM `categorias` WHERE area = '{$this->area}'  ORDER BY id DESC $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }


    function listForSearch($limit) {
        $array = array();
        if ($limit != '') {
            $limitSql = "LIMIT " . $limit;
        } else {
            $limitSql = '';
        }
        $sql = " SELECT `categorias`.`titulo`,`categorias`.`cod`, count(`productos`.`categoria`)  as cantidad  FROM `productos`,`categorias` WHERE `categoria` = `categorias`.`cod` GROUP BY categoria ORDER BY cantidad  DESC  $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array ;
        } 
    }
}
