<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "ConnectDB.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class registerController{

    public $args;
    public $connection;

    public function  __construct($args)
    {
        $this->args = $args;
        $database = new ConnectDB();
        $this->connection = $database->db_connection;
    }

    public function register()
    {
        $username = $this->args->args->username;
        $email    = $this->args->args->email;
        $clean_password = htmlspecialchars($this->args->args->pass);
        $password = md5($clean_password); //false is default and returns hex
        $activo   = 0;
        $nif = '245923042';
        $federado = 0;

        $query = "INSERT INTO utilizador(nome, email, nif, senha, ativo, federado)VALUES('$username', '$email', $nif, '$password', 
    '$activo', '$federado')";

        $user  = mysqli_query($this->connection, $query);

        if($user)
        {
            $response = array('success'=> 1, 'msg' => 'Utilizador criado com sucesso!');
        }
        else
        {
            $response = array('success'=> 0, 'msg' => 'Erro na criação de utilizador! :(!',
                'query' => $query, 'error' => mysqli_error_list($this->connection));
        }
        return json_encode($response);
    }
}