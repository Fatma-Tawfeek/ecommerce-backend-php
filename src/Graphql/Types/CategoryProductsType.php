<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class CategoryProductsType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'CategoryProducts',
            'fields' => [
                'categoryName' => Type::string(),
                'products' => Type::listOf(\App\GraphQL\Types\TypeRegistry::product())
            ]
        ]);
    }
}
