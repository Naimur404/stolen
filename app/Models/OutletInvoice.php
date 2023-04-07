<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'outlet_invoices';
    protected $fillable = [
        'outlet_id',
        'customer_id',
        'sale_date',
        'sub_total',
        'vat',
        'total_discount',
        'grand_total',
        'given_amount',
        'paid_amount',
        'due_amount',
        'earn_point',
        'redeem_point',
        'payment_method_id',
        'added_by',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function payment()
    {
        return $this->hasOne(PaymentMethod::class, 'id', 'payment_method_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'added_by');
    }

}
