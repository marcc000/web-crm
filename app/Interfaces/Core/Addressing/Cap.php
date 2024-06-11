<?php

namespace App\Interfaces\Core\Addressing;

/**
 * Represents a generic CAP
 */
interface Cap
{
    /**
     * Unique 5 digits postal code
     */
    public function getKey(): string;

    /**
     * City name
     */
    public function getCity(): string;
}
