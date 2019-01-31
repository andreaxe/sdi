<?php


class ConnectDB{
    /**
     * @var bool|mysqli
     * Alterar aqui as credenciais de acesso à Base de dados
     */

    public $db_connection = false;
    public $logs = array();
    private $_servername = "172.17.0.1"; // alterar
    private $_username = "andre"; // alterar
    private $_password = "andre"; // alterar
    private static $_instance;
    private $_database = 'cvp'; // alterar SE o nome da BD for diferente


    public function __construct() {
        $this->logs[] = "Attempting to connect to the database.";

        try{
            $this->db_connection = new mysqli($this->_servername, $this->_username,
                $this->_password, $this->_database);
            $this->logs[] = "A new database connection has been established.";
        }
        catch (mysqli_sql_exception $e) {
            $this->logs[] = "A new database connection could not be established.";
        }
    }

//    public function __destruct()
//    {
//        $this->db_connection->close();
//    }
}