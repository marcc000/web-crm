<?php

namespace App\Interfaces\UsersInfo;

/**
 * Security level of user
 */
interface Permission
{
    public function getKey(): string;

    /**
     * Information about the level of the permission
     */
    public function getDescription(): string;
}
