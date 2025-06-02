<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'amount' => Type::float(),
                'currency' => [
                    'type' => new ObjectType([
                        'name' => 'Currency',
                        'fields' => [
                            'label' => Type::string(),
                            'symbol' => Type::string()
                        ]
                    ]),
                    'resolve' => function ($price) {
                        return [
                            'label' => $price['label'],
                            'symbol' => $price['symbol']
                        ];
                    }
                ]
            ]
        ]);
    }
}
