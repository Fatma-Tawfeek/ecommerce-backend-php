<?php

namespace App\Models;

use App\Database\DB;
use PDO;

class Order
{
    private ?int $id;
    private float $totalPrice;
    private int $itemsNumber;
    private array $products;
    // كل عنصر: ['productId' => int, 'selectedAttributes' => [name => value]]

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->totalPrice = $data['totalPrice'] ?? 0.0;
        $this->itemsNumber = $data['itemsNumber'] ?? 0;
        $this->products = $data['products'] ?? [];
    }

    public function addProduct(int $productId, array $selectedAttributes = []): void
    {
        $this->products[] = [
            'productId' => $productId,
            'selectedAttributes' => $selectedAttributes
        ];
        $this->itemsNumber = count($this->products);
    }

    public function calculateTotalPrice(): void
    {
        $pdo = DB::connect();

        if (empty($this->products)) {
            $this->totalPrice = 0;
            return;
        }

        $productIds = array_map(fn($p) => $p['productId'], $this->products);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        $stmt = $pdo->prepare("
        SELECT product_id, amount
        FROM prices
        WHERE product_id IN ($placeholders)
    ");
        $stmt->execute($productIds);
        $prices = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [product_id => amount]

        $total = 0;
        foreach ($productIds as $id) {
            $total += $prices[$id] ?? 0;
        }

        $this->totalPrice = $total;
    }


    public function save(): void
    {
        $pdo = DB::connect();
        $pdo->beginTransaction();

        try {
            // 1. Save order
            $stmt = $pdo->prepare("INSERT INTO orders (`total-price`, `items-number`) VALUES (:price, :items)");
            $stmt->execute([
                ':price' => $this->totalPrice,
                ':items' => $this->itemsNumber
            ]);

            $this->id = $pdo->lastInsertId();

            // 2. Save products in order_product
            $stmt = $pdo->prepare("INSERT INTO order_product (order_id, product_id) VALUES (:order_id, :product_id)");
            $stmtAttr = $pdo->prepare("INSERT INTO order_product_attributes (order_id, product_id, attribute_name, selected_value) VALUES (:order_id, :product_id, :name, :value)");

            foreach ($this->products as $product) {
                $productId = $product['productId'];
                $attributes = $product['selectedAttributes'] ?? [];

                $stmt->execute([
                    ':order_id' => $this->id,
                    ':product_id' => $productId
                ]);

                // 3. Save attributes
                foreach ($attributes as $name => $value) {
                    $stmtAttr->execute([
                        ':order_id' => $this->id,
                        ':product_id' => $productId,
                        ':name' => $name,
                        ':value' => $value
                    ]);
                }
            }

            $pdo->commit();
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'totalPrice' => $this->totalPrice,
            'itemsNumber' => $this->itemsNumber,
            'products' => $this->products
        ];
    }
}
