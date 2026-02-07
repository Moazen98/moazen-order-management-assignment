<?php

namespace App\Repositories\Order;

use App\Enums\OrderStatus;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;

class OrderRepository implements OrderRepositoryInterface
{
    public function create($data)
    {
        return Order::create($data);
    }

    public function addItem(Order $order,$item)
    {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['product_id'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
        ]);
    }

    public function updateTotal(Order $order,$total)
    {
        $order->update(['total_amount' => $total]);
    }

    public function hasPayments(Order $order)
    {
        return $order->payments()->exists();
    }

    public function paginateByUser($userId, $perPage = 10,$status = null)
    {
        return Order::with('items')
            ->where('user_id', $userId)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findByIdAndUser($id,$userId)
    {
        return Order::with('items')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }


    public function delete(Order $order)
    {
        $order->status = OrderStatus::CANCELLED;
        $order->save();

        return $order->delete();
    }

    public function deleteItems(Order $order)
    {
        $order->items()->delete();
    }

}
