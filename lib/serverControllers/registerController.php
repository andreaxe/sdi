<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "ConnectDB.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class registerController{

    public $args;

    public function  __construct($args)
    {
        $this->args = $args;
    }

    public function register()
    {
        $connection = ConnectDB::getInstance()->getConnection();
        $username = $this->args->args->username;
        $email    = $this->args->args->email;
        $password = sha1($this->args->args->password);
        $activo   = 0;
        $nif = '245923042';
        $federado = 0;
        $password     = sha1($password);

        $query = "INSERT INTO utilizador(nome, email, nif, senha, ativo, federado)VALUES('$username', '$email', $nif, '$password', 
    '$activo', '$federado')";

        $fp = fopen('lidn.txt', 'a');
        fwrite($fp, $query);
        fclose($fp);

        $user  = mysqli_query($connection, $query);

        if($user)
        {
            $response = array('success'=> 1, 'msg' => 'Utilizador criado com sucesso!');
        }
        else
        {
            $response = array('success'=> 0, 'msg' => 'Erro na criação de utilizador! :(!',
                'query' => $query, 'error' => mysqli_error_list($connection));
        }
        mysqli_close($connection);
        return json_encode($response);
    }
}