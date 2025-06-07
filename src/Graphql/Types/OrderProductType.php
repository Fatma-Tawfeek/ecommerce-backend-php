<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderProduct',
            'fields' => [
                'productId' => Type::int(),
                'quantity' => Type::int(),
                'selectedAttributes' => Type::listOf(
                    new ObjectType([
                        'name' => 'OrderSelectedAttribute',
                        'fields' => [
                            'name' => Type::string(),
                            'value' => Type::string()
                        ]
                    ])
                )
            ]
        ]);
    }
}
