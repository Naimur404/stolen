<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $table = 'sales_returns';
    protected $fillable = [
        'return_date',
        'outlet_id',
        'customer_id',
        'invoice_id',
        'sub_total',
        'deduct_amount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'deduct_point',
        'payment_method_id',
        'added_by',
    ];
}
