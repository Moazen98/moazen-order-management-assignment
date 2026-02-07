<?php

namespace App\Models\Payment;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['order_id','status','method','transaction_id'];

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

}
