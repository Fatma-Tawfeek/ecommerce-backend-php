<?php

use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => function () {
                return [
                    'id' => Type::nonNull(Type::int()),
                    'name' => Type::string(),
                    'price' => Type::float(),
                    'description' => Type::string(),
                    'brand' => Type::string(),
                    'inStock' => Type::boolean(),
                    'category' => [
                        'type' => GraphQL::type('Category'),
                        'resolve' => function ($product) {
                            return Category::find($product->category_id);
                        }
                    ],
                    'attributes' => [
                        'type' => Type::listOf(GraphQL::type('Attribute')),
                        'resolve' => function ($product) {
                            // هنا بنستخدم العلاقة بين الجدولين
                            return $product->attributes;
                        }
                    ]
                ];
            }
        ]);
    }
}
