<?php

namespace App\Repositories\Order;

use App\Models\Order\Order;

interface OrderRepositoryInterface
{
    public function create($data);

    public function addItem(Order $order,$item);

    public function updateTotal(Order $order,$total);

    public function hasPayments(Order $order);

    public function delete(Order $order);

    public function paginateByUser($userId,$perPage = 10,$status = null);

    public function deleteItems(Order $order);

}
