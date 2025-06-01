<?php

namespace App\Models;

use App\Models\AttributeSet;

class SwatchAttribute extends AttributeSet
{
    public function renderValue(string $value): string
    {
        $colorCode = $this->getColorCode($value);
        return "<span class='color-swatch' style='background-color: {$colorCode};'></span>";
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

    private function getColorCode(string $value): string
    {
        foreach ($this->items as $item) {
            if ($item['id'] === $value || $item['displayValue'] === $value) {
                return $item['value'];
            }
        }
        return '#000000';
    }
}
