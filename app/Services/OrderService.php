<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Pipelines\Order\Payment\OrderMustBeConfirmed;
use App\Pipelines\Order\Payment\OrderNotCancelled;
use App\Pipelines\Order\Payment\OrderNotPaid;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Gateways\PaymentGatewayInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Pipeline\Pipeline;

/**
 * Class OrderService.
 */
class OrderService extends MainService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected ProductRepositoryInterface $productRepository,
        protected PaymentRepositoryInterface $paymentRepository,
        protected PaymentService $paymentService
    ) {
    }

    public function paginateForCurrentUser($user,$status = null)
    {
        return $this->orderRepository
            ->paginateByUser($user->id, $this->paginate, $status);
    }


    public function create(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            $order = $this->orderRepository->create([
                'user_id' => $user->id,
                'status' => OrderStatus::PENDING,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {

                $product = $this->productRepository
                    ->findById($item['product_id']);

                if (!$product) {
                    return false;
                }

                $price = $product->price;
                $quantity = $item['quantity'];

                $total += $price * $quantity;

                $this->orderRepository->addItem($order, [
                    'product_id' => $product->id,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
            }

            $this->orderRepository->updateTotal($order, $total);

            return $order;
        });
    }


    public function delete($orderId,$user)
    {

       $order = $this->orderRepository->findByIdAndUser($orderId, $user->id);


        if ($this->orderRepository->hasPayments($order)) {
            return ['status' => false, 'message' => __('locale.Order has payments and cannot be deleted'), 'response_code' => Response::HTTP_FORBIDDEN, 'data' => null];
        }

        $response = $this->orderRepository->delete($order);

        if (!$response) {
            return ['status' => false, 'message' => __('locale.Data deleted failed'), 'response_code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'data' => null];
        }

        return ['status' => true, 'message' => __('locale.Data was successfully deleted'), 'response_code' => Response::HTTP_OK, 'data' => null];
    }

    public function show(int $orderId, $user)
    {
        return $this->orderRepository
            ->findByIdAndUser($orderId, $user->id);
    }

    public function confirm($id, $user)
    {
        $order = $this->orderRepository
            ->findByIdAndUser($id, $user->id);

        if (!$order || $order->user_id !== $user->id) {
            return ['status' => false, 'message' => __('locale.Unauthorized'), 'response_code' => Response::HTTP_FORBIDDEN, 'data' => null];
        }

        if ($order->status == OrderStatus::CONFIRMED) {
            return ['status' => false, 'message' => __('locale.Order already been confirmed'), 'response_code' => Response::HTTP_METHOD_NOT_ALLOWED, 'data' => null];
        }

        if ($order->status !== OrderStatus::PENDING) {
            return ['status' => false, 'message' => __('locale.Order cannot be confirmed'), 'response_code' => Response::HTTP_METHOD_NOT_ALLOWED, 'data' => null];
        }

        $order->update([
            'status' => OrderStatus::CONFIRMED,
        ]);

        return ['status' => true, 'message' => __('locale.Order confirmed successfully'), 'response_code' => Response::HTTP_OK, 'data' => $order];
    }

    public function pay( int $orderId, $user, PaymentGatewayInterface $gateway)
    {
        $order = $this->orderRepository
            ->findByIdAndUser($orderId, $user->id);

        if (!$order) {
            return ['status' => false, 'message' => __('locale.Order not found'), 'response_code' => Response::HTTP_NOT_FOUND, 'data' => null];
        }

        $result = app(Pipeline::class)
            ->send($order)
            ->through([
                OrderNotPaid::class,
                OrderNotCancelled::class,
                OrderMustBeConfirmed::class,
            ])
            ->then(function ($order) {
                return true;
            });

        if (is_array($result) && !$result['status']) {
            return $result;
        }

        return $this->paymentService->process($order, $gateway);
    }


    public function updateItems(int $orderId, $user, array $data)
    {
        $order = $this->orderRepository
            ->findByIdAndUser($orderId, $user->id);

        if (!$order) {
            return [
                'status' => false,
                'message' => __('locale.Order not found'),
                'response_code' => Response::HTTP_NOT_FOUND,
                'data' => null
            ];
        }

        if ($order->status !== OrderStatus::PENDING) {
            return [
                'status' => false,
                'message' => __('locale.Order cannot be modified'),
                'response_code' => Response::HTTP_FORBIDDEN,
                'data' => null
            ];
        }

        return DB::transaction(function () use ($order, $data) {

            $this->orderRepository->deleteItems($order);

            $total = 0;

            foreach ($data['items'] as $item) {

                $product = $this->productRepository
                    ->findById($item['product_id']);

                if (!$product) {
                    return [
                        'status' => false,
                        'message' => __('locale.Product not found'),
                        'response_code' => Response::HTTP_NOT_FOUND,
                        'data' => null
                    ];
                }

                $price = $product->price;
                $quantity = $item['quantity'];

                $total += $price * $quantity;

                $this->orderRepository->addItem($order, [
                    'product_id' => $product->id,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
            }

            $this->orderRepository->updateTotal($order, $total);

            return [
                'status' => true,
                'message' => __('locale.Order items updated successfully'),
                'response_code' => Response::HTTP_OK,
                'data' => $order->fresh('items')
            ];
        });
    }
}
