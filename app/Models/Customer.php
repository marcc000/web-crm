<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'tax_id',
        'PEC',
        'default_address_id',
        'default_contact_id',
        'active',
        'exported',
        'price_list',
        'product_category',
        'sales_category',
        'channel',
        'seasonality',
        'payment_method',
        'partner_id',
        'default_delivery_address_id',
    ];

    public function addresses(): HasMany
    {
        return $this->HasMany(Address::class);
    }

    /**
     * Get the main address.
     */
    public function mainAddress(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the postal info of the address.
     */
    public function cap(): HasOne
    {
        return $this->HasOne(Cap::class);
    }

    /**
     * Get the default delivery address.
     */
    public function defaultDeliveryAddress(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the sales channel.
     */
    public function channel(): HasOne
    {
        return $this->hasOne(Category::class, localKey: 'channel');
    }

    /**
     * Get the seasonality.
     */
    public function seasonality(): HasOne
    {
        return $this->hasOne(Category::class, localKey: 'seasonality');
    }

    /**
     * Get the product category.
     */
    public function productCategory(): HasOne
    {
        return $this->hasOne(Category::class, localKey: 'product_category');
    }

    /**
     * Get the sales category.
     */
    public function salesCategory(): HasOne
    {
        return $this->hasOne(Category::class, localKey: 'sales_category');
    }
}
