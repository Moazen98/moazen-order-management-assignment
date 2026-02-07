<?php

namespace App\Services;


use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class ProductService.
 */
class ProductService extends MainService
{

    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {
    }

    public function list(int $perPage = 15)
    {
        return $this->productRepository->paginate($perPage);
    }

    public function show(int $id)
    {
        $product = $this->productRepository->findById($id);

        return $product;
    }

    public function create(Request $request)
    {
        $product = $this->productRepository->create(
            $request->only(['price', 'is_active'])
        );

        $product->setTranslatedAttributes($request);
        $product->save();

        return $product;
    }

    public function update(int $id, Request $request)
    {
        $product = $this->show($id);

        $this->productRepository->update(
            $product,
            $request->only(['price', 'order', 'is_active'])
        );

        $product->setTranslatedAttributes($request);
        $product->save();

        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->show($id);
        $this->productRepository->delete($product);
    }
}
