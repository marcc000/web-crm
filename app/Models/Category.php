<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'description',
        'category_scope',
        'parent_category',
    ];

    /**
     * Get the category scope.
     */
    public function categoryScope(): HasOne
    {
        return $this->hasOne(CategoryScope::class);
    }

    /**
     * Get the parent category.
     */
    public function parentCategory(): HasOne
    {
        return $this->hasOne(Category::class);
    }
}
