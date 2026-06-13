<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Todo;

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
        'payment_type'
    ];
    

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
