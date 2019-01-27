<?php include('include/header.php'); ?>
<?php include('../../ConnectDB.php'); ?>
<?php
$connection = ConnectDB::getInstance()->getConnection();
$query = "SELECT evento.ativo, evento.coordenadas, evento.designacao as evento, evento.categoria, evento.dataevento, 
          evento.local, prova.designacao as prova, prova.idp, prova.hora as hora FROM prova INNER JOIN evento 
          ON evento.ide = prova.idevento";
$provas = mysqli_query($connection, $query);

// Criação do CSV
if(isset($_POST['prova']) && !empty($_POST['prova'])) {

    $filename = "toy_csv.csv";
    $fp = fopen('php://output', 'w');

    $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='cvp' AND TABLE_NAME='evento'";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_row($result)) {
        $header[] = $row[0];
    }
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename='.$filename);
    fputcsv($fp, $header);

    $num_column = count($header);
    $query = "SELECT * FROM evento";
    $result = mysqli_query($connection, $query);
    while($row = mysqli_fetch_row($result)) {
        fputcsv($fp, $row);
    }
    exit;
}
?>
    <div class="container">
    	<div class="row">
    		<div class="col-sm-3">
                <?php include('include/sidebar.php') ?>
    		</div>
    		<div class="col-sm-9">
                <h2 class="titulo">Consultar provas</h2>
                <table id="example" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Prova</th>
                        <th>Evento</th>
                        <th>Local</th>
                        <th>Coordenadas</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th>Activo</th>
                        <th>Download</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Prova</th>
                        <th>Evento</th>
                        <th>Local</th>
                        <th>Coordenadas</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th>Activo</th>
                        <th>Download</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php while ($row = $provas->fetch_array()) { ?>
                        <tr>
                            <td><a href="#"><?= $row["prova"]; ?></a></td>
                            <td><?php echo $row['evento']; ?></td>
                            <td><?php echo $row['local']; ?></td>
                            <td><?= $row['coordenadas']; ?></td>
                            <td><?= $row['categoria']; ?></td>
                            <td><?= $row['dataevento']; ?></td>
                            <?php if($row['ativo'] == 1){ ?>
                                <td><i class="fa fa-check" aria-hidden="true"></i></td>
                            <?php } else { ?>
                                <td><i class="fa fa-times" aria-hidden="true"></i></td>
                            <?php } ?>
                            <td>
                                <a href="<?php echo 'php_csv_export.php?prova='.$row['idp']; ?>">Exportar csv</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

    		</div>




    		<!--<div class="col-md-2 offset-md-4"><h2 style="color: #ABABAB;"><img class="img-responsive" src="assets/img/Logo-Maratona.png"></h2></div>-->
    	</div>
    </div>
</div>
<?php include('include/footer.php'); ?>
<script>
    $(".criar_csv").click(function (e) {
        var prova = $(this).attr('value'); // $(this) refers to button that was clicked
        e.preventDefault();
        $.ajax({
            type: 'post',
            data: {prova: prova},
            success: function(response){
                console.log(response)
            }
        });
    });

</script>
