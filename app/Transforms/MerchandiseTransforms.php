<?php

namespace App\Transforms;

use App\Models\Merchandise\MerchandiseSize;
use App\Models\Merchandise\MerchandiseType;
use App\Models\Merchandise\Variant;

class MerchandiseTransforms
{
    public static function getMerchandiseTypes(string $filter): array
    {
        $data = [];
        foreach (MerchandiseType::where('name', 'like', "%{$filter}%")->get() as $merchType) {
            $data['results'][] = ['id' => $merchType->id, 'text' => $merchType->name];
        }
        return $data;
    }
    
    public static function getSelectedMerchandiseType(int $type): array|null
    {
        $type = MerchandiseType::find($type);
        if ($type == null) return null;
        return ['id' => $type->id, 'text' => $type->name];
    }

    public static function getVariants(string $filter): array
    {
        $data = [];
        foreach (Variant::where('name', 'like', "%{$filter}%")->get() as $merchType) {
            $data['results'][] = ['id' => $merchType->id, 'text' => $merchType->name];
        }
        return $data;
    }
    
    public static function getSelectedVariant(int $type): array|null
    {
        $type = Variant::find($type);
        if ($type == null) return null;
        return ['id' => $type->id, 'text' => $type->name];
    }

    public static function getSizes(string $filter): array
    {
        $data = [];
        foreach (MerchandiseSize::where('name', 'like', "%{$filter}%")->get() as $merchType) {
            $data['results'][] = ['id' => $merchType->id, 'text' => $merchType->name];
        }
        return $data;
    }

    public static function getSelectedSize(int $size): array|null
    {
        $type = MerchandiseSize::find($size);
        if ($type == null) return null;
        return ['id' => $type->id, 'text' => $type->name];
    }
}
