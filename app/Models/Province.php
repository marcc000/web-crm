<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
