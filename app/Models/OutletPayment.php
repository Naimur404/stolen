<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletPayment extends Model
{
    use HasFactory;
    protected $table = 'outlet_payments';
    protected $fillable = [
        'outlet_id',
        'customer_id',
        'invoice_id',
        'payment_method_id',
        'amount',
        'pay',
        'due',
        'collected_by',
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
