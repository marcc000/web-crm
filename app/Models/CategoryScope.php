<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get the parent category scope.
     */
    public function parentScope(): HasOne
    {
        return $this->hasOne(CategoryScope::class);
    }
}
