<?php

namespace App\Interfaces\Core\Addressing;;

/**
 * Represents a generic CAP
 */
interface Country
{
    /**
     * Unique code for countries
     */
    function getCodeIso(): string;

    /**
     * Country name
     */
    function getName(): string;
}
