<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "ConnectDB.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class provaController{

    public $args;

    public function  __construct($args)
    {
        $this->args = $args;
    }

    public function verificar_prova_inscricao($prova, $user, $connection)
    {
        /*
         * O utilizador apenas pode inscrever-se numa única prova de um dado evento.
         */
        $query = "SELECT * from inscricoes INNER JOIN prova on prova.idp = inscricoes.idprova where idutilizador=".$user." ";
        $eventos = mysqli_query($connection, $query)->fetch_all();

        if(empty($eventos)){
            return true;
        }

        $evento = mysqli_query($connection, "SELECT idevento FROM prova where prova.idp =".$prova)->fetch_row();
        foreach($eventos as $value){
            if($evento[0] == $value[7]){
                return false;
            }
        }

        return true;
    }

    public function provaEvento()
    {
        $query            = "SELECT * FROM prova INNER JOIN evento ON evento.ide = prova.idevento";
        $connection = ConnectDB::getInstance()->getConnection();
        $results = array('provas' => array(), 'inscrito' => array());

        if ($result = mysqli_query( $connection, $query )){
            while ($row = mysqli_fetch_array($result)) {

                $row_array['idp'] = $row['idp'];
                $row_array['ide'] = $row['ide'];
                $row_array['idevento'] = $row['idevento'];
                $row_array['hora'] = $row['hora'];
                $row_array['prova'] = $row[1];
                $row_array['evento'] = $row[5];
                $row_array['local'] = $row['local'];
                $row_array['coordenadas'] = $row['coordenadas'];
                $row_array['categoria'] = $row['categoria'];
                $row_array['dataevento'] = $row['dataevento'];
                $row_array['ativo'] = $row['ativo'];

                array_push($results['provas'], $row_array);
            }
        }

        $query_inscricoes = "SELECT * FROM inscricoes INNER JOIN prova on prova.idp = inscricoes.idprova INNER JOIN evento on 
               evento.ide = prova.idevento where idutilizador = ".$this->args->args->idu;

        $fp = fopen('lidn.txt', 'a');
        fwrite($fp, $this->args);
        fclose($fp);

        if ($result = mysqli_query( $connection, $query_inscricoes )){
            while ($row = mysqli_fetch_array($result)) {
                $row_array['designacao_evento'] = $row[9];
                $row_array['idp'] = $row[1];
                $row_array['datainsc'] = $row[2];
                $row_array['idevento'] = $row[3];
                $row_array['hora'] = $row[4];
                $row_array['prova'] = $row[5];
                $row_array['hora'] = $row[6];
                $row_array['local'] = $row[7];
                $row_array['coordenadas'] = $row[8];
                $row_array['categoria'] = $row[9];
                $row_array['localidade'] = $row[10];
                $row_array['coordenadas'] = $row[11];
                $row_array['categoria_evento'] = $row[12];
                $row_array['dataevento'] = $row[13];
                $row_array['cemas5'] = $row[14];

                array_push($results['inscrito'],$row_array);
            }
        }

        return json_encode($results);
    }
}