<?php

namespace App\Models;

class Order
{
    private ?int $id;
    private float $totalPrice;
    private int $itemsNumber;
    private array $products;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->totalPrice = $data['totalPrice'] ?? 0.0;
        $this->itemsNumber = $data['itemsNumber'] ?? 0;
        $this->products = $data['products'] ?? [];
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getItemsNumber(): int
    {
        return $this->itemsNumber;
    }

    public function setItemsNumber(int $itemsNumber): void
    {
        $this->itemsNumber = $itemsNumber;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function addProduct(string $productId, array $selectedAttributes = []): void
    {
        $this->products[] = [
            'productId' => $productId,
            'selectedAttributes' => $selectedAttributes
        ];
        $this->itemsNumber = count($this->products);
    }

    public function calculateTotalPrice(array $productPrices): void
    {
        $total = 0.0;
        foreach ($this->products as $product) {
            if (isset($productPrices[$product['productId']])) {
                $total += $productPrices[$product['productId']];
            }
        }
        $this->totalPrice = $total;
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
