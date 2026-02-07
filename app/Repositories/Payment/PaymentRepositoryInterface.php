<?php

namespace App\Repositories\Payment;

interface PaymentRepositoryInterface
{
    public function create(array $data);
    public function getByOrder(int $orderId, int $userId);
    public function paginateByUser(int $userId, int $perPage = 10);
    public function findByIdAndUser(int $paymentId, int $userId);
    public function findByOrder( int $orderId,int $userId);
    public function findByTransactionIdAndUser(string $transactionId, int $userId);


}
