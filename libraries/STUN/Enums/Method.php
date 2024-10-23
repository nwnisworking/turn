<?php
namespace STUN\Enums;

/**
 * Enum representing STUN methods used for different operations within the STUN protocol.
 * Each method corresponds to a specific action performed during the STUN communication process.
 *
 * @package STUN\Enums
 */
enum Method: int {

	/**
	 * Used for binding requests to retrieve a mapped address.
	 */
	case BINDING = 0x1;

	/**
	 * Requests the allocation of a relay address.
	 */
	case ALLOCATE = 0x3;

	/**
	 * Refreshes an allocation's lifetime.
	 */
	case REFRESH = 0x4;

	/**
	 * Used to send data through a relay.
	 */
	case SEND = 0x6;

	/**
	 * Used to transmit application data.
	 */
	case DATA = 0x7;

	/**
	 * Creates permissions for a specific peer on a relay.
	 */
	case CREATE_PERMISSION = 0x8;

	/**
	 * Binds a channel number to a peer address.
	 */
	case CHANNEL_BIND = 0x9;

	/**
	 * Initiates a connection between two peers.
	 */
	case CONNECT = 0xA;

	/**
	 * Binds a connection ID to a specific connection.
	 */
	case CONNECTION_BIND = 0xB;

	/**
	 * Used to attempt a connection with a peer.
	 */
	case CONNECTION_ATTEMPT = 0xC;

	/**
	 * Google-specific method for performing a ping operation.
	 *
	 * @todo Investigate why GOOG Ping is not working.
	 */
	case GOOG_PING = 0x80;
}
