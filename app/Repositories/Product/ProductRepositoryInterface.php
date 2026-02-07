<?php

namespace App\Repositories\Product;

use App\Models\Product\Product;

interface ProductRepositoryInterface
{
    public function paginate(int $perPage = 15);

    public function findById(int $id);

    public function create(array $data);

    public function update(Product $product, array $data);

    public function delete(Product $product);
}
