<?php
session_start();
unset($_SESSION['token']);
unset($_SESSION['nome']);
unset($_SESSION['idu']);
$_SESSION = array();
session_destroy();
header("location: ../../index.php");