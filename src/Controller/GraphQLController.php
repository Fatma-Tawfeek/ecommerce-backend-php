<?php

namespace App\Controller;

use App\GraphQL\Resolvers\CategoryResolver;
use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Resolvers\OrderResolver;
use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\OrderType;
use GraphQL\Error\DebugFlag;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;

class GraphQLController
{
    static public function handle()
    {

        try {
            // Query Type
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'products' => [
                        'type' => Type::listOf(\App\GraphQL\Types\TypeRegistry::product()),
                        'resolve' => [ProductResolver::class, 'getAll']
                    ],
                    'product' => [
                        'type' =>  \App\GraphQL\Types\TypeRegistry::product(),
                        'args' => [
                            'id' => Type::nonNull(Type::int())
                        ],
                        'resolve' => [ProductResolver::class, 'getById']
                    ],
                    'productsByCategory' => [
                        'type' => Type::listOf(\App\GraphQL\Types\TypeRegistry::product()),
                        'args' => [
                            'categoryId' => Type::nonNull(Type::int())
                        ],
                        'resolve' => [ProductResolver::class, 'getByCategory']
                    ],
                    'categories' => [
                        'type' => Type::listOf(\App\GraphQL\Types\TypeRegistry::category()),
                        'resolve' => [CategoryResolver::class, 'getAll']
                    ]
                ]
            ]);

            // Mutation Type
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'createOrder' => [
                        'type' =>  \App\GraphQL\Types\TypeRegistry::order(),
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
            // $output = $result->toArray();
            $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
            file_put_contents(__DIR__ . '/../../error_log.txt', $e->getMessage());
        }
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output);
    }
}
