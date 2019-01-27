<?php
unset($_SESSION['token']);
session_destroy();
header("location: ../../index.php");