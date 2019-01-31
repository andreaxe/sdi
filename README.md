Trabalho de SDI
============================
	
Tema:
Interface “mobile first” para SGBD utilizando sockets

Objetivo:

1. Pretende-se criar um interface de utilizador, privilegiando o conceito “mobile first” que, através da utilização de sockets, permita disponibilizar métodos para leitura, escrita e atualização de dados numa base de dados de gestão de eventos e provas desportivas.

Requisitos:
1. A prova de conceito deverá ser disponibilizada com um protótipo que permita testar os seguintes métodos:
2. autenticação do utilizador;
3. listagem de provas disponíveis;
4. inscrição de utilizador em provas;
5. listagem das provas que o utilizador se encontra inscrito;
6. dados do utilizador.

A aplicação servidor deverá permitir acesso concorrente.

As operações que impliquem alterações no SGBD deverão ser realizadas por "datastream". As operações que não impliquem alterações no SGBD poderão ser efetuadas através de "datagram".

Aspetos a Avaliar:

1. Interface do utilizador;
2. métodos utilizados;
3. abordagem na utilização dos sockets;
4. protocolo da aplicação; concorrência;
5. eficiência da aplicação.

Valorização:
Apresentar uma versão para android; iphone; ou windows mobile da aplicação.

Instruções para deploy do projecto
============
Para colocar a funcionar este projecto é preciso seguir as instruções disponiveis nos seguintes passos:
 
* certificar-se que tem o docker instalado;
* clonar este repositório para o seu computador;
* Criar o container e correr através do seguinte comando: $ docker run --name app -d -p 8010:80 -v raiz/do/projecto:/var/www/app/ romeoz/docker-apache-php
* Copiar para o clipboard o id do container disponivel na seguinte lista: $ docker ps
* Aceder ao container através do seguinte comando: docker exec -it **id_do_container** bash

No interior do container correr o servidor:
* php server.php

Depois de certificar-se que as notas prévias foram cumpridas, abrir o browser e consultar o seguinte endereço:

* http://localhost:8010 
* registar um utilizador e efectuar o login

#### Notas prévias:
* Instalar ou usar um servidor mysql já existente e importar o ficheiro 'cvp.sql' disponivel na raiz do projecto. 
* Alterar as credenciais de acesso à BD no ficheiro 'ConnectDB.php'
* Não se esqueça de alterar o caminho **'raiz/do/projecto'** pela raiz do directório onde se encontra o projecto na sua máquina local.

##### Problemas na configuração do servidor através do docker

Na eventualidade de não ser possivel o deploy do projecto através de um container docker, o mesmo deverá ser possivel correr através do servidor XAAMP ou mesmo configurando o apache, assumindo que as **notas prévias** são cumpridas.

### Considerações finais:

* Foram cumpridas todos os requisitos propostos contudo apenas há um aspecto que não foi possivel melhorar (conforme detectado na apresentação prévia) que tem a ver com o redireccionamento automático em situações em que o servidor socket se encontra indisponivel.
Foi possivel minimizar erros que poderiam surgir ao utilizador contudo não foi possivel em tempo útil melhorar muito mais nesse aspecto.

* A aplicação cliente após autenticação correcta no servidor de sockets, regista uma sessão que permite navegar por entre as páginas autenticado e efectuar pedidos ao servidor socket. Contudo em caso de anomalia no servidor socket o comportamento não é o ideal (vai de encontro à problemática do ponto anterior).

* Quantos aos aspectos positivos, a aplicação foi desenhada tendo em consideração uma interface responsive ideal para se adaptar a qualquer dispositivo (desktop, mobile). Todos os aspectos funcionais do site funcionam de acordo com o proposto e não se detectou anomalias durante a navegação, provando-se consistente. 
 

Exemplos:
=========

[Alt text](./assets/img/braga.jpg?raw=true "Title")

**./ConnectDB.php (Conexão à base de dados)**

Alterar as variáveis de classe existentes neste ficheiro para se conectar à sua base de dados.

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


[Alt text](./assets/img/braga.jpg?raw=true "Title")

**./server.php (Servidor)**

Caso seja necessário alterar a porta usada pelo socket deverá alterar a váriável $port

    <?php    
    $port = 8000;


[Alt text](./assets/img/braga.jpg?raw=true "Title")

**./lib/socketClient.class.php (Cliente)**

No caso do cliente deverá alterar no constructor da classe *socketClient*

    Class socketClient extends socket{
    
        private $connected = True;
    
    	function __construct($ip = "127.0.0.1", $port = 8000, $auth = false){
    		parent::__construct($ip, $port, $auth);
    	}



[Alt text](./assets/img/braga.jpg?raw=true "Title")


Exemplo de criação um array com a informação da classe, método e argumentos a serem executados pelo servidor:

    <?php
    require('./lib/socket.class.php');
    require('./lib/socketClient.class.php');
    
    $socket = new socketClient('127.0.0.1', 8000);
    
    $packet = array('controller' => 'index', 'action' => 'login', 
                    'args' => ['email'=> $_POST['email'], 'pass' => $_POST['password']]);
    $results = json_decode($socket->send(json_encode($packet)));
    
    $response = $socket->send(json_encode($packet));
        
    ?>

Servidor
------------------

**./server.php (Server)**
    
       <?php
       
       // Funcão que encaminha o pedido para o controlador respectivo
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
       
       // Socket server começa aqui
       set_time_limit (0);
       // Set the ip and port we will listen on
       $address = '127.0.0.1';
       $port = 8000;
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
       ?>

Interface gráfica
===================

![image](https://user-images.githubusercontent.com/9929973/51805947-9c431d80-226b-11e9-9b21-0f8cfe9067a3.png)

![image](https://user-images.githubusercontent.com/9929973/52072278-9e172480-257c-11e9-9812-2a8dcdc06da5.png)

![image](https://user-images.githubusercontent.com/9929973/52072418-dae31b80-257c-11e9-91c1-779af1b3ea32.png)


