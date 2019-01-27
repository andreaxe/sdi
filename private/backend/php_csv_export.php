<?php
include('../../ConnectDB.php');
$prova = $_GET['prova'];
$connection = ConnectDB::getInstance()->getConnection();
$nome_prova = mysqli_query($connection, "SELECT designacao from prova where idp=".$prova.';')->fetch_row();
$filename = $nome_prova[0].'.csv';
$fp = fopen('php://output', 'w');

$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='cvp' AND TABLE_NAME='utilizador'";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_row($result)) {
    if (($row[0] != 'idu') && ($row[0] != 'senha')) {
        $header[] = $row[0];
    }
}
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
fputcsv($fp, $header);

$num_column = count($header);
$query = "SELECT nome,nif,cc,datan,email,telef,morada,nacionalidade,genero,ativo,federado,tempos,tamanho 
          from utilizador INNER JOIN inscricoes on inscricoes.idutilizador=utilizador.idu where inscricoes.idprova=".$prova.';';
$result = mysqli_query($connection, $query);
while($row = mysqli_fetch_row($result)) {
    fputcsv($fp, $row);
}
exit;