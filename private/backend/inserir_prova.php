<?php
include_once('../../ConnectDB.php');
require('../../Email.php');

// importante ver os erros do php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$connection = ConnectDB::getInstance()->getConnection();
$query      = "SELECT * from evento where ativo = 1;";
$resultado  = mysqli_query($connection, $query);

if (isset($_POST['submitButton'])) {

    $designacao = mysqli_real_escape_string($connection, $_POST['designacao']);
    $hora       = mysqli_real_escape_string($connection, $_POST['hora']);
    $ide        = mysqli_real_escape_string($connection, $_POST['ide']);

    $query = "INSERT INTO prova(designacao, hora, idevento) VALUES('$designacao', '$hora', '$ide')";
    $prova = mysqli_query($connection, $query);
    mysqli_close($connection);

    if ($prova) {
        # tratar do acesso à internet no container
        //enviarEmail($email, $nome, $senha);
        header("location:index.php?nova_prova=true");
    } else {
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
    <h2 class="titulo" style="margin-top: 20px;">Inserir Prova</h2>
    <form role="form" action="" method="POST">
      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <input type="text" name="designacao" id="designacao" class="form-control input-sm" placeholder="Designação" required>
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
            <input type="time" name="hora" id="hora" class="form-control input-sm" placeholder="Hora" required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-3 col-sm-3 col-md-1" style="line-height: 2;">
          <small>Evento:</small>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-5">
          <div class="form-group">
            <select class="form-control" name="ide" id="ide">
                <?php while ($row = $resultado->fetch_array()) { ?>
                  <option value="<?= $row['ide'] ?>"><?= $row['designacao']; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
        </div>
      </div>

      <hr>
      <button type="submit" class="btn btn-default" name="submitButton">Submit</button>

    </form>

  </div>
<?php include('include/footer.php'); ?>