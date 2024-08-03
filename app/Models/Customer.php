<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'erp_id',
        'business_name',
        'vat_number',
        'PEC',
        'default_address',
        'default_delivery_address',
        'default_contact',
        'zone',
        'exported',
        'price_list',
        'product_category',
        'sales_category',
        'channel',
        'seasonality',
        'payment_method',
    ];

    /**
     * Get the addresses owned by the customer.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id', 'erp_id');
    }

    /**
     * Get the main address.
     */
    public function mainAddress(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the default delivery address.
     */
    public function defaultDeliveryAddress(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * The zones that own the customer.
     */
    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(AgentZone::class, 'customer_zone');
    }

    /**
     * Get the sales channel.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'channel', 'key');
    }

    /**
     * Get the pricelist.
     */
    public function priceList(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'price_list', 'key');
    }

    /**
     * Get the seasonality.
     */
    public function seasonality(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'seasonality', 'key');
    }

    /**
     * Get the product category.
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'product_category', 'key');
    }

    /**
     * Get the sales category.
     */
    public function salesCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sales_category', 'key');
    }
}
