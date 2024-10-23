<?php
use STUN\Address;
use STUN\Attribute;
use STUN\Enums\Attribute as Attr;
use STUN\Enums\Method;
use STUN\Enums\Type;
use STUN\Message;
use STUN\Server;
use STUN\Socket;

spl_autoload_register(function(string $class){
  require_once "libraries/$class.php";
});

$server = new Server(new Address('127.0.0.1', 9000));
$clients = [];

while(1){
  $read = iterator_to_array($server->sockets);
  $null = null;

  socket_select($read, $null, $null, 0);

  foreach($read as $socket){
    /** @var Socket */
    $socket = $server->sockets[$socket];

    if(!$data = $socket->read($address))
      continue;

      /**@var Message */
    $data = new Message($data);

    switch($data->getMethod()){
      case Method::BINDING : 
        $data
        ->setClass(Type::RESPONSE)
        ->setAttribute(
          Attribute::XORMappedAddress($address, $data)
        );

        $socket->send($data, $address);
      break;
      case Method::ALLOCATE : 
        $relay = $server->allocate($address);

        $clients[] = '#['.count($server->sockets)."] client: $relay->client_address, server: $relay->address\n";

        if(count($clients) === 2){
          file_put_contents('ip.log', json_encode($clients, JSON_PRETTY_PRINT));
        }

        $data
        ->setClass(Type::RESPONSE)
        ->removeAttributes()
        ->setAttribute(
          Attribute::Lifetime(300),
          Attribute::Software('hello-world'),
          Attribute::XORRelayedAddress($relay->address, $data),
          Attribute::XORMappedAddress($address, $data)
        );

        $socket->send($data, $address);
      break;
      case Method::CREATE_PERMISSION : 
        $peer_address = $data
        ->getAttribute(Attr::XOR_PEER_ADDRESS)
        ->value();

        $a = $server->clientAddress($address);
        $b = $server->localAddress($peer_address);
        $a->peer_address = $b->address;
        $b->peer_address = $a->address;

        $data
        ->setClass(Type::RESPONSE)
        ->removeAttributes()
        ->setAttribute(
          Attribute::Software('hello-world')
        );

        $socket->send($data, $address);
      break;
      case Method::SEND : 
        $peer_address = $data->getAttribute(Attr::XOR_PEER_ADDRESS)->value();
        $msg = new Message($data->getAttribute(Attr::DATA)->value());

        var_dump($msg);
        
        // $server->socket->send($msg, $peer_address);
      break;
    }
  }
}