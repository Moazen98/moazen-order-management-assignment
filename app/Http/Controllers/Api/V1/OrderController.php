<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\MainApiController;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Requests\Api\Order\UpdateOrderItemsRequest;
use App\Http\Resources\Api\Order\OrderCollection;
use App\Http\Resources\Api\Order\OrderResource;
use App\Http\Responses\V1\CustomResponse;
use App\Services\Gateways\PaymentGatewayFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends MainApiController
{

    protected $perPage;
    protected $page;
    protected $user;


    public function __construct(Request $request)
    {
        $user = getUserLoginDetails();

        $this->perPage = $this->apiPaginate ?? 15;
        $this->page = $request->page ?? 1;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $ordersResponse = app('servicesV1')->orderService
            ->paginateForCurrentUser($this->user,$request->query('status'));

        $orders = (new OrderCollection($ordersResponse->items()))->toArray();

        return CustomResponse::Success(
            Response::HTTP_OK,
            __('locale.Data uploaded successfully'),
            [
                'data' => $orders['orders'],
                'current_page' => $ordersResponse->currentPage(),
                'last_page' => $ordersResponse->lastPage(),
                'total' => $ordersResponse->total(),
                'per_page' => $ordersResponse->perPage(),
            ],
            []
        );
    }

    public function store(StoreOrderRequest $request)
    {
        $order = app('servicesV1')->orderService->create($request->validated(),$this->user);

        if (!$order){
            return CustomResponse::Failure(Response::HTTP_NOT_FOUND, __('locale.Data not founded'), $data = [], []);
        }


        $order = (new OrderResource($order))->toArray();

        return CustomResponse::Success(Response::HTTP_CREATED, __('locale.Order created successfully'),$order, []);
    }

    public function pay(Request $request)
    {
        $gateway = PaymentGatewayFactory::make(
            $request->payment_method
        );

        $payment = app('servicesV1')->orderService
            ->pay($request->order_id, $this->user, $gateway);

        if (!$payment['status']) {
            return CustomResponse::Failure(
                $payment['response_code'],
                $payment['message'],
                [],
                []
            );
        }

        return CustomResponse::Success(
            $payment['response_code'],
            $payment['message'],
            ['payment' => $payment['data']],
            []
        );
    }

    public function confirm(Request $request)
    {
        $orderResponse = app('servicesV1')->orderService->confirm($request->id, $this->user);

        if (!$orderResponse['status']){
            return CustomResponse::Failure($orderResponse['response_code'],$orderResponse['message'], $data = [], []);
        }

        $order = (new OrderResource($orderResponse['data']))->toArray();

        return CustomResponse::Success($orderResponse['response_code'],$orderResponse['message'],['order' => $order],[]);
    }



    public function show(Request $request)
    {
        $order = app('servicesV1')->orderService->show((int) $request->id, $this->user);

        if (!$order) {
            return CustomResponse::Failure(  Response::HTTP_NOT_FOUND,__('locale.Data not founded'),[],[] );
        }

        $order = (new OrderResource($order))->toArray();

        return CustomResponse::Success(Response::HTTP_OK,  __('locale.Data uploaded successfully'),['order' => $order],[] );
    }


    public function delete(Request $request)
    {
        $order = app('servicesV1')->orderService->delete((int) $request->id, $this->user);

        if (!$order['status']) {
            return CustomResponse::Failure($order['response_code'],$order['message'],$order['data'],[] );
        }

        return CustomResponse::Success($order['response_code'],$order['message'],$order['data'],[] );
    }

    public function updateItems(UpdateOrderItemsRequest $request)
    {
        $result = app('servicesV1')->orderService
            ->updateItems(
                $request->order_id,
                $this->user,
                $request->validated()
            );

        if (!$result['status']) {
            return CustomResponse::Failure(
                $result['response_code'],
                $result['message'],
                $result['data'],
                []
            );
        }

        return CustomResponse::Success(
            $result['response_code'],
            $result['message'],
            $result['data'],
            []
        );
    }
}
