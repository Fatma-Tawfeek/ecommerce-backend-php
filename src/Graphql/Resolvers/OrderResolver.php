<?php

namespace App\GraphQL\Resolvers;

use App\Models\Order;

class OrderResolver
{
    public static function create($root, array $args): array
    {
        $order = new Order();

        foreach ($args['products'] as $productData) {
            $productId = $productData['productId'];
            $quantity = $productData['quantity'] ?? 1;
            $attributes = [];

            foreach ($productData['selectedAttributes'] ?? [] as $attr) {
                $attributes[$attr['name']] = $attr['value'];
            }

            $order->addProduct($productId, $quantity, $attributes);
        }

        $order->calculateTotalPrice();
        $order->save();

        return $order->toArray();
    }
}
