<?php
namespace STUN;

/**
 * Represents a UDP socket for STUN communication, providing methods to read 
 * and send data to clients or peers. Supports both IPv4 and IPv6 addresses.
 * 
 * @package STUN
 */
class Socket {

	/**
	 * The master socket resource.
	 * 
	 * @var \Socket
	 */
	public readonly \Socket $master;

	/**
	 * The address to which the socket is bound (local address).
	 * 
	 * @var Address
	 */
	public readonly Address $address;

	/**
	 * The client address from which data is received.
	 * 
	 * @var Address
	 */
	public ?Address $client_address = null;

	/**
	 * The peer address to which data will be sent.
	 * 
	 * @var Address
	 */
	public ?Address $peer_address = null;

	/**
	 * Constructor for the Socket class.
	 * Initializes and binds the socket to the specified local address.
	 * 
	 * @param Address $address The local address to bind the socket to.
	 * @throws \AssertionError If the provided IP address is not valid.
	 */
    public function __construct(Address $address) {
			assert($address->valid(), 'IP address is not valid');

			$this->address = $address;

			$this->master = socket_create($address->isIPV4() ? AF_INET : AF_INET6, SOCK_DGRAM, SOL_UDP);

			socket_setopt($this->master, SOL_SOCKET, SO_REUSEADDR, true);
			socket_bind($this->master, $address->ip, $address->port);
			socket_getsockname($this->master, $this->address->ip, $this->address->port);
    }

	/**
	 * Reads data from the socket, populating the provided Address object with 
	 * the sender's address.
	 * 
	 * @param Address|null $address Address of the client from which data is received.
	 * @return string|bool The received data, or false on failure.
	 */
	public function read(Address &$address = null): string|bool {
		$result = @socket_recvfrom($this->master, $data, 1024 * 1024, 0, $ip, $port);

		// If successful, create a new Address object for the sender
		if($result){
			$address = new Address($ip, $port);
		}

		return $result ? $data : false;
	}

	/**
	 * Sends data to the specified address or peer address.
	 * 
	 * @param string $data The data to send.
	 * @param Address|null $address The destination address. If null, uses the peer address.
	 * @return bool True if data was sent successfully, false otherwise.
	 */
	public function send(string $data, ?Address $address = null): bool {
		$address ??= $this->peer_address;

		return socket_sendto($this->master, $data, strlen($data), 0, $address->ip, $address->port);
	}
}
