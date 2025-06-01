<?php

namespace App\Controller;

use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Resolvers\OrderResolver;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\OrderType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;

class GraphqLController
{
    static public function handle()
    {
        try {
            // Query Type
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'products' => [
                        'type' => Type::listOf(new ProductType()),
                        'resolve' => [ProductResolver::class, 'getAll']
                    ],
                    'product' => [
                        'type' => new ProductType(),
                        'args' => [
                            'id' => Type::nonNull(Type::int())
                        ],
                        'resolve' => [ProductResolver::class, 'getById']
                    ],
                    'categories' => [
                        'type' => Type::listOf(Type::string()),
                        'resolve' => [CategoryResolver::class, 'getAll']
                    ]
                ]
            ]);

            // Mutation Type
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'createOrder' => [
                        'type' => new OrderType(),
                        'args' => [
                            'products' => Type::nonNull(Type::listOf(
                                new \GraphQL\Type\Definition\InputObjectType([
                                    'name' => 'OrderProductInput',
                                    'fields' => [
                                        'productId' => Type::nonNull(Type::int()),
                                        'selectedAttributes' => Type::listOf(
                                            new \GraphQL\Type\Definition\InputObjectType([
                                                'name' => 'SelectedAttributeInput',
                                                'fields' => [
                                                    'name' => Type::string(),
                                                    'value' => Type::string()
                                                ]
                                            ])
                                        )
                                    ]
                                ])
                            ))
                        ],
                        'resolve' => [OrderResolver::class, 'create']
                    ]
                ]
            ]);

            // إعداد السكيمة
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            // قراءة الإنتربت
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;

            $rootValue = [];
            $result = \GraphQL\GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output);
    }
}
