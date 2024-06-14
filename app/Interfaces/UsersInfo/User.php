<?php

namespace App\Interfaces\UsersInfo;

/**
 * Data about a specific user
 */
interface User
{
    /**
     * Username user
     */
    public function getUsername(): string;

    /**
     * Email user
     */
    public function getEmail(): string;

    /**
     * User password
     */
    public function getPassword(): string;
}
