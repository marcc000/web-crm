<?php

namespace App\Interfaces\Core\Addressing;

/**
 * Represents a generic CAP
 */
interface Country
{
    /**
     * Unique code for countries
     */
    public function getCodeIso(): string;

    /**
     * Country name
     */
    public function getName(): string;
}
