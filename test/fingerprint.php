<?php
spl_autoload_register(function(string $class){
  require_once "../libraries/$class.php";
});

/**
 * This is a message from reference.pcapng
 * 
 * The index is 91
 */

use STUN\Message;
use STUN\Enums\Attribute as Attr;
use STUN\Attribute;

 
$hex = "0001004c2112a4424f382f645079454e376a6e7a0006000974686f353a55365555000000c0570004000003e7802900082b2c9a309840e50b002400046e001fff0008001429fae316e202f50b0698c30d11972968c14f81cd80280004c34c7260";

$original = new Message(hex2bin($hex));
$modified = new Message($original);

echo "Fingerprint:     ".bin2hex($original->getAttribute(Attr::FINGERPRINT)->value())."\n";

/**
 * The Attribute::Fingerprint function will clone and remove the Fingerprint field
 * You can comment to see the line below intended effect
 */

$modified->removeAttribute(Attr::FINGERPRINT);

$modified->setAttribute(Attribute::Fingerprint($modified));

echo "New Fingerprint: ".bin2hex($modified->getAttribute(Attr::FINGERPRINT)->value())."\n";
