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
    function getUsername(): string;

    /**
    * Email user
    */
    function getEmail(): string;

    /**
    * User password
    */
    function getPassword(): string;
}
