<?php

namespace App\Models;

use App\Models\AttributeSet;

class TextAttribute extends AttributeSet
{
    public function renderValue(string $value): string
    {
        return "<span className='px-3 py-2'>{$value}</span>";
    }

    protected function getFormattedValues(): array
    {
        return array_map(function ($item) {
            return [
                'label' => $item['displayValue'] ?? $item['value'],
                'rendered' => $this->renderValue($item['value'])
            ];
        }, $this->items);
    }
}
