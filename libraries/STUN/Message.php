<?php
namespace STUN;

use SplObjectStorage;
use STUN\Enums\Attribute as MessageAttribute;
use STUN\Enums\Method;
use STUN\Enums\Type;

/**
 * Class Message
 * 
 * Represents a STUN message that can contain various attributes and 
 * methods as defined by the STUN protocol. This class handles message 
 * creation, manipulation, and serialization.
 *
 * @package STUN
 */
class Message {
	/**
	 * @var int $type The type of the STUN message, which includes 
	 *               both method and class information.
	 */
	public int $type = 0;

	/**
	 * @var string|null $cookie The STUN cookie used for message 
	 *                          integrity and identification.
	 */
	public ?string $cookie;

	/**
	 * @var string|null $id Unique identifier for the STUN message, 
	 *                     generated if not provided.
	 */
	public ?string $id;

	/**
	 * @var SplObjectStorage $attributes Stores the attributes associated 
	 *                                   with the STUN message.
	 */
	public SplObjectStorage $attributes;

	/**
	 * Message constructor.
	 * 
	 * Initializes a new STUN message with optional data. If no data is 
	 * provided, a default cookie and a random ID are generated.
	 *
	 * @param array|string|null $data Optional data to initialize the 
	 *                                message.
	 */
	public function __construct(array|string|null $data = null) {
		$this->attributes = new SplObjectStorage;

		if(!isset($data)){
			$this->cookie = hex2bin('2112a442');
			$this->id = openssl_random_pseudo_bytes(12);
		}
		else{
			if(is_string($data)){
				$data = unpack('ntype/nlength/a4cookie/a12id/a*attributes', $data);
			}

			$this->type = $data['type'];
			$this->cookie = $data['cookie'];
			$this->id = $data['id'];

			/**
			 * @var Attribute
			 */
			foreach (Attribute::parse($data['attributes']) as $attr) {
				$this->attributes->attach($attr->name, $attr);
				$attr->setMessage($this);
			}
		}
	}

	/**
	 * Sets the class of the STUN message.
	 *
	 * @param Type $type The type to set for the message.
	 * @return $this
	 */
	public function setClass(Type $type): self {
		$this->type &= 0x3eef;
		$this->type |= $type->value;

		return $this;
	}

	/**
	 * Sets the method of the STUN message.
	 *
	 * @param Method $method The method to set for the message.
	 * @return $this
	 */
	public function setMethod(Method $method): self {
		$this->type &= 0x110;
		$this->type |= $method->value;

		return $this;
	}

	/**
	 * Gets the class of the STUN message.
	 *
	 * @return Type The class type of the STUN message.
	 */
	public function getClass(): Type {
		return Type::tryFrom($this->type & 0x110);
	}

	/**
	 * Gets the method of the STUN message.
	 *
	 * @return Method The method type of the STUN message.
	 */
	public function getMethod(): Method {
		return Method::tryFrom($this->type & 0x3eef);
	}

	/**
	 * Sets one or more attributes for the STUN message.
	 *
	 * @param Attribute ...$attributes The attributes to attach to the message.
	 * @return $this
	 */
	public function setAttribute(Attribute ...$attributes): self {
		/**
		 * @var Attribute
		 */
		foreach($attributes as $attribute){
			$this->attributes->attach($attribute->name, $attribute);
			$attribute->setMessage($this);
		}

		return $this;
	}

	/**
	 * Removes all attributes from the STUN message.
	 *
	 * @return $this
	 */
	public function removeAttributes(): self {
		$this->attributes->removeAll($this->attributes);

		return $this;
	}

	/**
	 * Gets a specific attribute from the STUN message.
	 *
	 * @param MessageAttribute $attribute The attribute to retrieve.
	 * @return Attribute|null The requested attribute or null if not found.
	 */
	public function getAttribute(MessageAttribute $attribute): ?Attribute {
		return $this->attributes->contains($attribute) ? $this->attributes[$attribute] : null;
	}

	/**
	 * Calculates the total length of the STUN message including attributes.
	 *
	 * @return int The total length of the message.
	 */
	public function length(): int {
		$i = 0;
		foreach($this->attributes as $attr){
			/** @var Attribute */
			$attribute = $this->attributes[$attr];
			$i += $attribute->length(true) + 4;
		}

		return $i;
	}

	public function __toString() {
		$attributes = '';

		foreach ($this->attributes as $attr){
			$attributes .= $this->attributes[$attr];
		}

		return pack('nna4a12a*', $this->type, $this->length(), $this->cookie, $this->id, $attributes);
	}
}
