<?php
namespace STUN;

use Generator;
use STUN\Enums\Attribute as MessageAttribute;
use STUN\Enums\Error;

/**
 * Represents a STUN message attribute, providing methods to parse, manipulate,
 * and retrieve STUN attributes from raw data. Attributes are key-value pairs
 * that form part of a STUN message.
 *
 * @package STUN
 */
final class Attribute {

	/**
	 * The name of the attribute, represented by an enum from STUN\Enums\Attribute.
	 *
	 * @var MessageAttribute
	 */
	public readonly MessageAttribute $name;

	/**
	 * The value of the attribute.
	 *
	 * @var string
	 */
	public string $value;

	/**
	 * The message to which this attribute belongs.
	 *
	 * @var Message
	 */
	private Message $message;

	/**
	 * Constructor for the Attribute class.
	 * Initializes the attribute name and value from raw binary data or array format.
	 *
	 * @param string|array|null $data The binary data or an associative array containing attribute data.
	 * @throws AssertionError If the attribute name cannot be validated.
	 */
	public function __construct(string|array|null $data = null){
		// A string received from client-side
		if(is_string($data)){
			[
				'name'=>$name,
				'length'=>$length,
				'value'=>$value
			] = unpack("nname/nlength/a*value", $data);

			// Validate attribute name
			assert($name = MessageAttribute::tryFrom($name), "Unable to validate attribute");

			$data = [
				'name'=>$name,
				'value'=>substr($value, 0, $length)
			];
		}

		$this->name = $data['name'];
		$this->value = $data['value'];
	}

	/**
	 * Calculates the length of the attribute value, optionally including padding.
	 *
	 * @param bool $include_padding Whether to include padding in the length calculation.
	 * @return int The length of the attribute value, with or without padding.
	 */
	public function length(bool $include_padding = false): int {
		return strlen($this->value) + $this->paddingSize() * $include_padding;
	}

	/**
	 * Calculates the amount of padding required to align the attribute to a 32-bit boundary.
	 *
	 * @return int The padding size in bytes.
	 */
	public function paddingSize(): int {
		$i = 0;

		while(($i + strlen($this->value)) % 4 !== 0){
			$i++;
		}

		return $i;
	}

	/**
	 * Retrieves the value of the attribute, performing any necessary decoding.
	 * Currently handles XOR attributes.
	 *
	 * @return mixed The decoded value of the attribute, or null for unsupported types.
	 */
	public function value(): mixed {
		switch($this->name){
			case MessageAttribute::XOR_PEER_ADDRESS:
			case MessageAttribute::XOR_MAPPED_ADDRESS:
			case MessageAttribute::XOR_RELAYED_ADDRESS:
				// Handle XOR address cases
			break;
		}

		return null;
	}

	/**
	 * Parses raw binary data to extract and yield individual STUN attributes.
	 *
	 * @param string $data The raw binary data representing STUN attributes.
	 * @return Generator|null A generator yielding Attribute instances.
	 */
	public static function parse(string $data): ?Generator {
		$i = 0;

		while($i < strlen($data)){
			$length = ord($data[$i + 2]) << 8 | ord($data[$i + 3]);

			yield $attr = new Attribute(substr($data, $i, $length + 4));

			$i+= $attr->length(true) + 4;
		}

		return null;
	}

	/**
	 * Set STUN message to the following attribute
	 *
	 * @param Message $message
	 * @return self
	 */
	public function setMessage(Message $message): self{
		$this->message = $message;

		return $this;
	}

	public function __toString(){
		return pack('nna*', 
			$this->name->value, 
			$this->length(), 
			str_pad($this->value, $this->length(true), "\x0")
		);
	}
}
