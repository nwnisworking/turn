<?php
namespace STUN\Enums;

/**
 * Enum representing STUN message attributes as defined by various RFCs.
 * Each attribute is represented by a case and a corresponding integer value.
 *
 * @package STUN\Enums
 */
enum Attribute: int {

	/**
	 * Indicates the mapped IP address and port.
	 */
	case MAPPED_ADDRESS = 0x0001;

	/**
	 * Contains the username information used for authentication.
	 */
	case USERNAME = 0x0006;

	/**
	 * Used to ensure the message integrity of STUN requests or responses.
	 */
	case MESSAGE_INTEGRITY = 0x0008;

	/**
	 * Contains error information in case of a failed request.
	 */
	case ERROR_CODE = 0x0009;

	/**
	 * Identifies a specific communication channel.
	 */
	case CHANNEL_NUMBER = 0x000C;

	/**
	 * Defines the time duration for an allocation.
	 */
	case LIFETIME = 0x000D;

	/**
	 * The XOR'd address of a peer.
	 */
	case XOR_PEER_ADDRESS = 0x0012;

	/**
	 * Used to transmit application data.
	 */
	case DATA = 0x0013;

	/**
	 * Provides the realm for a STUN server.
	 */
	case REALM = 0x0014;

	/**
	 * Contains a unique value used to prevent replay attacks.
	 */
	case NONCE = 0x0015;

	/**
	 * The XOR'd address of the relayed endpoint.
	 */
	case XOR_RELAYED_ADDRESS = 0x0016;

	/**
	 * Specifies the requested IP address family (IPv4 or IPv6).
	 */
	case REQUESTED_ADDRESS_FAMILY = 0x0017;

	/**
	 * Requests an even-numbered port for the allocation.
	 */
	case EVEN_PORT = 0x0018;

	/**
	 * Specifies the transport protocol (e.g., UDP, TCP).
	 */
	case REQUESTED_TRANSPORT = 0x0019;

	/**
	 * Requests that the STUN message should not be fragmented.
	 */
	case DONT_FRAGMENT = 0x001A;

	/**
	 * Contains an access token for authentication.
	 */
	case ACCESS_TOKEN = 0x001B;

	/**
	 * Ensures message integrity using SHA-256.
	 */
	case MESSAGE_INTEGRITY_SHA256 = 0x001C;

	/**
	 * Specifies the algorithm used to hash the password.
	 */
	case PASSWORD_ALGORITHM = 0x001D;

	/**
	 * Contains a hash of the username for verification.
	 */
	case USERHASH = 0x001E;

	/**
	 * @var int XOR_MAPPED_ADDRESS: XOR'd mapped IP address and port.
	 */
	case XOR_MAPPED_ADDRESS = 0x0020;

	/**
	 * @var int RESERVATION_TOKEN: Token for allocating a specific port.
	 */
	case RESERVATION_TOKEN = 0x0022;

	/**
	 * @var int PRIORITY: Specifies the priority of the ICE candidate.
	 */
	case PRIORITY = 0x0024;

	/**
	 * @var int USE_CANDIDATE: Indicates that a candidate is ready for nomination in ICE.
	 */
	case USE_CANDIDATE = 0x0025;

	/**
	 * @var int PADDING: Used to pad the message to a multiple of 4 bytes.
	 */
	case PADDING = 0x0026;

	/**
	 * @var int RESPONSE_PORT: Specifies the port for the response.
	 */
	case RESPONSE_PORT = 0x0027;

	/**
	 * @var int CONNECTION_ID: Specifies the ID for a specific connection.
	 */
	case CONNECTION_ID = 0x002A;

	/**
	 * @var int ADDITIONAL_ADDRESS_FAMILY: Requests support for additional address families.
	 */
	case ADDITIONAL_ADDRESS_FAMILY = 0x8000;

	/**
	 * @var int ADDRESS_ERROR_CODE: Contains error code information related to addresses.
	 */
	case ADDRESS_ERROR_CODE = 0x8001;

	/**
	 * @var int PASSWORD_ALGORITHMS: Specifies supported password algorithms.
	 */
	case PASSWORD_ALGORITHMS = 0x8002;

	/**
	 * @var int ALTERNATE_DOMAIN: Specifies an alternate domain for the server.
	 */
	case ALTERNATE_DOMAIN = 0x8003;

	/**
	 * @var int ICMP: Internet Control Message Protocol data.
	 */
	case ICMP = 0x8004;

	/**
	 * @var int SOFTWARE: Contains information about the software being used by the server.
	 */
	case SOFTWARE = 0x8022;

	/**
	 * @var int ALTERNATE_SERVER: Specifies an alternate server address.
	 */
	case ALTERNATE_SERVER = 0x8023;

	/**
	 * @var int TRANSACTION_TRANSMIT_COUNTER: Tracks the number of transmissions for a specific transaction.
	 */
	case TRANSACTION_TRANSMIT_COUNTER = 0x8025;

	/**
	 * @var int CACHE_TIMEOUT: Specifies how long an entry can be cached.
	 */
	case CACHE_TIMEOUT = 0x8027;

	/**
	 * @var int FINGERPRINT: Used to verify message integrity using CRC32.
	 */
	case FINGERPRINT = 0x8028;

	/**
	 * @var int ICE_CONTROLLED: Used to indicate ICE control during a session.
	 */
	case ICE_CONTROLLED = 0x8029;

	/**
	 * @var int ICE_CONTROLLING: Indicates which peer is controlling the ICE process.
	 */
	case ICE_CONTROLLING = 0x802A;

	/**
	 * @var int RESPONSE_ORIGIN: Contains the origin address of the response.
	 */
	case RESPONSE_ORIGIN = 0x802B;

	/**
	 * @var int OTHER_ADDRESS: Specifies an alternate address for response purposes.
	 */
	case OTHER_ADDRESS = 0x802C;

	/**
	 * @var int ECN_CHECK: Used to check for Explicit Congestion Notification (ECN) support.
	 */
	case ECN_CHECK = 0x802D;

	/**
	 * @var int THIRD_PARTY_AUTHORIZATION: Authorizes a third-party to send messages on behalf of the client.
	 */
	case THIRD_PARTY_AUTHORIZATION = 0x802E;

	/**
	 * @var int MOBILITY_TICKET: Contains a ticket for mobility support.
	 */
	case MOBILITY_TICKET = 0x8030;

	/**
	 * @var int CISCO_STUN_FLOWDATA: Cisco-specific STUN flow data.
	 */
	case CISCO_STUN_FLOWDATA = 0xC000;

	/**
	 * @var int ENF_FLOW_DESCRIPTION: Flow description for Endpoint Flow (ENF) functionality.
	 */
	case ENF_FLOW_DESCRIPTION = 0xC001;

	/**
	 * @var int ENF_NETWORK_STATUS: Network status information for ENF functionality.
	 */
	case ENF_NETWORK_STATUS = 0xC002;

	/**
	 * @var int CISCO_WEBEX_FLOW_INFO: Cisco-specific flow information for WebEx.
	 */
	case CISCO_WEBEX_FLOW_INFO = 0xC003;

	/**
	 * @var int CITRIX_TRANSACTION_ID: Citrix-specific transaction ID.
	 */
	case CITRIX_TRANSACTION_ID = 0xC056;

	/**
	 * @var int GOOG_NETWORK_INFO: Google-specific network information.
	 */
	case GOOG_NETWORK_INFO = 0xC057;

	/**
	 * @var int GOOG_LAST_ICE_CHECK_RECEIVED: Google-specific data for ICE checks.
	 */
	case GOOG_LAST_ICE_CHECK_RECEIVED = 0xC058;

	/**
	 * @var int GOOG_MISC_INFO: Google-specific miscellaneous information.
	 */
	case GOOG_MISC_INFO = 0xC059;

	/**
	 * @var int GOOG_OBSOLETE_1: Obsolete attribute used in older Google protocols.
	 */
	case GOOG_OBSOLETE_1 = 0xC05A;

	/**
	 * @var int GOOG_CONNECTION_ID: Google-specific connection ID.
	 */
	case GOOG_CONNECTION_ID = 0xC05B;

	/**
	 * @var int GOOG_DELTA: Delta synchronization for Google-specific protocols.
	 */
	case GOOG_DELTA = 0xC05C;

	/**
	 * @var int GOOG_DELTA_ACK: Acknowledgment of a delta in Google-specific protocols.
	 */
	case GOOG_DELTA_ACK = 0xC05D;

	/**
	 * @var int GOOG_DELTA_SYNC_REQ: Request for delta synchronization in Google-specific protocols.
	 */
	case GOOG_DELTA_SYNC_REQ = 0xC05E;

	/**
	 * @var int GOOG_MESSAGE_INTEGRITY_32: Google-specific message integrity using 32-bit hashes.
	 */
	case GOOG_MESSAGE_INTEGRITY_32 = 0xC060;
}
