<?php
include('ConnectDB.php');
session_start();
if (!isset($_SESSION['token'])) {
    header("location:index.php");
}
require('./lib/socket.class.php');
require('./lib/socketClient.class.php');
$socket = new socketClient('127.0.0.1', 8000);
//die();
//if(empty($socket->response)){
//    header('Location: index.php');
//}

$packet = array('controller'=> 'utilizador', 'action' => 'consultaUtilizador',
    'args' => ['idu'=> $_SESSION['idu']]);
$results = json_decode($socket->send(json_encode($packet)));

foreach ($results as $row) {
    $nome = $row->nome;
    $nif = $row->nif;
    $datan = $row->datan;
    $email = $row->email;
    $cc = $row->cc;
    $telef = $row->telef;
}

if(isset($_POST['dados_utilizador'])){

    $packet = array('controller'=> 'utilizador', 'action' => 'editaUtilizador',
        'args' => ['uid'=> $_SESSION['idu'], 'nome' => $_POST['nome'], 'nif'=> $_POST['nif'], 'datan' => $_POST['datan'],
            'cc'=> $_POST['cc'], 'telef' => $_POST['telef'], 'email' => $_POST['email']]);
    $results = json_decode($socket->send(json_encode($packet)));

    if($results->success){
        header('Location: '.$_SERVER['REQUEST_URI']);
    }

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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/tabela.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
    <!-- Bootstrap TOUR -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/css/bootstrap-tour.min.css"
          rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/js/bootstrap-tour.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>

    <style>
        #map {
            height : 400px;
            width  : 100%;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-default no-margin-bottom">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img class="img-responsive" src="assets/img/run_logo.png" /></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown open">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?php echo $_SESSION['nome'] ?>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="provas.php">Provas</a></li>
                        <li><a href="utilizador.php">Definições</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">Terminar sessão</a></li>
                    </ul>
                </li>

            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-->
</nav>
<div class="container">

    <div class="row">
        <div class="col-sm-12">
            <h3 class="titulo">Dados de utilizador</h3>
            <table id="prova" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Nif</th>
                    <th>Data de Nascimento</th>
                    <th>Localidade</th>
                    <th>Email</th>
                    <th>CC</th>
                    <th>Telefone</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $row) { ?>
                    <tr>
                        <td><a href="#"><?= $row->nome ?></a></td>
                        <td><?= $row->nif ?></td>
                        <td><?= $row->datan ?></td>
                        <td><?= $row->email?></td>
                        <td><?= $row->cc ?></td>
                        <td><?= $row->telef ?></td>

                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-12">
            <h3 class="titulo">Editar dados de utilizador</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="Nome">Nome</label>
                    <input type="text" class="form-control" name="nome" value="<?= $nome ?>">
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $email ?>">
                </div>
                <div class="form-group">
                    <label for="Nif">Nif</label>
                    <input type="text" class="form-control" name="nif" value="<?= $nif ?>">
                </div>
                <div class="form-group">
                    <label for="datan">Data de Nascimento</label>
                    <input type="date" class="form-control" name="datan" value="<?= $datan ?>" required>
                </div>
                <div class="form-group">
                    <label for="cc">Cartão de cidadão</label>
                    <input type="text" class="form-control" name="cc" value="<?= $cc ?>">
                </div>
                <div class="form-group">
                    <label for="Telefone">Telefone</label>
                    <input type="text" class="form-control" name="telef" value="<?= $telef ?>">
                </div>
                <button type="submit" name="dados_utilizador" class="btn btn-success">Submeter</button>
            </form>
        </div>
    </div>

</div>
<div class="navbar navbar-fixed-bottom">
    <div class="container-fluid"
         style="background-color: #F8F8F8; padding: 10px; border-top: 1px solid #ebebeb; margin-top:25px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-6" style="background-color: #F8F8F8">
                    <small>Trabalho de SDI - 3º momento de avaliação</small>
                </div>
                <div class="col-sm-6" style="text-align:right;">
                    <small>André Garcia - EI072135</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Button trigger modal -->

<!-- Modal -->
<!--<script type="text/javascript">-->
<!---->
<!--  var prev    = 'Ant.';-->
<!--  var next    = 'Próx.';-->
<!--  var endtour = 'Fim';-->
<!--  var tour    = new Tour({-->
<!--    storage : false,-->
<!--    template: "<div class='popover tour'> \-->
<!--			<div class='arrow'></div> \-->
<!--			<h3 class='popover-title'></h3> \-->
<!--			<div class='popover-content'></div> \-->
<!--			<nav class='popover-navigation'> \-->
<!--				<div class='btn-group'> \-->
<!--					<button class='btn btn-default' data-role='prev'>" + prev + "</button> \-->
<!--					<button class='btn btn-default' data-role='next'>" + next + "</button> \-->
<!--				</div> \-->
<!--				<button class='btn btn-primary' data-role='end'>" + endtour + "</button> \-->
<!--			</nav> \-->
<!--		</div>",-->
<!--    name    : 'provas',-->
<!---->
<!--    onEnd   : function (tour) {-->
<!--      $('#myCarousel').carousel({cycle: true});-->
<!--    },-->
<!--    onStart : function (tour) {-->
<!--      $('#myCarousel').carousel({-->
<!--        interval: 5000,-->
<!--        pause   : "hover"-->
<!--      });-->
<!--    },-->
<!--    backdrop: true,-->
<!--    steps   : [-->
<!--      {-->
<!--        element: "#prova",-->
<!--        title  : "Lista de provas",-->
<!--        content: "Nesta página vai encontrar diferentes tabelas identificadas pela categoria a que pertencem."-->
<!--      }, {-->
<!--        element: "#inscrever",-->
<!--        title  : "Inscrição",-->
<!--        content: "Para se inscrever deverá carregar neste botão. Vai receber um email a confirmar a sua inscrição!"-->
<!--      },-->
<!--      {-->
<!--        element: "#inscrito",-->
<!--        title  : "Inscrição bem sucedida",-->
<!--        content: "No caso da inscrição ser bem sucedida, além do email deverá observar que a prova pela qual se inscreveu tem um botão diferente das outras!"-->
<!--      }-->
<!--    ]-->
<!--  });-->
<!--  // Initialize the tour-->
<!--  tour.init();-->
<!--  // Start the tour-->
<!--  //tour.start();-->
<!---->
<!--</script>-->



<!--<script async defer-->
<!--        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPnDxgZy0PPb4Se9BLcpFoAtyVfrLe61U&callback=initMap">-->
<!---->
<!--</script>-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/datatables.min.js"></script>
</body>
</html>