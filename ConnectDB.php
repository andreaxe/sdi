<?php


class ConnectDB{
    /***
     * Esta classe faz uso do Singleton design pattern
     * @var string
     */
    private $_servername = "172.17.0.1";
    private $_username = "andre";
    private $_password = "andre";
    private static $_instance; //The single instance
    private $_database = 'cvp';
    private $_connection;

    private function __construct() {
        $this->_connection = new mysqli($this->_servername, $this->_username,
            $this->_password, $this->_database);

        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to connect to MySQL: " . mysqli_connect_error(),
                E_USER_ERROR);
        }
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
    }

    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
