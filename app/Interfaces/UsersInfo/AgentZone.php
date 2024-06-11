<?php

namespace App\Interfaces\UsersInfo;

/**
 * Security level of user
 */
interface AgentZone
{
    /**
     * 3 digit unique string
     * relative to partner
     */
    function getErpID(): string;

}