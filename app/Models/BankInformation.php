<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInformation extends Model
{
    use HasFactory;

    protected $table = "bank_information";

    protected $fillable = ['user_id', 'bank_code', 'bank_name', 'account_name', 'account_number', 'status'];
}
