<?php

namespace App\GraphQL\Types;

use App\GraphQL\Types\AttributeType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => function () {
                return [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'description' => Type::string(),
                    'brand' => Type::string(),
                    'price' => Type::float(),
                    'inStock' => Type::boolean(),
                    'gallery' => Type::listOf(Type::string()),
                    'category' => Type::string(),

                    'attributes' => [
                        'type' => Type::listOf(new AttributeType()),
                        'resolve' => function ($product) {
                            return $product['attributes'] ?? [];
                        }
                    ]
                ];
            }
        ]);
    }
}
