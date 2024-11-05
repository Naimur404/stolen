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
    protected $guarded = ['id'];
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $fillable = [
        'outlet_id',
        'customer_id',
        'sale_date',
        'sub_total',
        'vat',
        'delivery_charge',
        'total_with_charge',
        'total_discount',
        'grand_total',
        'payable_amount',
        'given_amount',
        'paid_amount',
        'due_amount',
        'earn_point',
        'redeem_point',
        'payment_method_id',
        'added_by',
        'is_exchange'
    ];

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'id', 'outlet_id');
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

    public function invoiceDetails()
    {
        return $this->hasMany(OutletInvoiceDetails::class);
    }


}
