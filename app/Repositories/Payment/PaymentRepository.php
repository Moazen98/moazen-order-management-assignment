<?php

namespace App\Repositories\Payment;

use App\Models\Payment\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $data)
    {
        return Payment::query()->create($data);
    }

    public function paginateByUser(int $userId, int $perPage = 10)
    {
        return Payment::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->latest()
            ->paginate($perPage);
    }


    public function getByOrder(int $orderId, int $userId)
    {
        return Payment::where('order_id', $orderId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->latest()->get();
    }

    public function findByIdAndUser(int $paymentId, int $userId)
    {
        return Payment::where('id', $paymentId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();
    }

    public function findByOrder(int $orderId,int $userId,int $perPage = 10) {

        return Payment::where('order_id', $orderId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();
    }

    public function findByTransactionIdAndUser(string $transactionId, int $userId)
    {
        return Payment::where('transaction_id', $transactionId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->first();
    }

}
