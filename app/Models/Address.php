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
        'erp_id',
        'address',
        'description',
        'cap',
        'city',
        'province',
        'country',
        'customer_id',
    ];

    /**
     * Get the owner of the address.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'erp_id', 'erp_id');
    }

    /**
     * Get the postal info of the address.
     */
    public function cap(): HasOne
    {
        return $this->HasOne(Cap::class, 'code', 'cap');
    }

    /**
     * Get the province of the address.
     */
    public function province(): HasOne
    {
        return $this->HasOne(Province::class, 'ISO', 'province');
    }

    /**
     * Get the country of the address.
     */
    public function country(): HasOne
    {
        return $this->HasOne(Country::class, 'ISO', 'country');
    }
}
