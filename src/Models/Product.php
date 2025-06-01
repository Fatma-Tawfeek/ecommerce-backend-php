<?php

namespace App\Models;

abstract class Product
{
    protected int $id;
    protected string $name;
    protected bool $inStock;
    protected array $gallery;
    protected string $description;
    protected string $category;
    protected array $attributes;
    protected string $price;
    protected string $brand;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->inStock = $data['inStock'];
        $this->gallery = $data['gallery'];
        $this->description = $data['description'];
        $this->category = $data['category'];
        $this->attributes = $data['attributes'];
        $this->price = $data['price'];
        $this->brand = $data['brand'];
    }

    // Common getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isInStock(): bool
    {
        return $this->inStock;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }
}
