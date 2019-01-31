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
Para colocar a funcionar este trabalho é preciso tomar os seguintes passos:
 
* certificar-se que tem o docker instalado;
* clonar este repositório para o seu computador;
* Criar o container e correr  através do seguinte comando: $ docker run --name app -d -p 8010:80 -v raiz/do/projecto:/var/www/app/ romeoz/docker-apache-php
* Registar o id do container na lista através do seguinte comando: $ docker ps
* docker exec -it {id do cotnainer} bash

No interior do container correr o servidor:
* php server.php

Depois de certificar-se que as notas prévias foram cumpridas, abrir o browser e consultar o seguinte endereço:

* http://localhost:8010 

Notas prévias:
* Instalar um servidor mysql e importar o ficheiro 'cvp.sql' 
* Alterar as credenciais de acesso à BD no ficheiro 'ConnectDB.php'
* Não se esqueça de alterar o caminho **'raiz/do/projecto'** pela raiz do directório onde se encontra o projecto na sua máquina local.


##### Problemas na configuração do servidor através do docker

Na eventualidade de não ser possivel o deploy do projecto através de um container docker, o mesmo deverá ser possivel correr o mesmo através do servidor XAAMP ou mesmo configurando o apache, assumindo que as **notas prévias** são cumpridas.

### Considerações finais:

* Foram cumpridas todos os requisitos propostos contudo apenas há um aspecto que não foi possivel melhorar (conforme detectado na apresentação prévia) que tem a ver com o redireccionamento automático em situações em que o servidor socket se encontra indisponivel.
Foi possivel minimizar erros que poderiam surgir ao utilizador contudo não foi possivel em tempo útil melhorar muito mais nesse aspecto.

* A aplicação cliente após autenticação correcta no servidor de sockets, regista uma sessão que permite navegar por entre as páginas autenticado e efectuar pedidos ao servidor socket. Contudo em caso de anomalia no servidor socket o comportamento não é o ideal (vai de encontro à problemática do ponto anterior).

* Quantos aos aspectos positivos, a aplicação foi desenhada tendo em consideração uma interface responsive ideal para se adaptar a qualquer dispositivo (desktop, mobile). Todos os aspectos funcionais do site funcionam de acordo com o proposto e não se detectou anomalias durante a navegação, provando-se consistente. 
 

Exemplos:
=========
[Alt text](./assets/img/braga.jpg?raw=true "Title")

**./index.php (Client)**
Cliente cria um array com a informação do Controlador e método a ser evocado no servidor

    <?php
    require('./lib/socket.class.php');
    require('./lib/socketClient.class.php');
    
    $socket = new socketClient('127.0.0.1', 8000);
    // sem envio de argumentos 
    $packet = array('controller'=> 'index', 'action' => 'provaEvento');
    // com argumentos
    $packet = array('controller' => 'index', 'action' => 'login', 
                    'args' => ['email'=> $_POST['email'], 'pass' => $_POST['password']]);
    $results = json_decode($socket->send(json_encode($packet)));
    
    $response = $socket->send(json_encode($packet));
    
    $socket->report();
    ?>

Servidor
------------------

**./server.php (Server)**
    
       <?php
       
       // Funcão para nova rota
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

![image](https://user-images.githubusercontent.com/9929973/51804651-c12f9480-225b-11e9-9783-97d4138b48bf.png)

![image](https://user-images.githubusercontent.com/9929973/51805989-3905bb00-226c-11e9-9a17-a28942895d10.png)

