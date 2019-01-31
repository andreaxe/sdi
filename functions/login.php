<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 26-01-2019
 * Time: 14:59
 */

if (isset($_POST['email']) && isset($_POST['password'])) {

    require('../lib/socket.class.php');
    require('../lib/socketClient.class.php');

    $socket = new socketClient('127.0.0.1', 8000);

    if(!$socket->get_status()){
        // socket não está conectado!
        header('Location: ../logout.php');
    }

    $packet = array('controller'    => 'index',
        'action'	=> 'login',
        'args' => ['email'=> $_POST['email'], 'pass' => $_POST['password']]);

    $admin = $packet['args']['email'] == 'andre@andre.pt' ? true : false;

    $response = json_decode($socket->send(json_encode($packet)));
    if (isset($response->success)){
        if($response->success == 1){
            session_start();
            $_SESSION['token'] = $response->token;
            $_SESSION['idu'] = $response->idu;
            $_SESSION['nome'] = $response->nome;
            mysqli_close($connection);
            if ($admin){
                header("location: ../private/backend/index.php");
            }
            else {
                header("location: ../provas.php");
            }
        }
    }
   $socket->report();
//    $socket->close();

}