<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = ['user_id', 'transaction_ref', 'amount', 'app_fee', 'amount_settled', 'currency', 'transaction_type', 'status', 'payment_type'];
}
