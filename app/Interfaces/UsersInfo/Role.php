<?php

namespace App\Interfaces\UsersInfo;

/**
 * Information about role of user
 */
interface Role
{
    function getKey(): string;

    /**
     * Brief description about role
     */
    function getDescription(): string;
}
