<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['todo_id', 'order_id', 'amount', 'status', 'snap_token', 'transaction_id', 'payment_type'])]
#[Hidden([transaction_id])]
class Payments extends Model
{
    //
    public function createPayment($data)
    {
        // Simulate payment processing logic
        $payment = new self();
        $payment->amount = $data['amount'];
        $payment->status = 'pending';
        $payment->save();

        // Simulate payment gateway response
        $payment->status = 'completed';
        $payment->save();

        return $payment;
    }

    public function todo()
    {
        return $this->hasOne(Todo::class, 'id', 'todo_id');
    }
}
