<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'erpID',
        'address',
        'description',
        'cap',
        'province',
        'country',
        'customer_id',
    ];

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

    /**
     * Get the owner of the address.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the postal info of the address.
     */
    public function cap(): HasOne
    {
        return $this->HasOne(Cap::class);
    }

    /**
     * Get the province of the address.
     */
    public function province(): HasOne
    {
        return $this->HasOne(Province::class);
    }

    /**
     * Get the country of the address.
     */
    public function country(): HasOne
    {
        return $this->HasOne(Country::class);
    }
}
