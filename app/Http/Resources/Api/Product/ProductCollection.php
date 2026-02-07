<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request=null)
    {
        return [
            'products' => $this->collection->map(function ($product) use ($request) {
                return (new ProductResource($product))->toArray($request);
            })
        ];
    }

}
