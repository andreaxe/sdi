<?php
include_once('ConnectDB.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = ConnectDB::getInstance()->getConnection();

if(isset($_POST['email']) && isset($_POST['password'])){
    $data['email'] = mysqli_real_escape_string($connection, $_POST['email']);
    $data['password'] = mysqli_real_escape_string($connection, $_POST['password']);

    // Verificar se o email é do administrador
    $admin = ($data['email'] == 'admin@admin.com') ? true : false;
    checkLogin($data, $connection, $admin);
}

function checkLogin($data, $connection, $admin = false)
{
    $password = sha1($data['password']);
    $query = "SELECT * FROM utilizador WHERE email = '".$data['email']."' AND senha ='".$password."'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_object($result);
    if(!$row){
        echo "<div>";
        echo "No existing user or wrong password.";
        echo "</div>";
    }
    else
        {
        session_start();
        print_r($row->idu);
        $_SESSION['idu'] = $row->idu;
        $_SESSION['nome'] = $row->nome;
        $_SESSION['email'] = $data['email'];
        mysqli_close($connection);
        if($admin){
            header("location: private/backend/index.php");
        }
        else {
            header("location: provas.php");
        }
    }
}
