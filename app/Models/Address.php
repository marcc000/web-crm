<?php

namespace App\Models;

use App\Interfaces\Core\Address as IAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model implements IAddress
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';

    public function getErpID(): string
    {
        return $this->erpID;
    }

    public function getAddress(): string
    {
        return $this->fullAddress;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
