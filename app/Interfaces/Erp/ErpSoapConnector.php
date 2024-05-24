<?php

namespace App\Interfaces\Erp;

/**
 * Connector to communicate with the
 * ERP through SOAP web services
 */
interface ErpSoapConnector
{
    /**
     * Creates a Prospect in the ERP
     */
    function createProspect();
}
