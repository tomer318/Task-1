<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'tags', 'reason', 'images', 'video_path', 'status', 'admin_note', 'processed_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'images' => 'array',
        'processed_at' => 'datetime',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}