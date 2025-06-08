<?php

namespace App\GraphQL\Resolvers;

use App\Models\Category;

class CategoryResolver
{
    public static function getAll(): array
    {
        return Category::all();
    }
}
