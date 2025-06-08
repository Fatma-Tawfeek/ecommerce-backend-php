<?php

namespace App\GraphQL\Types;


class TypeRegistry
{
    private static array $types = [];

    public static function product(): ProductType
    {
        return self::$types['product'] ??= new ProductType();
    }
    public static function category(): CategoryType
    {
        return self::$types['category'] ??= new CategoryType();
    }

    public static function categoryProducts(): CategoryProductsType
    {
        return self::$types['categoryProducts'] ??= new CategoryProductsType();
    }

    public static function attribute(): AttributeType
    {
        return self::$types['attribute'] ??= new AttributeType();
    }

    public static function order(): OrderType
    {
        return self::$types['order'] ??= new OrderType();
    }

    public static function orderProduct(): OrderProductType
    {
        return self::$types['orderProduct'] ??= new OrderProductType();
    }
}
