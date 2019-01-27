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

    $packet = array('controller'    => 'index',
        'action'	=> 'login',
        'args' => ['email'=> $_POST['email'], 'pass' => $_POST['password']]);

    $response = json_decode($socket->send(json_encode($packet)));
    echo $response->success;
    if($response->success == 1){
        session_start();
        $_SESSION['token'] = $response->token;
        mysqli_close($connection);
        header("location: ../private/backend/index.php");
/*        }
        else {
            header("location: provas.php");
        }*/
    }
   $socket->report();
}