<?php

namespace App\Interfaces\Core;

/**
 * Represents a generic key-value pair
 * relative to a category scope. A
 * category can have 1 parent.
 */
interface Category
{
    /**
     * Category key.
     */
    public function getKey();

    /**
     * Category value.
     */
    public function getValue();

    /**
     * Category scope.
     */
    public function getScope();

    /**
     * Parent category.
     */
    public function getParent();
}
