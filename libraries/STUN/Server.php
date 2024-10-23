<?php
namespace STUN;

use SplObjectStorage;

class Server{
	private Socket $socket;

	private SplObjectStorage $sockets;

	private array $local_address = [];

	private array $client_address = [];

	public function __construct(Address $address){
		$this->socket = new Socket($address);
		$this->sockets[] = $this->socket;
	}

	public function allocate(Address $client_address): Socket{
		$socket = new Socket(new Address($this->socket->address->ip));
		$socket->client_address = $client_address;
		$this->local_address[(string)$socket->address] = &$socket;
		$this->client_address[(string)$client_address] = &$socket;

		$this->sockets->attach($socket->master, $socket);
		return $socket;
	}

	public function clientAddress(Address $address): Socket{
		return $this->client_address[(string)$address];
	}

	public function localAddress(Address $address): Socket{
		return $this->local_address[(string)$address];
	}

	public function run(): never{
		while(1){
			$read = iterator_to_array($this->sockets);
			$null = null;

			socket_select($read, $null, $null, 0);

			foreach($read as $socket){
				/**@var Socket */
				$socket = $this->sockets[$socket];

				if(!$data = $socket->read($address))
					continue;

				$data = new Message($data);

			}
		}
	}
}