<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;

class ProductResolver
{
    public static function getAll(): array
    {
        return Product::all();
    }

    public static function getById($root, array $args): ?array
    {
        return Product::find($args['id']);
    }

    public static function getByCategory($root, array $args): array
    {
        return Product::getByCategory($args['categoryId']);
    }
}
