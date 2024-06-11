<?php

namespace App\Interfaces\UsersInfo;

/**
 * Information about role of user
 */
interface Role
{
    public function getKey(): string;

    /**
     * Brief description about role
     */
    public function getDescription(): string;
}
