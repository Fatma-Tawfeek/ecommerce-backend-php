<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'name' => Type::string(),
                'type' => Type::string(),
                'items' => [
                    'type' => Type::listOf(Type::nonNull(
                        new ObjectType([
                            'name' => 'AttributeItem',
                            'fields' => [
                                'id' => Type::id(),
                                'displayValue' => Type::string(),
                                'value' => Type::string(),
                            ]
                        ])
                    ))
                ]
            ]
        ]);
    }
}
