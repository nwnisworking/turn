<?php
namespace STUN\Enums;

/**
 * Enum representing STUN message types.
 * These types define the nature of the STUN message being transmitted.
 * 
 * @package STUN\Enums
 */
enum Type: int {
    /**
     * A STUN request message, initiating a transaction.
     */
    case REQUEST = 0x0;

    /**
     * A STUN indication message, which does not expect a response.
     */
    case INDICATION = 0x8;

    /**
     * A STUN response message, sent in reply to a request.
     */
    case RESPONSE = 0x100;

    /**
     * A STUN error response, sent when a request fails.
     */
    case ERROR = 0x110;
}