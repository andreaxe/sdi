<?php
include_once('../../ConnectDB.php');
require('../../Email.php');

// importante ver os erros do php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['submitButton'])){ //check if form was submitted


    $connection = ConnectDB::getInstance()->getConnection();
    $data['designacao'] = $_POST['designacao'];
    $data['local'] = $_POST['local'];
    $data['coordenadas'] = isset($_POST['coordenadas']) ? $_POST['coordenadas'] : null;
    $data['categoria'] = $_POST['categoria'];
    $data['dataevento'] = $_POST['dataevento'];
    $data['activo'] = isset($_POST['activo']) ? 1 : 0;

    criarEvento($data, $connection);
}

function criarEvento($data, $connection)
{
    $designacao    = mysqli_real_escape_string($connection, $data['designacao']);
    $local         = mysqli_real_escape_string($connection, $data['local']);
    $coordenadas   = mysqli_real_escape_string($connection, $data['coordenadas']);
    $categoria     = mysqli_real_escape_string($connection, $data['categoria']);

    $dataevento    = mysqli_real_escape_string($connection, $data['dataevento']);
    $activo        = mysqli_real_escape_string($connection, $data['activo']);

    $query = "INSERT INTO evento(designacao, local, coordenadas, categoria, dataevento, ativo )VALUES 
    ('$designacao', '$local', '$coordenadas', '$categoria', '$dataevento', '$activo')";

    $evento  = mysqli_query($connection , $query);
    mysqli_close($connection);

    if($evento)
    {
        # tratar do acesso à internet no container
        //enviarEmail($email, $nome, $senha);

        header("location:index.php?novo_evento=true");
    }
    else
    {
        echo("Houve um erro ao introduzir o utilizador!");
        print_r(mysqli_error_list($connection));
    }
}
  include('include/header.php'); ?>
  <div class="container">
  <div class="row">
  <div class="col-sm-3">
      <?php include('include/sidebar.php') ?>
  </div>
  <div class="col-sm-9">
    <h2 class="titulo" style="margin-top: 20px;">Inserir Evento</h2>
    <form role="form" action="" method="POST">
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <input type="text" name="designacao" id="designacao" class="form-control input-sm" placeholder="Designação" required>
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <input type="text" name="local" id="nif" class="form-control input-sm" placeholder="Local" required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <input type="text" name="coordenadas" id="coordenadas" class="form-control input-sm"
                   placeholder="Coordenadas" required>
          </div>
        </div>

        <div class="col-xs-3 col-sm-3 col-md-1" style="line-height: 2;">
          <small>Categoria:</small>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5">
          <div class="form-group">
            <select class="form-control" name="categoria" id="categoria">
              <option value="maratona">maratona</option>
              <option value="resistência">resistência</option>
              <option value="obstáculos">obstáculos</option>
              <option value="cross country">cross country</option>
              <option value="meia-maratona">meia-maratona</option>
              <option value="montanha">montanha</option>
              <option value="marcha">marcha</option>
              <option value="passeio">passeio</option>
              <option value="ultra-maratona">ultra-maratona</option>
              <option value="triatlo">triatlo</option>
              <option value="duatlo">duatlo</option>
              <option value="outro">outro</option>
            </select>
          </div>
        </div>
          <div class="col-sm-6">
          </div>
        <div class="col-xs-3 col-sm-3 col-md-1">
          <small>Data:</small>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5">
          <div class="form-group">
            <input type="date" name="dataevento" id="dataevento" class="form-control input-sm" placeholder="Data do evento" required>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-check-label">
          <input class="form-check-input" type="checkbox" name="activo" checked>
          <small>Activo</small>
        </label>

      </div>
      <hr>
      <button type="submit" class="btn btn-default" name="submitButton">Submit</button>

    </form>

  </div>
<?php include('include/footer.php'); ?>