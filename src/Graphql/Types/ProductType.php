<?php

namespace App\GraphQL\Types;

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
                        'type' => Type::listOf(TypeRegistry::attribute()),
                        'resolve' => function ($product) {
                            return array_map(function ($attr) {
                                if (is_object($attr) && method_exists($attr, 'toFrontendFormat')) {
                                    return $attr->toFrontendFormat();
                                }
                                return null;
                            }, $product['attributes'] ?? []);
                        }
                    ]

                ];
            }
        ]);
    }
}
