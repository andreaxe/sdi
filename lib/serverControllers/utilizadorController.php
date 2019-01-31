<<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "ConnectDB.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class utilizadorController{

    public $args;

    public function  __construct($args)
    {
        $this->args = $args;
    }

    public function consultaUtilizador()
    {
        $connection = ConnectDB::getInstance()->getConnection();
        $results =  array();

        $query = "SELECT * from utilizador where idu =".$this->args->args->idu;

        if ($result = mysqli_query( $connection, $query )){
            while ($row = mysqli_fetch_array($result)) {
                $row_array['idu'] = $row['idu'];
                $row_array['nome'] = $row['nome'];
                $row_array['nif'] = $row['nif'];
                $row_array['datan'] = $row['datan'];
                $row_array['email'] = $row['email'];
                $row_array['cc'] = $row['cc'];
                $row_array['telef'] = $row['telef'];


                array_push($results,$row_array);
            }
        }
        mysqli_close($connection);
        return json_encode($results);
    }
}