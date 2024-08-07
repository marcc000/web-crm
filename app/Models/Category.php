<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function categoryScope(): BelongsTo
    {
        return $this->belongsTo(CategoryScope::class, 'key', 'category_scope');
    }

    public function parentCategory(): HasOne
    {
        return $this->hasOne(Category::class, 'key', 'parent_category');
    }

    public static function getPriceLists()
    {
        return Category::where('category_scope', '30')->whereIn('key', ['1', '2', '10']);
    }

    public static function getProductCategories()
    {
        return Category::where('category_scope', '31');
    }

    public static function getSalesCategories()
    {
        return Category::where('category_scope', '32');
    }

    public static function getChannels()
    {
        return Category::where('category_scope', '33');
    }

    public static function getSeasonalities()
    {
        return Category::where('category_scope', '34');
    }
}
