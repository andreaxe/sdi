<?php
include_once('../ConnectDB.php');

// importante ver os erros do php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*$data['username'] = $_POST['username'];
$data['email'] = $_POST['email'];
$data['password'] = $_POST['password'];
$data['password_confirmation'] = $_POST['password_confirmation'];*/

if($_POST['password'] !== $_POST['password_confirmation']){
    $msg = "As passwords não coincidem!";
    header( "Location: index.php" ); die;
} else {
    createUser();
}

function createUser()
{
    require('../lib/socket.class.php');
    require('../lib/socketClient.class.php');

    $username = $_POST['first_name']."_".$_POST['last_name'];
    $socket = new socketClient('127.0.0.1', 8000);

    $packet = array('controller'    => 'register',
        'action'	=> 'register',
        'args' => ['email'=> $_POST['email'], 'pass' => $_POST['password'], 'username' => $username]);

    $response = json_decode($socket->send(json_encode($packet)));

    if($response->success == 1)
    {
        echo "<div class=\"alert alert-success\">";
        echo("Utilizador inserido com sucesso!");
        echo "</div>";
        header("location:../index.php");
    }
    else
    {
        echo("Houve um erro ao introduzir o utilizador!");
    }
}