<?php

namespace App\Http\Response;

class ByDomainResponse
{
    public static function formatSeller($seller)
    {
        return [
            'shop_name' => $seller->shop_name,
            'regencies' => $seller->regencies,
            'desc' => $seller->desc,
            'created_at' => $seller->created_at->toDateTimeString(), // Format ke string waktu
        ];
    }

    public static function formatProduct($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'image_url' => $product->image_url,
            'condition' => $product->condition,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
        ];
    }
}
