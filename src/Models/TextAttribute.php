<?php

namespace App\Models;

use App\Models\AttributeSet;

class TextAttribute extends AttributeSet
{
    public function renderValue(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getAvailableValues(): array
    {
        return array_map(function ($item) {
            return $item['value'];
        }, $this->items);
    }

    public function getDisplayValue(string $value): string
    {
        foreach ($this->items as $item) {
            if ($item['value'] === $value) {
                return $item['displayValue'];
            }
        }
        return $value;
    }
}
