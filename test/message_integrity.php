<?php
spl_autoload_register(function(string $class){
  require_once "../libraries/$class.php";
});

/**
 * This is a message from reference.pcapng
 * 
 * The index is 87
 * username: nwnisworking
 * password: password
 * realm: atlantis-software.net
 */

use STUN\Message;
use STUN\Enums\Attribute as Attr;
use STUN\Attribute;

$hex = "010300482112a4426544462f4342307375574852001600080001c2535e12a443000d000400000258002000080001ebd55e12a443802200096e6f64652d7475726e0000000008001402e293416c1accd279be0a2d4e26f90479235711";
$user = Attribute::Username('nwnisworking');
$realm = Attribute::Realm("atlantis-software.net");

$original = new Message(hex2bin($hex));
$modified = new Message($original);

echo "Message Integrity: ".bin2hex($original->getAttribute(Attr::MESSAGE_INTEGRITY)->value())."\n";

/**
 * The Attribute::MessageIntegrity function will clone and remove the Message Integrity field
 * You can comment to see the line below intended effect
 */

$modified->removeAttribute(Attr::MESSAGE_INTEGRITY);

$modified->setAttribute(
  Attribute::MessageIntegrity(
    "password",
    $user,
    $realm,
    $modified
  )
);

echo "New Integrity:     ".bin2hex($modified->getAttribute(Attr::MESSAGE_INTEGRITY)->value())."\n";