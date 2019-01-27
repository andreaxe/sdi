<?php
/**
 * Controlo da sessão
 */
session_start();
if(!isset($_SESSION['token'])){
    header('location:../../index.php');
}

?>

<!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">

	<title>ATW</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">
	<!-- Bootstrap v3-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Custom CSS -->
	<link rel="stylesheet" href="../../../assets/css/custom.css">
    <link rel="stylesheet" href="../../../assets/css/tabela.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
	<!-- jQuery library -->
		<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
		<![endif]-->
	</head>

	<body>
		<nav class="navbar navbar-default no-margin-bottom">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
					aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><img class="img-responsive" src="../../../assets/img/run_logo.png" alt="logo" /></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li class="sem_ponto"><a href="#">Admin</a></li>
					<!--<li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Opcoes <b class="caret"></b></a>
                        <ul class="dropdown-menu">

                        </ul>
                    </li>-->

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>