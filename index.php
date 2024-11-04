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

$ip = '192.168.18.4';
$port = 9000;
/**
 * Client will send server messages 
 * @var Socket
 */
$server = new Socket(new Address($ip, $port));
$handler = new TURNHandler($server);

$null = null;
while(1){
  $read = array_map(fn($e)=>$e->master, $handler->sockets);

  /**
   * Nothing interesting going on. Move along
   */
  if(!socket_select($read, $null, $null, 1))
    continue;

  foreach($read as $socket){
    $socket = $handler->sockets[spl_object_id($socket)];
    /**
     * Issues with the socket or a false alarm.
     * 
     * Continue to the next socket
     */
    if(!$data = $socket->read($address))
      continue;

    /**
     * This is part of a TURN message
     */
    if(ord($data[0]) & 192){
      #todo: add stuff
      $user = $handler->ips[(string)$address];
      $peer = $handler->ips[(string)$user->peer_address];

      $socket->send($data, $peer->client_address);
    }
    else{
      if(substr($data, 4, 4) === "\x21\x12\xa4\x42")
        $handler->process($socket, new Message($data), $address);
      else
        $handler->process($socket, $data, $address);
    }
    
  }
}
