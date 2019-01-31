<?php

$port = 8000;

/*********function to check new order******************/
function run_controller($route){
    /* create controllers class instance & inject core */
    $controller = './lib/serverControllers/'.$route->controller.'Controller.php';
    if(file_exists($controller)) {
        require_once($controller);
        $class = $route->controller.'Controller';
        if(class_exists($class)) {
            $controller = new $class($route);
        }
    } else {
        require_once('./lib/serverControllers/not_foundController.php');
        $controller = new not_foundController();
    }
    /* check the root class is callable */
    if (is_callable(array($controller, $route->action)) === false) {
        /* index() method because not found method */
        $action = 'index';
    } else {
        /* action() method is callable */
        $action = $route->action;
    }
    /* run the action method */
    return $controller->{$action}();
}
/*************************************/
/********Socket Server*********************/
set_time_limit (0);
// Set the ip and port we will listen on
$address = '127.0.0.1';
// Create a TCP Stream socket
$sock = socket_create(AF_INET, SOCK_STREAM, 0); // 0 for  SQL_TCP
// Bind the socket to an address/port
socket_bind($sock, 0, $port) or die('Could not bind to address');  //0 for localhost
// Start listening for connections
socket_listen($sock);
//loop and listen

while (true) {
    /* Accept incoming  requests and handle them as child processes */
    $client =  socket_accept($sock);
    // Read the input  from the client – 1024000 bytes
    $input =  socket_read($client, 1024000);
    $output =  json_decode($input);

    $response = run_controller($output);
    /* run the action method */
    // Display output  back to client
    socket_write($client, $response);
    socket_close($client);
}
// Close the master sockets
socket_close($sock);