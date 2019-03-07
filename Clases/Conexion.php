<?php namespace Clases;

class Conexion
{
    private $datos = array("host"=> "localhost","user"=> "root","pass"=> "","db"  => "anca");

    private $con;

    public function __construct()
    {
        $this->con = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        mysqli_set_charset($this->con,'utf8');

    }

    public function con()
    {
        $conexion = mysqli_connect($this->datos["host"], $this->datos["user"], $this->datos["pass"], $this->datos["db"]);
        mysqli_set_charset($conexion,'utf8');
        return $conexion;
    }

    public function sql($query)
    {
        $this->con->query($query);
    }

    public function sqlReturn($query)
    {
        $dato = $this->con->query($query);
        return $dato;
    }

    public function backup($archivo, $sql, $compress) {
        if ($compress) {
            if (!($zf = gzopen($archivo, 'w9'))) {
                $this->error = 'Can\'t create the output file.';
                return false;
            }
            gzwrite($zf, $sql);
            gzclose($zf);
        } else {
            if (!($f = fopen($archivo, 'w'))) {
                $this->error = 'Can\'t create the output file.';
                return false;
            }
            fwrite($f, $sql);
            fclose($f);
        }
        return true;
    }
}
