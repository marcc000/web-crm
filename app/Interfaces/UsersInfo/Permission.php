<?php

namespace App\Interfaces\UsersInfo;

/**
 * Security level of user
 */
interface Permission
{
    function getKey(): string;

    /**
     * Information about the level of the permission
     */
    function getDescription(): string;
}
