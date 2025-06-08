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
                'values' => [
                    'type' => Type::listOf(Type::nonNull(
                        new ObjectType([
                            'name' => 'AttributeValue',
                            'fields' => [
                                'label' => Type::string(),
                                'rendered' => Type::string(),
                            ]
                        ])
                    ))
                ]
            ]
        ]);
    }
}
