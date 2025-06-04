<?php

namespace App\Models;

use App\Database\DB;
use PDO;

class Product
{
    public static function all(): array
    {
        $pdo = DB::connect();
        $stmt = $pdo->query("
                        SELECT 
                            id, 
                            name, 
                            description, 
                            brand, 
                            `in-stock` AS inStock, 
                            category_id
                        FROM products
                    ");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => self::loadRelations($pdo, $row), $products);
    }

    public static function find(int $id): ?array
    {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            return null;
        }

        return self::loadRelations($pdo, $product);
    }

    public static function getByCategory(int $categoryId): array
    {
        $pdo = DB::connect();

        // Get category name
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->execute([$categoryId]);
        $categoryName = $stmt->fetchColumn();

        // Get products
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = array_map(fn($row) => self::loadRelations($pdo, $row), $products);

        return [
            'categoryName' => $categoryName,
            'products' => $products
        ];
    }


    private static function loadRelations(PDO $pdo, array $product): array
    {

        // تحميل التصنيف
        $stmt = $pdo->prepare("SELECT name FROM categories WHERE id = ?");
        $stmt->execute([$product['category_id']]);
        $product['category'] = $stmt->fetchColumn();

        // تحميل الصور (gallery)
        $stmt = $pdo->prepare("SELECT url FROM gallery WHERE product_id = ?");
        $stmt->execute([$product['id']]);
        $product['gallery'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // تحميل الـ attributes
        $stmt = $pdo->prepare("
        SELECT a.id, a.name, a.type
        FROM attributes a
        JOIN attribute_product ap ON ap.attribute_id = a.id
        WHERE ap.product_id = ?
    ");
        $stmt->execute([$product['id']]);
        $attributes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // تحميل الـ items لكل attribute، وتحويلها لكائن AttributeSet (Text/Swatch)
        $finalAttributes = [];
        foreach ($attributes as $attribute) {
            $stmtItems = $pdo->prepare("SELECT id, `display-value` AS displayValue, `value` FROM items WHERE attribute_id = ?");
            $stmtItems->execute([$attribute['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            $attribute['items'] = $items;

            // استخدمي الفاكتوري هنا (حتى لو جوّا Product class دلوقتي)
            $finalAttributes[] = self::makeAttributeObject($attribute);
        }

        $product['attributes'] = $finalAttributes;

        // ✅ تحميل الأسعار من جدول prices + currencies
        $stmt = $pdo->prepare("
        SELECT p.amount, c.label, c.symbol
        FROM prices p
        JOIN currencies c ON p.currency_id = c.id
        WHERE p.product_id = ?
    ");
        $stmt->execute([$product['id']]);
        $prices = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $product['prices'] = $prices;

        return $product;
    }

    private static function makeAttributeObject(array $attributeData): AttributeSet
    {
        return match ($attributeData['type']) {
            'text' => new TextAttribute($attributeData),
            'swatch' => new SwatchAttribute($attributeData),
            default => throw new \Exception("Unknown attribute type: " . $attributeData['type']),
        };
    }
}
