<?php
namespace STUN;

/**
 * Represents an IP address and port, providing utility methods for validating
 * and working with both IPv4 and IPv6 addresses.
 * 
 * @package STUN
 */
class Address {

	/**
	 * The IP address, either IPv4 or IPv6.
	 * 
	 * @var string
	 */
	public string $ip;

	/**
	 * The port number, optional, associated with the IP address.
	 * 
	 * @var int|null
	 */
	public ?int $port;

	/**
	 * Constructor for the Address class.
	 * Initializes the address with an IP and an optional port number.
	 * 
	 * @param string $ip The IP address (IPv4 or IPv6).
	 * @param int|null $port The port number (optional).
	 */
	public function __construct(string $ip, ?int $port = null) {
		$this->ip = $ip;
		$this->port = $port;
	}

	/**
	 * Converts the IP address to a packed in_addr representation using inet_pton.
	 * 
	 * @return string|bool Packed binary format of the IP address, or false on failure.
	 */
	public function ip(): string|bool {
		return inet_pton($this->ip);
	}

	/**
	 * Converts the port number to its packed binary representation using pack.
	 * 
	 * @return string|bool Packed binary representation of the port, or false if no port is set.
	 */
	public function port(): string|bool {
		return $this->port ? pack('n', $this->port) : false;
	}

	/**
	 * Checks if the IP address is a valid IPv4 address.
	 * 
	 * @return bool True if the IP is IPv4, false otherwise.
	 */
	public function isIPV4(): bool {
		return filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
	}

	/**
	 * Checks if the IP address is a valid IPv6 address.
	 * 
	 * @return bool True if the IP is IPv6, false otherwise.
	 */
	public function isIPV6(): bool {
		return filter_var($this->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
	}

	/**
	 * Validates if the IP address is either IPv4 or IPv6.
	 * 
	 * @return bool True if the IP is valid, false otherwise.
	 */
	public function valid(): bool {
		return $this->isIPV4() || $this->isIPV6();
	}

	/**
	 * Returns the IP address as a string, including the port if available.
	 * 
	 * @return string A string representation of the IP address and port.
	 */
	public function __toString() {
		return $this->ip . ($this->port ? ":$this->port" : "");
	}
}