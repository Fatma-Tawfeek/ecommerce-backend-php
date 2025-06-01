<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Order',
            'fields' => [
                'id' => Type::id(),
                'totalPrice' => Type::float(),
                'itemsNumber' => Type::int(),
                'products' => Type::listOf(Type::string())
            ]
        ]);
    }
}
