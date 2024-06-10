<?php

namespace App\Interfaces\Core\Addressing;

/**
 * Represents a generic address
 */
interface Address
{
    /**
     * 3 digit unique string
     * relative to partner
     */
    function getErpID(): string;

    /**
     * Street name + number
     */
    function getAddress(): string;

    /**
     * Address description
     */
    function getDescription(): string;

    /**
     * True if address is active
     * False if disabled
     */
    function isActive(): bool;
}
