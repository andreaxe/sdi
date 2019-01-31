<?php
include('ConnectDB.php');
session_start();
if (!isset($_SESSION['token'])) {
    header("location:index.php");
}

$database = new ConnectDB();
$connection = $database->db_connection;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('./lib/socket.class.php');
require('./lib/socketClient.class.php');
$socket = new socketClient('127.0.0.1', 8000);

if(!$socket->get_status()){
    // socket não está conectado!
    header('Location: logout.php');
}
//die();
//if(empty($socket->response)){
//    header('Location: index.php');
//}

$packet = array('controller'=> 'prova', 'action' => 'provaEvento',
    'args' => ['idu'=> $_SESSION['idu']]);
$results = json_decode($socket->send(json_encode($packet)));


if(isset($_POST['remover_prova'])){

    $id_utilizador = $_SESSION['idu'];
    $id_prova = htmlentities($_POST['remover_prova'], ENT_COMPAT, 'UTF-8');
    $packet = array('controller'=> 'prova', 'action' => 'removerProva',
    'args' => ['prova'=> $id_prova, 'user' => $id_utilizador]);

    $results = json_decode($socket->send(json_encode($packet)));
    if ($results->success){
        return True;
    }
    return False;
}

if(isset($_POST['prova'])){

    $id_utilizador = $_SESSION['idu'];
    $prova = htmlentities($_POST['prova'], ENT_COMPAT, 'UTF-8');
    $data = date('Y-m-d');
    // Não percebo muito bem este campo na tabela de inscrições...
    $limite = date('Y-m-d');

    $packet = array('controller'=> 'prova', 'action' => 'verificarProvaInscricao',
        'args' => ['prova'=> $prova, 'user' => $id_utilizador]);
    $results = json_decode($socket->send(json_encode($packet)));

    if($results->success){
        header("Content-Type: text/json; charset=utf8");
        $query = "INSERT INTO inscricoes(idutilizador, idprova, datainsc, datalimite)VALUES('$id_utilizador', '$prova','$data', '$limite')";
        $inscricao = mysqli_query($connection , $query);

      if($inscricao)
      {
          header("Refresh:0");
      }
      else
      {
        header("Refresh:0");
      }
    }
    header('Location: '.$_SERVER['REQUEST_URI']);
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
      <h3 class="titulo">Lista de Provas e Eventos</h3>
      <table id="prova" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th>Evento</th>
          <th>Prova</th>
          <th>Categoria</th>
          <th>Localidade</th>
          <th>Data</th>
          <th>Hora</th>
          <th>Coordenadas</th>
          <th>Inscrição</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results->provas as $row) { ?>
          <tr>
            <td><a href="#"><?= $row->evento ?></a></td>
            <td><?= $row->prova ?></td>
            <td><?= $row->categoria ?></td>
            <td><?= $row->local?></td>
            <td><?= $row->dataevento ?></td>
            <td><?= $row->hora ?></td>
            <td><?= $row->coordenadas ?></td>
            <td>
                <form action="" method="post">
              <button type="submit" name="prova" value="<?= $row->idp; ?>" class="btn btn-default btn-xs">clique aqui</button>
                </form>
            </td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
<!--    <div class="col-sm-3">-->
<!--      <h3 class="titulo">Localizações</h3>-->
<!--      <div id="map"></div>-->
<!--    </div>-->
  </div>

  <div class="row">
    <div class="col-sm-12">
      <h3 class="titulo">Lista de Provas onde se encontra inscrito</h3>
        <?php if (empty($results->inscrito)){ ?>
            <table id="inscrito" class="hide table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th>Evento</th>
          <th>Prova</th>
          <th>Categoria</th>
          <th>Localidade</th>
          <th>Data</th>
          <th>Hora</th>
          <th>Coordenadas</th>
          <th>Inscrição</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
            </table>
          <u id="zero_inscritos">Não se encontra inscrito em nenhuma prova!</u>
        <?php } else
        { ?>
      <table id="inscrito" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
          <th>Prova</th>
            <th>Evento</th>
            <th>Categoria</th>
          <th>Localidade</th>
          <th>Data inscrição</th>
          <th>Data e Hora evento</th>
          <th>Coordenadas</th>
          <th>Inscrição</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($results->inscrito as $row) { ?>
            <tr>
                <td><?= $row->prova ?></td>
                <td><a href="#"><?= $row->categoria ?></a></td>
            <td><?= $row->categoria_evento ?></td>
            <td><?= $row->localidade ?></td>
            <td><?php echo($row->datainsc) ?></td>
            <td><?php echo($row->dataevento . ' ' . $row->hora) ?></td>
            <td><?= $row->coordenadas ?></td>
            <td>
              <button id="inscrever" type="button" value="<?= $row->idp; ?>" class="btn btn-danger btn-xs remover_prova">
                Remover inscrição</button>
            </td>
          </tr>
        <?php } ?>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<div class="navbar navbar-fixed-bottom">
  <div class="container-fluid"
       style="background-color: #F8F8F8; padding: 10px; border-top: 1px solid #ebebeb; margin-top:25px;">
    <div class="container">
      <div class="row">
        <div class="col-sm-6" style="background-color: #F8F8F8">
          <small>Trabalho de ATW - 1º momento de avaliação</small>
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

<script type="text/javascript">

    $(document).ready(function(){

        // initMap();

            $('#prova').DataTable();
            $('#inscrito').DataTable();




        function initMap()
        {
            var uluru  = {lat: -25.363, lng: 131.044};
            var map    = new google.maps.Map(document.getElementById('map'), {
                zoom  : 4,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map     : map
            });
        }

        $(".remover_prova").click(function (e) {

            var prova = $(this).attr('value'); // $(this) refers to button that was clicked
            var $this = $(this);
            e.preventDefault();
            $.ajax({
                type: 'post',
                data: {remover_prova: prova},
                success: function(response){
                    console.log(response);
                    console.log($(this))
                    $this.closest("tr").remove();
                }
            });
        });

        $(".inscrever_prova").click(function (e) {

            var prova = $(this).attr('value'); // $(this) refers to button that was clicked
            var $this = $(this);
            var row = $(this).closest('tr').clone();
            var table = $("#inscrito");
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: "provas.php",
                data: {prova: prova},
                success: function(data) {
                    if (!data.success) {
                        alert("Já se encontra inscrito numa prova do mesmo evento!");
                        // console.log(data.success);
                    } // Just for demonstration purposes
                    else {
                        $this.closest('tr').remove();
                        var inscrito_table = $("#inscrito tbody");
                        console.log(inscrito_table);
                        if (table.hasClass('hide')) {
                            table.removeClass('hide');
                            $('#inscrito tr').eq(1).remove();
                        }
                        inscrito_table.append(row);
                        $("#zero_inscritos").remove();
                        // $this.removeClass('btn-default inscrever_prova');
                        // $this.addClass('btn-success disable');
                        $this.text("Remover inscricao");
                    }
                }
            })
        });
    });
</script>

<!--<script async defer-->
<!--        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAPnDxgZy0PPb4Se9BLcpFoAtyVfrLe61U&callback=initMap">-->
<!---->
<!--</script>-->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/datatables.min.js"></script>
</body>
</html>