<?php

use STUN\Address;
use STUN\Attribute;
use STUN\Enums\Error;
use STUN\Enums\Method;
use STUN\Enums\Type;
use STUN\Message;
use STUN\Socket;
use STUN\Enums\Attribute as Attr;

class TURNHandler{
  private Socket $socket;

  public array $ips = [];

  public array $sockets = [];

  public function __construct(Socket $master){
    $this->socket = $master;
    $this->add($master);
  }

  public function add(Socket $socket){
    $this->sockets[spl_object_id($socket->master)] =
    $this->ips[(string)$socket->client_address] = 
    $this->ips[(string)$socket->address] = $socket;
  }

  public function bind(Socket $socket, Message $msg, Address $addr): void{
    $reply = clone $msg;
    $reply
    ->setClass(Type::RESPONSE)
    ->setAttribute(Attribute::XORMappedAddress($addr, $reply))
    ->removeAttributes();

    if($socket === $this->socket){
      $socket->send($reply, $addr);
      return;
    }
    else{
      $indication = new Message();
      $peer = $this->ips[(string)$addr];
      $indication
      ->setClass(Type::INDICATION)
      ->setMethod(Method::DATA)
      ->setAttribute(
        Attribute::Data($msg),
        Attribute::XORPeerAddress($peer->address, $indication)
      );

      $this->socket->send($indication, $socket->client_address);
    }
  }

  public function allocate(Socket $socket, Message $msg, Address $addr): void{
    $username = $msg->getAttribute(Attr::USERNAME);
    $realm = $msg->getAttribute(Attr::REALM);
    $reply = clone $msg;
    $reply
    ->removeAttributes()
    ->setClass(Type::RESPONSE);

    if(!$username){
      $reply
      ->setClass(Type::ERROR)
      ->setAttribute(
        Attribute::Realm('nwncorner'),
        Attribute::Nonce(),
        Attribute::ErrorCode(Error::UNAUTHENTICATED, 'Unknown user')
      );
    }
    else{
      $relay = new Socket(new Address($addr->ip));
      $relay->client_address = $addr;

      $reply->setAttribute(
        Attribute::Lifetime(10),
        Attribute::Software('turn'),
        Attribute::Realm('nwncorner'),
        Attribute::XORRelayedAddress($relay->address, $reply),
        Attribute::XORMappedAddress($addr, $reply)
      );

      $reply->setAttribute(Attribute::MessageIntegrity('test', $username, $realm, $reply));
      $reply->setAttribute(Attribute::Fingerprint($reply));

      $this->add($relay);
    }

    $socket->send($reply, $addr);
  }

  public function permission(Socket $socket, Message $msg, Address $addr): void{
    $realm = $msg->getAttribute(Attr::REALM)->value();
    $username = $msg->getAttribute(Attr::USERNAME)->value();
    $p_addr = $msg->getAttribute(Attr::XOR_PEER_ADDRESS)->value();
    $user = $this->ips[(string)$addr];
    $reply = clone $msg;
    $reply
    ->removeAttributes()
    ->setClass(Type::RESPONSE);

    $user->peer_address = $p_addr;
    $reply->setAttribute(
      Attribute::Software('turn'),
      Attribute::XORMappedAddress($addr, $reply)
    );

    $reply->setAttribute(Attribute::MessageIntegrity('test', $username, $realm, $reply));
    $reply->setAttribute(Attribute::Fingerprint($reply));
    $socket->send($reply, $addr);
  }

  public function send(Socket $socket, Message $msg, Address $addr): void{
    /**
     * @var Socket
     */
    $user = $this->ips[(string)$addr];
    $data = $msg->getAttribute(Attr::DATA)->value();

    $user->send($data);
  }
  
  public function channelBind(Socket $socket, Message $msg, Address $addr): void{
    $realm = $msg->getAttribute(Attr::REALM)->value();
    $username = $msg->getAttribute(Attr::USERNAME)->value();
    $reply = clone $msg;
    $reply
    ->removeAttributes()
    ->setClass(Type::RESPONSE);

    $reply->setAttribute(Attribute::MessageIntegrity('test', $username, $realm, $reply));
    $socket->send($reply, $addr);
  }

  public function process(Socket $socket, Message|string $msg, Address $addr): void{
    if(is_string($msg)){
      $indication = new Message();
      $peer = $this->ips[(string)$addr];
      $indication
      ->setClass(Type::INDICATION)
      ->setMethod(Method::DATA)
      ->setAttribute(
        Attribute::Data($msg),
        Attribute::XORPeerAddress($peer->address, $indication)
      );

      $this->socket->send($indication, $socket->client_address);

      return;
    }

    switch($msg->getMethod()){
      case Method::BINDING : $this->bind($socket, $msg, $addr); break;
      case Method::ALLOCATE : $this->allocate($socket, $msg, $addr); break;
      case Method::CREATE_PERMISSION : $this->permission($socket, $msg, $addr); break;
      case Method::SEND : $this->send($socket, $msg, $addr); break;
      case Method::CHANNEL_BIND : $this->channelBind($socket, $msg, $addr); break;
    }
  }
}