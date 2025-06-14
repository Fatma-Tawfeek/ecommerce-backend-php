<?php

namespace App\Models;

use App\Models\AttributeSet;

class SwatchAttribute extends AttributeSet
{
    private function getColorCode(string $value): string
    {
        foreach ($this->items as $item) {
            if ((string)$item['value'] === (string)$value) {
                return $item['value'];
            }
        }
        return '#000000';
    }

    public function renderValue(string $value): string
    {
        $colorCode = $this->getColorCode($value);
        return "<span style='background-color: {$colorCode}; width: 100%; height: 100%;display: inline-block'></span>";
    }

    protected function getFormattedValues(): array
    {
        return array_map(function ($item) {
            return [
                'label' => $item['displayValue'],
                'rendered' => $this->renderValue($item['value'])
            ];
        }, $this->items);
    }
}
