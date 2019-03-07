<?php

namespace Clases;

class Subcategorias
{

    //Atributos
    public $id;
    public $cod;
    public $titulo;
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
        $sql   = "INSERT INTO `subcategorias`(`cod`, `titulo`, `categoria`) VALUES ('{$this->cod}', '{$this->titulo}', '{$this->categoria}')";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function edit()
    {
        $sql   = "UPDATE `subcategorias` SET cod = '{$this->cod}', titulo = '{$this->titulo}', categoria = '{$this->categoria}' WHERE `cod`='{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function delete()
    {
        $sql   = "DELETE FROM `subcategorias` WHERE `cod`  = '{$this->cod}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view()
    {
        $sql   = "SELECT * FROM `subcategorias` WHERE cod = '{$this->cod}' ORDER BY id DESC";
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

        $sql = "SELECT * FROM `subcategorias` $filterSql  ORDER BY titulo ASC";
         $notas = $this->con->sqlReturn($sql);

        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array;
        }
    }

    function listForSearch($categoria) {
        $sql = "SELECT subcategorias.cod, subcategorias.titulo, subcategorias.categoria, count(productos.id) as cantidad FROM subcategorias INNER JOIN productos ON subcategorias.cod = productos.subcategoria where productos.categoria = subcategorias.categoria AND productos.subcategoria = subcategorias.cod group by subcategorias.titulo ORDER BY `cantidad` DESC";
        $notas = $this->con->sqlReturn($sql);
        if ($notas) {
            while ($row = mysqli_fetch_assoc($notas)) {
                $array[] = $row;
            }
            return $array ;
        }
    }
}
