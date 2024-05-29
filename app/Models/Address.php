<?php

namespace App\Models;

use App\Interfaces\Core;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model implements Core\Address
{
    use HasFactory;

    function getErpID() {


    }

    /**
     * 
     */
    function getFullAddress() {
        
    }


}
