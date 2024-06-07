<?php

namespace App\Interfaces\Core;

/**
 * Represents a generic address
 */
interface Address
{
    /**
     * 3 digit unique string
     * relative to partner
     */
    public function getErpID(): string;

    /**
     * Street name + number
     */
    public function getAddress(): string;

    /**
     * Address description
     */
    public function getDescription(): string;

    /**
     * True if address is active
     * False if disabled
     */
    public function isActive(): bool;
}
