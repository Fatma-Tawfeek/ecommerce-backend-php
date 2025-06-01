<?php

namespace App\Models;

use App\Models\AttributeSet;

class SwatchAttribute extends AttributeSet
{
    public function renderValue(string $value): string
    {
        // For swatch, return HTML with color style
        $colorCode = $this->getColorCode($value);
        return "<span class='color-swatch' style='background-color: {$colorCode};'></span>";
    }

    public function getColorCode(string $value): string
    {
        foreach ($this->items as $item) {
            if ($item['id'] === $value || $item['displayValue'] === $value) {
                return $item['value']; // This contains the hex color code
            }
        }
        return '#000000'; // Default black
    }

    public function getAvailableColors(): array
    {
        return array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['displayValue'],
                'color' => $item['value']
            ];
        }, $this->items);
    }
}
