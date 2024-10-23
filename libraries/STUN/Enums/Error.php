<?php
namespace STUN\Enums;

/**
 * Enum representing STUN error codes, which indicate various conditions encountered during the processing
 * of STUN requests and responses. These error codes follow standard STUN protocol definitions.
 *
 * @package STUN\Enums
 */
enum Error: int {

	/**
	 * Client should try an alternate server.
	 */
	case TRY_ALTERNATE = 300;

	/**
	 * The request was malformed or incomplete.
	 */
	case BAD_REQUEST = 400;

	/**
	 * The client is not authenticated.
	 */
	case UNAUTHENTICATED = 401;

	/**
	 * The request was forbidden due to insufficient permissions.
	 */
	case FORBIDDEN = 403;

	/**
	 * Mobility request is not allowed.
	 */
	case MOBILITY_FORBIDDEN = 405;

	/**
	 * An unknown or unsupported attribute was encountered.
	 */
	case UNKNOWN_ATTRIBUTE = 420;

	/**
	 * The allocation information does not match the request.
	 */
	case ALLOCATION_MISMATCH = 437;

	/**
	 * The nonce value is no longer valid and must be updated.
	 */
	case STALE_ONCE = 438;

	/**
	 * The requested address family (e.g., IPv6) is not supported.
	 */
	case ADDRESS_FAMILY_NOT_SUPPORTED = 440;

	/**
	 * Credentials provided by the client are invalid.
	 */
	case WRONG_CREDENTIALS = 441;

	/**
	 * The requested transport protocol (e.g., TCP, UDP) is unsupported.
	 */
	case UNSUPPORTED_TRANSPORT_PROTOCOL = 442;

	/**
	 * The peer's address family does not match the request.
	 */
	case PEER_ADDRESS_FAMILY_MISMATCH = 443;

	/**
	 * A connection already exists and cannot be reestablished.
	 */
	case CONNECTION_ALREADY_EXISTS = 446;

	/**
	 * Connection timed out or failed.
	 */
	case CONNECTION_TIMEOUT_OR_FAILURE = 447;

	/**
	 * The allocation limit for this session has been reached.
	 */
	case ALLOCATION_QUOTA_REACHED = 486;

	/**
	 * A role conflict occurred, and the request could not be processed.
	 */
	case ROLE_CONFLICT = 487;

	/**
	 * A general server error occurred.
	 */
	case SERVER_ERROR = 500;

	/**
	 * The server has insufficient capacity to process the request.
	 */
	case INSUFFICIENT_CAPACITY = 508;
}
