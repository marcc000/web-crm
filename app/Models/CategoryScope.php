<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CategoryScope extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_scope';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'description',
        'category_scope_id',
    ];

    /**
     * Get the parent category scope.
     */
    public function parentScope(): HasOne
    {
        return $this->hasOne(CategoryScope::class);
    }
}
