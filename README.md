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

Docker 
------------------
1. docker run --name app -d -p 8010:80 -v home/afg/Documents/SDI:/var/www/app/ romeoz/docker-apache-php
2. docker run -d --name mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root mysql:latest

Exemplos:
=========
[Alt text](./assets/img/braga.jpg?raw=true "Title")

**./index.php (Client)**
Cliente cria um array com a informação do Controlador e método a ser evocado no servidor

    <?php
    require('./lib/socket.class.php');
    require('./lib/socketClient.class.php');
    
    $socket = new socketClient('127.0.0.1', 8000);
    // Sem necessidade envio de argumentos
    $packet = array('controller'=> 'index', 'action' => 'provaEvento');
    $packet = array('controller' => 'index', 'action' => 'login', 
                    'args' => ['email'=> $_POST['email'], 'pass' => $_POST['password']]);
    $results = json_decode($socket->send(json_encode($packet)));
    
    $response = $socket->send(json_encode($packet));
    
    $socket->report();
    ?>

Com argumentos
------------------

**./server.php (Server)**
    
    <?php
    require('./lib/socket.class.php');
    require('./lib/socketServer.class.php');
    
    new socketServer("0.0.0.0", 54321);
    ?>
