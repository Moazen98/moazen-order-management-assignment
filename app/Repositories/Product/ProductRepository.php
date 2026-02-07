<?php

namespace App\Repositories\Product;

use App\Models\Product\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(int $perPage = 15)
    {
        return Product::query()->latest()->paginate($perPage);
    }

    public function findById(int $id)
    {
        return Product::query()->find($id);
    }

    public function create(array $data)
    {
        return Product::query()->create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->query()->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        $product->query()->delete();
    }
}
