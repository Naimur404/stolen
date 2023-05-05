<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerDuePayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_due_payments';
    protected $fillable = [
        'outlet_id',
        'customer_id',
        'due_amount',
        'pay',
        'rest_amount',
        'received_by'
    ];
}
