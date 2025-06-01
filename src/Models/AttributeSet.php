<?php

namespace App\Models;

abstract class AttributeSet
{
    protected int $id;
    protected string $name;
    protected string $type;
    protected array $items;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->items = $data['items'] ?? [];
    }

    // Abstract methods
    abstract public function renderValue(string $value): string;

    // Common getters
    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
