<?php namespace Clases;

class Videos
{
    //Atributos
    public $id;
    public $titulo;
    public $link;
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
        $sql   = "INSERT INTO `videos`(`titulo`, `link`) VALUES ('{$this->titulo}','{$this->link}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql   = "UPDATE `videos` SET `titulo`='{$this->titulo}',`link`='{$this->link}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `videos` WHERE `id`  = '{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `videos` WHERE id = '{$this->id}' ORDER BY id DESC";
        $videos = $this->con->sqlReturn($sql);
        $row   = mysqli_fetch_assoc($videos);
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

        $sql   = "SELECT * FROM `videos` $filterSql  ORDER BY id DESC";
        $videos = $this->con->sqlReturn($sql);

        if ($videos) {
            while ($row = mysqli_fetch_assoc($videos)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
