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
Docker 

Example:
===
**./index.php (Client)**
Client builds and sends a JSON encoded packet.

    <?php
    require('./lib/socket.class.php');
    require('./lib/socketClient.class.php');
    
    $socket = new socketClient('127.0.0.1', 54321);
    
    $packet = array('controller'    => 'index',
    				'action'	    => 'index',
    				'subaction'	    => '',
    				'subaction_id'  => '',
    				'time'		    => time(),
    				'ip'		    => $_SERVER['SERVER_ADDR'],
    				);
    
    $response = $socket->send(json_encode($packet));
    
    $socket->report();
    ?>

**./server.php (Server)**
    
    <?php
    require('./lib/socket.class.php');
    require('./lib/socketServer.class.php');
    
    new socketServer("0.0.0.0", 54321);
    ?>
