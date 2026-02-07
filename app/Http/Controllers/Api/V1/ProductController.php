<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\MainApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Product\ProductCollection;
use App\Http\Resources\Api\Product\ProductResource;
use App\Http\Responses\V1\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends MainApiController
{

    protected $perPage;
    protected $page;

    public function __construct(Request $request)
    {
        $this->perPage = $this->apiPaginate ?? 15;
        $this->page = $request->page ?? 1;
    }



    public function index(Request $request)
    {
        $productsResponse = app('servicesV1')->productService->list($this->perPage);
        $products = (new ProductCollection($productsResponse->items()))->toArray($request);

        return CustomResponse::Success(
            Response::HTTP_OK,
            __('locale.Data uploaded successfully'),
            [
                'data' => $products['products'],
                'current_page' => $productsResponse->currentPage(),
                'last_page' => $productsResponse->lastPage(),
                'total' => $productsResponse->total(),
                'per_page' => $productsResponse->perPage(),
            ],
            []
        );
    }

    /**
     * GET /api/v1/products/{id}
     */
    public function show(Request $request)
    {
        $productResponse = app('servicesV1')->productService->show($request->id);

        if (!$productResponse){
            return CustomResponse::Failure(Response::HTTP_NOT_FOUND, __('locale.Data not founded'), $data = [], []);
        }

        $product = (new ProductResource($productResponse))->toArray($request);

        return CustomResponse::Success(Response::HTTP_OK, __('locale.Data uploaded successfully'), $product, []);
    }
}
