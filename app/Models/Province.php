<?php

namespace App\Models;

use App\Models\Cap;
use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'province';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ISO',
        'country',
    ];

    public function caps(): HasMany
    {
        return $this->HasMany(Cap::class);
    }

    public function country(): BelongsTo
    {
        return $this->BelongsTo(Country::class);
    }
}
