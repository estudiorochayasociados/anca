<?php namespace Clases;

class Contenidos
{
    //Atributos
    public $id;
    public $contenido;
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
        $sql   = "INSERT INTO `contenidos`(`contenido`, `cod`) VALUES ('{$this->contenido}','{$this->cod}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql   = "UPDATE `contenidos` SET `contenido`='{$this->contenido}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
    }

    public function delete()
    {
        $sql   = "DELETE FROM `contenidos` WHERE `id`  = '{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM contenidos WHERE id = '{$this->id}' || cod = '{$this->cod}' ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($notas);
        return $row;
    }

    public function list($filter)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }

        $sql   = "SELECT * FROM `contenidos` $filterSql  ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
