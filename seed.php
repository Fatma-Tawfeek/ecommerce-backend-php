<?php

require_once __DIR__ . '/src/Database/DB.php';

use App\Database\DB;

$pdo = DB::connect();

// تحميل بيانات الـ JSON
$json = file_get_contents(__DIR__ . '/data.json');
$data = json_decode($json, true);

$categories = $data['data']['categories'];
$products = $data['data']['products'];

// 3. Delete old data (be careful in real projects!)
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE attribute_product");
$pdo->exec("TRUNCATE TABLE gallery");
$pdo->exec("TRUNCATE TABLE items");
$pdo->exec("TRUNCATE TABLE attributes");
$pdo->exec("TRUNCATE TABLE products");
$pdo->exec("TRUNCATE TABLE categories");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

// --- Step 1: إدخال الكاتيجوريز ---
$categoryIds = [];
foreach ($categories as $category) {
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (:name)");
    $stmt->execute([':name' => $category['name']]);
    $categoryIds[$category['name']] = $pdo->lastInsertId();
}

// --- Step 2: إدخال البرودكتس + الجاليري + الإتربيوتس ---
foreach ($products as $product) {
    $stmt = $pdo->prepare("
        INSERT INTO products (name, description, brand, `in-stock`, price, category_id)
        VALUES (:name, :description, :brand, :inStock, :price, :category_id)
    ");

    $stmt->execute([
        ':name' => $product['name'],
        ':description' => strip_tags($product['description']),
        ':brand' => $product['brand'],
        ':inStock' => $product['inStock'] ? 1 : 0,
        ':price' => isset($product['prices'][0]['amount']) ? $product['prices'][0]['amount'] : 0,
        ':category_id' => $categoryIds[$product['category']] ?? null
    ]);

    $productId = $pdo->lastInsertId();

    // --- الجاليري ---
    foreach ($product['gallery'] as $url) {
        $stmt = $pdo->prepare("INSERT INTO gallery (url, product_id) VALUES (:url, :product_id)");
        $stmt->execute([':url' => $url, ':product_id' => $productId]);
    }

    // --- الإتربيوتس ---
    foreach ($product['attributes'] as $attributeSet) {
        if (!isset($attributeSet['name'])) continue;

        // أدخل الاتربيوت نفسه
        $stmt = $pdo->prepare("INSERT INTO attributes (name) VALUES (:name)");
        $stmt->execute([':name' => $attributeSet['name']]);
        $attributeId = $pdo->lastInsertId();

        // اربطه بالبرودكت
        $stmt = $pdo->prepare("INSERT INTO attribute_product (product_id, attribute_id) VALUES (:product_id, :attribute_id)");
        $stmt->execute([':product_id' => $productId, ':attribute_id' => $attributeId]);

        // أدخل الآيتيمز بتاعته
        if (isset($attributeSet['items'])) {
            foreach ($attributeSet['items'] as $item) {
                $stmt = $pdo->prepare("
                    INSERT INTO items (`display-value`, `value`, attribute_id)
                    VALUES (:display_value, :value, :attribute_id)
                ");
                $stmt->execute([
                    ':display_value' => $item['displayValue'],
                    ':value' => $item['value'],
                    ':attribute_id' => $attributeId
                ]);
            }
        }
    }
}

echo "✅ Seeding done successfully!\n";
