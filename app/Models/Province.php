<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ISO',
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
