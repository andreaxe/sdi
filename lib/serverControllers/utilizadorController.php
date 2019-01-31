<<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "ConnectDB.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class utilizadorController{

    public $args;
    public $connection;

    public function  __construct($args)
    {
        $this->args = $args;
        $database = new ConnectDB();
        $this->connection = $database->db_connection;
    }

    public function consultaUtilizador()
    {
        $results =  array();

        $query = "SELECT * from utilizador where idu =".$this->args->args->idu;

        if ($result = mysqli_query( $this->connection, $query )){
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
        return json_encode($results);
    }

    public function editaUtilizador(){

        $nome = $this->args->args->nome;
        $nif = $this->args->args->nif;
        $datan = $this->args->args->datan;
        $cc = $this->args->args->cc;
        $telef = $this->args->args->telef;
        $uid = $this->args->args->uid;
        $email = $this->args->args->email;

        $query = "UPDATE utilizador SET email ='".$email."', nome ='".$nome."', nif = '".$nif."', datan = '".$datan."'
        ,  cc = '".$cc."', telef = '".$telef."' WHERE idu = '".$uid."'";

        if(mysqli_query($this->connection, $query)){
            return json_encode(array('success'=> 1));
        }
        return json_encode(array('success'=> 0));

    }
}