<?php

namespace Clases;

class Pagos
{

    //Atributos
    private $con;
    public $id;
    public $titulo;
    public $leyenda;
    public $cod;
    public $estado;
    public $aumento;
    public $disminuir;
    public $defecto;

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
        $sql = "INSERT INTO `pagos`(`titulo`, `leyenda`, `cod`, `estado`, `aumento`, `disminuir`, `defecto`) VALUES ('{$this->titulo}','{$this->leyenda}','{$this->cod}','{$this->estado}','{$this->aumento}','{$this->disminuir}','{$this->defecto}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql = "UPDATE `pagos` SET  `titulo` = '{$this->titulo}',`leyenda` = '{$this->leyenda}',`estado` = '{$this->estado}',`aumento` = '{$this->aumento}',`disminuir` = '{$this->disminuir}',`defecto` = '{$this->defecto}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function cambiar_estado()
    {
        $sql = "UPDATE `pagos` SET `estado`='{$this->estado}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql = "DELETE FROM `pagos` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `pagos` WHERE cod = '{$this->cod}' ORDER BY id DESC";
        $pagos = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($pagos);
        return $row;
    }

    function list($filter)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql = "SELECT * FROM `pagos` $filterSql  ORDER BY titulo ASC";
        $pagos = $this->con->sqlReturn($sql);
        if ($pagos) {
            while ($row = mysqli_fetch_assoc($pagos)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
