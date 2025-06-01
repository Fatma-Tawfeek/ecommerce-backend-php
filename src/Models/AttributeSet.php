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

    abstract public function renderValue(string $value): string;

    public function toFrontendFormat(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'values' => $this->getFormattedValues()
        ];
    }

    // هيتعمل لها override في الـ subclasses
    abstract protected function getFormattedValues(): array;
}
