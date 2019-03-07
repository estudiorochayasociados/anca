<?php

namespace Clases;

class Novedades
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
    public $desarrollo;
    public $categoria;
    public $keywords;
    public $description;
    public $fecha;
    private $con;
    private $imagenes;
    private $categorias;

    //Metodos
    public function __construct()
    {
        $this->con = new Conexion();
        $this->imagenes = new Imagenes();
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
        $sql = "INSERT INTO `novedades`(`cod`, `titulo`, `desarrollo`, `categoria`, `keywords`, `description`, `fecha`) VALUES ('{$this->cod}', '{$this->titulo}', '{$this->desarrollo}', '{$this->categoria}', '{$this->keywords}', '{$this->description}', '{$this->fecha}')";
        $query = $this->con->sql($sql);
        return true;
    }

    public function edit()
    {
        $sql = "UPDATE `novedades` SET cod = '{$this->cod}', titulo = '{$this->titulo}', desarrollo = '{$this->desarrollo}', categoria = '{$this->categoria}', keywords = '{$this->keywords}', description = '{$this->description}', fecha = '{$this->fecha}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return true;
    }

    public function delete()
    {
        $sql = "DELETE FROM `novedades` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql = "SELECT * FROM `novedades` WHERE id = '{$this->id}' || cod= '{$this->cod}' ORDER BY id DESC";
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
        $sql = "SELECT * FROM `novedades` $filterSql ORDER BY id DESC";
        $notas = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($notas);
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

        $sql = "SELECT * FROM `novedades` $filterSql  ORDER BY id DESC";
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

        $sql = "SELECT * FROM `novedades` $filterSql  ORDER BY $orderSql $limitSql";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));
                $cat=$this->categorias->view_row(array("cod = '" . $row['categoria'] . "'"));
                $fechas_ = explode("-", $row['fecha']);
                $fecha_=$fechas_[2] . '/' . $fechas_[1] . '/' . $fechas_[0];
                $array[] = array("data" => $row,"fecha_actual"=>$fecha_,"categorias"=>$cat, "imagenes" => $img);
            }
            return $array;
        }
    }

    function paginador($filter, $cantidad)
    {
        $array = array();
        if (is_array($filter)) {
            $filterSql = "WHERE ";
            $filterSql .= implode(" AND ", $filter);
        } else {
            $filterSql = '';
        }
        $sql = "SELECT * FROM `novedades` $filterSql";
        $contar = $this->con->sqlReturn($sql);
        $total = mysqli_num_rows($contar);
        $totalPaginas = $total / $cantidad;
        return ceil($totalPaginas);
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
        $sql = "SELECT * FROM `novedades` $filterSql  ORDER BY $orderSql $limitSql";
        $novedades = $this->con->sqlReturn($sql);
        if ($novedades) {
            while ($row = mysqli_fetch_assoc($novedades)) {
                //Agregar url a las imagenes
                $img = $this->imagenes->list(array("cod = '" . $row['cod'] . "'"));
                $img_ = array();
                foreach ($img as $i) {
                    $i['ruta'] = URLSITE . '/' . $i['ruta'];
                    array_push($img_, $i);
                }
                //Sacar etiquetas
                $row['desarrollo'] = strip_tags($row['desarrollo']);
                //
                $cat = $this->categorias->view_row(array("cod = '" . $row['categoria'] . "'"));
                $array[] = array("data" => $row, "categorias" => $cat, "imagenes" => $img_);
            }
            return $array;
        }
    }
}
