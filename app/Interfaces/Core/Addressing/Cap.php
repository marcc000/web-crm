<?php

namespace App\Interfaces\Core\Addressing;;

/**
 * Represents a generic CAP
 */
interface Cap
{
    /**
     * Unique 5 digits postal code
     */
    function getKey(): string;

    /**
     * City name
     */
    function getCity(): string;
}
