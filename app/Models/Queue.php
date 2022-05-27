<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $table = "queues";

    protected $fillable = ['user_id', 'ref', 'payload', 'type', 'amount', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
