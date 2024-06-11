<?php

namespace App\Interfaces\Core\Date;

/**
 * Represents a generic CAP
 */
interface Weekday
{
    /**
     * Name of the week
     */
    public function getName(): string;
}
