<?php

namespace Clases;

class Admin {

    //Atributos
    public $id;
    public $email;
    public $password;
    public $fecha;
    private $con;

    //Metodos
    public function __construct() {
        $this->con = new Conexion();
    }

    public function set($atributo, $valor) {
        $this->$atributo = $valor;
    }

    public function get($atributo) {
        return $this->$atributo;
    }

    public function add() {
        /*$sql = "INSERT INTO `admin` (`id`, `titulo`, `desarrollo`, `categoria`, `keywords`, `description`, `fecha`) VALUES ('{$this->id}', '{$this->titulo}', '{$this->desarrollo}', '{$this->categoria}', '{$this->keywords}', '{$this->description}', '{$this->fecha}')";
        $query = $this->con->sql($sql);
        return $query;*/
    }

    public function edit() {
      /*  $sql = "UPDATE `admin` SET id = '{$this->id}', titulo = '{$this->titulo}', desarrollo = '{$this->desarrollo}', categoria = '{$this->categoria}', keywords = '{$this->keywords}', description = '{$this->description}', fecha = '{$this->fecha}' WHERE `id`='{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;*/
    }

    public function delete() {
        $sql = "DELETE FROM `admin` WHERE `id`  = '{$this->id}'";
        $query = $this->con->sql($sql);
        return $query;
    }

    public function view() {
        $sql = "SELECT * FROM `admin` WHERE id = '{$this->id}' ORDER BY id DESC";
        $admin = $this->con->sqlReturn($sql);
        $row = mysqli_fetch_assoc($admin);
        return $row;
    }

    public function login() {
        $sql = "SELECT * FROM `admin` WHERE `email` = '{$this->email}' AND `password`= '{$this->password}'";
        $admin = $this->con->sqlReturn($sql);
        $contar = mysqli_num_rows($admin);
        $row = mysqli_fetch_assoc($admin);
        if($contar == 1) {
            $_SESSION["admin"] = $row;            
        } 
        return $contar;
    }

    public function logout() {
        $funciones = new PublicFunction();
        unset($_SESSION["admin"]);
        $funciones->headerMove(URL);
    }


    public function loginForm() {
        $admin = new Admin();
        $funciones = new PublicFunction();
        ?>
        <style>nav {display: none !important}</style>
        <center class="mt-100  align-center text-center">
            <div style="width: 400px;margin: auto">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Ingresar al administrador</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <?php
                            if (isset($_POST["login"])) {
                                $admin->set("email", isset($_POST["email"]) ? $funciones->antihack_mysqli($_POST["email"]) : '');
                                $admin->set("password", isset($_POST["password"]) ? $funciones->antihack_mysqli($_POST["password"]) : '');
                                $adm = $admin->login();
                                if($adm == 1) {
                                    $funciones->headerMove(URL."/index.php");
                                } else {
                                    echo "<div class='alert alert-danger'>No existe este usuario.</div>";
                                }
                            }
                            ?>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password">
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Ingresar" name="login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </center>
        <?php
    }

    public function listTable() {
        $sql = "SELECT * FROM `admin` ORDER BY id DESC";
        $admin = $this->con->sqlReturn($sql);
        while ($row = mysqli_fetch_assoc($admin)) {
            ?>
            <tr>
                <td><?= strtoupper($row["titulo"]) ?></td>
                <td>
                    <a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Modificar" href="<?= URL ?>/index.php?op=modificarPortfolio&id=<?= $row["id"] ?>">
                        <i class="fa fa-cog"></i>
                    </a>
                    <a class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" href="<?= URL ?>/index.php?op=verPortfolio&borrar=<?= $row["id"] ?>">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
    }

}
