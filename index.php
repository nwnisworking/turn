<?php

use STUN\Address;
use STUN\Attribute;
use STUN\DataChannel;
use STUN\Enums\Attribute as Attr;
use STUN\Enums\Method;
use STUN\Enums\Type;
use STUN\Enums\Error;
use STUN\Message;
use STUN\Server;
use STUN\Socket;

spl_autoload_register(function(string $class){
  require_once "libraries/$class.php";
});


$server = new Server(new Address('192.168.18.4', 9000));
$clients = [];

while(1){
  $read = iterator_to_array($server->sockets);
  $null = null;

  socket_select($read, $null, $null, 0);

  foreach($read as $socket){
    /** @var Socket */
    $socket = $server->sockets[$socket];
    /** @var ?Socket */
    $peer_socket = $server->localAddress($socket->peer_address);

    /**
     * False alarm or some issue with the data. 
     */
    if(!$data = $socket->read($address))
      continue;

    if(ord($data[0]) & 192){
      $client = $server->clientAddress($address);
      $peer = $server->localAddress($client->peer_address);

      
      $socket->send($data, $peer->client_address);
      continue;
    }
    /**
     * @var Message 
     */
    $data = new Message($data);

    /**
     * Message response does not need processing
     */
    if($data->getClass() === Type::RESPONSE)
      continue;
    
    $reply = clone $data;
    $reply
    ->setClass(Type::RESPONSE)
    ->removeAttributes();

    switch($data->getMethod()){
      case Method::BINDING : 
        $reply->setAttribute(
          Attribute::XORMappedAddress($address, $reply)
        );

        $socket->send($reply, $address);
        break;
      case Method::ALLOCATE :
        if(!$data->getAttribute(Attr::USERNAME)){
          $reply
          ->setClass(Type::ERROR)
          ->setAttribute(
            Attribute::Realm('nwncorner'),
            Attribute::Nonce(),
            Attribute::ErrorCode(Error::UNAUTHENTICATED, "Unknown user")
          );
        }
        else{
          $relay = $server->allocate($address);
          $user = $data->getAttribute(Attr::USERNAME);

          $reply
          ->setAttribute(
            Attribute::Lifetime(300),
            Attribute::Software("turn"),
            Attribute::Realm('nwncorner'),
            Attribute::XORRelayedAddress($relay->address, $reply),
            Attribute::XORMappedAddress($address, $reply)
          );

          $reply->setAttribute(Attribute::MessageIntegrity(
            'test', 
            $data->getAttribute(Attr::USERNAME), 
            $data->getAttribute(Attr::REALM), 
            $reply
          ));
          $reply->setAttribute(Attribute::Fingerprint($reply));

          $clients[$user->value] = [
            "relay"=>(string)$relay->address,
            "client"=>(string)$relay->client_address
          ];

          echo "ALLOCATE $user->value C:[$relay->client_address] with R:[$relay->address]\n";
        }

        $socket->send($reply, $address);
        break;
      case Method::CREATE_PERMISSION : 
        $realm = $data->getAttribute(Attr::REALM);
        $username = $data->getAttribute(Attr::USERNAME);
        $p_addr = $data->getAttribute(Attr::XOR_PEER_ADDRESS);
        $user = $server->clientAddress($address);
        $peer = $server->localAddress($p_addr->value());
        
        $user->peer_address = $peer->address;
        $peer->peer_address = $user->address;
        
        echo "PERMISSION $username->value R:[{$user->address}] pair with R:[{$p_addr->value()}]\n";
        $reply->setAttribute(
          Attribute::Software(10),
          Attribute::Software('turn'),
          Attribute::XORMappedAddress($address, $reply)
        );

        $reply->setAttribute(Attribute::MessageIntegrity('test', $username, $realm, $reply));
        $reply->setAttribute(Attribute::Fingerprint($reply));

        $socket->send($reply, $address);
        break;
      case Method::SEND : 
        $peer_addr = $data->getAttribute(Attr::XOR_PEER_ADDRESS);
        $dat = $data->getAttribute(Attr::DATA);
        $user = $server->clientAddress($address);
        $peer = $server->localAddress($peer_addr->value());

        $indication = new Message();
        $indication->setMethod(Method::DATA);
        $indication->setClass(Type::INDICATION);
        $indication->setAttribute($dat);
        $indication->setAttribute(Attribute::XORPeerAddress($user->address, $indication));

        $server->socket->send($indication, $peer->client_address);
        // $user->send($dat->value());
        break;
        case Method::CHANNEL_BIND : 
          $username = $data->getAttribute(Attr::USERNAME);
          $realm = $data->getAttribute(Attr::REALM);

          $reply->setAttribute(Attribute::MessageIntegrity('test', $username, $realm, $reply));
          
          $socket->send($reply, $address);
          break;
    }
  }
}

