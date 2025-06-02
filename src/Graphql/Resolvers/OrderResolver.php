<?php

namespace App\GraphQL\Resolvers;

use App\Models\Order;

class OrderResolver
{
    public static function create($root, array $args): array
    {
        $order = new \App\Models\Order();

        foreach ($args['products'] as $productData) {
            $productId = $productData['productId'];
            $attributes = [];

            foreach ($productData['selectedAttributes'] ?? [] as $attr) {
                $attributes[$attr['name']] = $attr['value'];
            }

            $order->addProduct($productId, $attributes);
        }

        $order->calculateTotalPrice();
        $order->save();

        return $order->toArray();
    }
}
