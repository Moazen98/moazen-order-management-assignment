<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order\Order;
use App\Pipelines\Payment\Cancel\PaymentNotSuccessful;
use App\Pipelines\Payment\Success\PaymentExists;
use App\Pipelines\Payment\Success\PaymentNotProcessed;
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Services\Gateways\PaymentGatewayInterface;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class PaymentService extends MainService
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepository
    ) {
    }

    public function process(Order $order, PaymentGatewayInterface $gateway)
    {
        if ($order->status !== OrderStatus::CONFIRMED) {
            return [
                'status' => false,
                'message' => __('locale.must be confirmed before payment'),
                'response_code' => Response::HTTP_FORBIDDEN,
                'data' => null
            ];
        }

        return DB::transaction(function () use ($order, $gateway) {

            $result = $gateway->pay($order);

            $payment = $this->paymentRepository->create([
                'order_id' => $order->id,
                'status' => PaymentStatus::PENDING,
                'method' => class_basename($gateway),
                'transaction_id' => $result['transaction_id'],
            ]);

            return [
                'status' => true,
                'message' => __('locale.Payment process completed successfully'),
                'response_code' => Response::HTTP_OK,
                'data' => $payment
            ];

        });

    }

    public function paginate($user, int $perPage = 10)
    {
        return $this->paymentRepository
            ->paginateByUser($user->id, $perPage);
    }


    public function all($user)
    {
        return $this->paymentRepository
            ->getAllByUser($user->id);
    }

    public function byOrder(int $orderId, $user)
    {
        return $this->paymentRepository
            ->getByOrder($orderId, $user->id);
    }

    public function show(int $paymentId, $user)
    {
        $payment = $this->paymentRepository
            ->findByIdAndUser($paymentId, $user->id);

        if (!$payment) {
            throw new \DomainException(
                __('locale.Payment not found')
            );
        }

        return $payment;
    }

    public function findByOrder(int $orderId,$user) {
        return $this->paymentRepository
            ->findByOrder($orderId, $user->id);
    }

    public function success(string $transactionId, $user)
    {

        $payment = $this->paymentRepository
            ->findByTransactionIdAndUser($transactionId, $user->id);

        $pipelineResult = app(Pipeline::class)
            ->send($payment)
            ->through([
                PaymentExists::class,
                PaymentNotProcessed::class,
            ])
            ->then(function ($payment) {
                return $payment;
            });

        if (is_array($pipelineResult) && !$pipelineResult['status']) {
            return $pipelineResult;
        }

        DB::transaction(function () use ($pipelineResult) {

            $pipelineResult->update([
                'status' => PaymentStatus::SUCCESSFUL,
            ]);

            $pipelineResult->order->update([
                'status' => OrderStatus::CONFIRMED,
            ]);
        });

        return [
            'status' => true,
            'message' => __('locale.Payment completed successfully'),
            'response_code' => Response::HTTP_OK,
            'data' => $pipelineResult->fresh()
        ];
    }


    public function cancel(string $transactionId, $user)
    {
        $payment = $this->paymentRepository
            ->findByTransactionIdAndUser($transactionId, $user->id);

        $pipelineResult = app(Pipeline::class)
            ->send($payment)
            ->through([
                \App\Pipelines\Payment\Cancel\PaymentExists::class,
                PaymentNotSuccessful::class,
            ])
            ->then(function ($payment) {
                return $payment;
            });

        if (is_array($pipelineResult)) {
            return $pipelineResult;
        }

        DB::transaction(function () use ($pipelineResult) {

            $pipelineResult->update([
                'status' => PaymentStatus::FAILED,
            ]);

            $pipelineResult->order->update([
                'status' => OrderStatus::CANCELLED,
            ]);
        });

        return [
            'status' => true,
            'message' => __('locale.Payment cancelled successfully'),
            'response_code' => Response::HTTP_OK,
            'data' => $pipelineResult->fresh()
        ];
    }


}

