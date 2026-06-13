<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Todo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_completed'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasOne(Payments::class, 'todo_id', 'id');
    }
}
