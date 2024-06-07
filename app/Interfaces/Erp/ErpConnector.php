<?php

namespace App\Interfaces\Erp;

/**
 * Connector to communicate with the
 * ERP through SOAP web services
 */
interface ErpConnector
{
    /**
     * Creates a Prospect in the ERP
     */
    public function createProspect();
}
