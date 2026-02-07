<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\MainApiController;
use App\Http\Resources\Api\Payment\PaymentCollection;
use App\Http\Resources\Api\Payment\PaymentResource;
use App\Http\Responses\V1\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends MainApiController
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

    public function index()
    {
        $paymentsResponse = app('servicesV1')->paymentService->paginate($this->user,$this->perPage);
        $payments = (new PaymentCollection($paymentsResponse->items()))->toArray();

        return CustomResponse::Success(
            Response::HTTP_OK,
            __('locale.Data uploaded successfully'),
            [
                'data' => $payments,
                'current_page' => $paymentsResponse->currentPage(),
                'last_page' => $paymentsResponse->lastPage(),
                'total' => $paymentsResponse->total(),
                'per_page' => $paymentsResponse->perPage(),
            ],
            []
        );
    }

    public function show(Request $request)
    {
        $payment = app('servicesV1')->paymentService->show($request->id, $this->user);

        if (!$payment){
            return CustomResponse::Failure(Response::HTTP_NOT_FOUND, __('locale.Data not founded'), $data = [], []);
        }

        return CustomResponse::Success( Response::HTTP_OK,  __('locale.Data uploaded successfully'), ['payment' => $payment], []);

    }

    public function success(Request $request)
    {
        $order = app('servicesV1')->paymentService->success($request->transaction_id, $this->user);

        if (!$order['status']) {
            return CustomResponse::Failure($order['response_code'],$order['message'],$order['data'],[] );
        }

        return CustomResponse::Success($order['response_code'],$order['message'],$order['data'],[] );
    }

    public function cancel(Request $request)
    {
        $order = app('servicesV1')->paymentService->cancel($request->transaction_id, $this->user);

        if (!$order['status']) {
            return CustomResponse::Failure($order['response_code'],$order['message'],$order['data'],[] );
        }

        return CustomResponse::Success($order['response_code'],$order['message'],$order['data'],[] );
    }



    public function byOrderID(Request $request)
    {
        $paymentsResponse = app('servicesV1')->paymentService->findByOrder($request->id, $this->user,$this->perPage);

        if (!$paymentsResponse){
            return CustomResponse::Failure(Response::HTTP_NOT_FOUND, __('locale.Data not founded'), $data = [], []);
        }
        $payment = (new PaymentResource($paymentsResponse))->toArray();

        return CustomResponse::Success( Response::HTTP_OK,  __('locale.Data uploaded successfully'), ['payment' => $payment], []);
    }


}
