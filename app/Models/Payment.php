<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'todo_id',
        'order_id',
        'amount',
        'status',
        'snap_token',
        'transaction_id',
        'payment_type',
        'invoice_sent_at',
    ];

    protected $casts = [
        'invoice_sent_at' => 'datetime',
    ];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
