<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'content',
        'image_path',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
