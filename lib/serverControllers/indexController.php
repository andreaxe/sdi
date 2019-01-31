<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "ConnectDB.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class indexController{

    public $args;

    public function __construct($args)
    {
        $this->args = $args;
    }

    function index(){

		$client = array(
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'port'=>$_SERVER['REMOTE_PORT'],
			'time'=>time(),
            'teste' => 'isto é um teste'
		);

		return json_encode($client);
	}

	function login(){

        $connection = ConnectDB::getInstance()->getConnection();
        $response = $this->_checkLogin($connection);
        return json_encode($response);
    }

    function consultarEvento(){

        $query = "SELECT * FROM evento;";
        $connection = ConnectDB::getInstance()->getConnection();
        $return_arr = array();

        if ($result = mysqli_query( $connection, $query )){
            while ($row = mysqli_fetch_assoc($result)) {
                $row_array['ide'] = $row['ide'];
                $row_array['designacao'] = $row['designacao'];
                $row_array['local'] = $row['local'];
                $row_array['coordenadas'] = $row['coordenadas'];
                $row_array['categoria'] = $row['categoria'];
                $row_array['dataevento'] = $row['dataevento'];
                $row_array['ativo'] = $row['ativo'];

                array_push($return_arr,$row_array);
            }
        }
        return json_encode($return_arr);
    }

    function consultarUtilizador(){

        $query = "SELECT * FROM utilizador;";
        $connection = ConnectDB::getInstance()->getConnection();
        $return_arr = array();

        if ($result = mysqli_query( $connection, $query )){
            while ($row = mysqli_fetch_assoc($result)) {
                $row_array['idu'] = $row['idu'];
                $row_array['nome'] = $row['nome'];
                $row_array['nif'] = $row['nif'];
                $row_array['cc'] = $row['cc'];
                $row_array['datan'] = $row['datan'];
                $row_array['email'] = $row['email'];
                $row_array['telef'] = $row['telef'];
                $row_array['ativo'] = $row['ativo'];

                array_push($return_arr,$row_array);
            }
        }
        return json_encode($return_arr);
    }

    private function _checkLogin($connection)
    {
        $password = sha1($this->args->args->pass);
        $email = $this->args->args->email;

        $query = "SELECT * FROM utilizador WHERE email = '".$email."' AND senha ='".$password."';";

        $fp = fopen('lidn.txt', 'a');
        fwrite($fp, $query);
        fclose($fp);

        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_object($result);

        $fp = fopen('lidn.txt', 'a');
        fwrite($fp, $row);
        fclose($fp);

        if(!$row){
            $response = array('success'=> 0, 'msg' => 'No existing user or wrong password',
                'password_hash' => $password);
        }
        else
        {
            $response = array('success' => 1, 'token' => bin2hex(random_bytes(78)),
                'idu'=> $row->idu, 'nome'=> $row->nome);
            /*if($admin){
                header("location: private/backend/index.php");
            }
            else {
                header("location: provas.php");
            }*/
        }
        mysqli_close($connection);
        return $response;
    }


}
