<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletExchange extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'outlet_exchanges';
    protected $fillable = [
        'outlet_id',
        'original_invoice_id',
        'customer_id',
        'grand_total',
        'paid_amount',
        'added_by',



    ];

    public function originalInvoice()
    {
        return $this->belongsTo(OutletInvoice::class, 'original_invoice_id');
    }

    public function outlet()
    {
        return $this->hasOne(Outlet::class, 'id', 'outlet_id');
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
