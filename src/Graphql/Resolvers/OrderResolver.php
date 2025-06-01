<?php

namespace App\GraphQL\Resolvers;

use App\Models\Order;

class OrderResolver
{
    public static function create($root, array $args): array
    {
        // الـ args لازم تحتوي على products: [{ productId: X }]
        $orderData = $args['input'];
        $order = new Order();

        foreach ($orderData['products'] as $product) {
            $order->addProduct($product['productId'], $product['selectedAttributes'] ?? []);
        }

        $order->save(); // دي بتحسب السعر وبتحفظ في الجدولين orders و order_product

        return $order->toArray();
    }
}
