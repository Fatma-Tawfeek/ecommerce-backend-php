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
            'name' => 'Category',
            'fields' => function () {
                return [
                    'id' => Type::id(),
                    'name' => Type::string(),
                ];
            }
        ]);
    }
}
