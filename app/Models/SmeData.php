<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmeData extends Model
{
    use HasFactory;

    protected $table = "sme_data";

    protected $fillable = ['name', 'amount', 'status'];
}
