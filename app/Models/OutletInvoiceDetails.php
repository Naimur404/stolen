<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletInvoiceDetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'outlet_invoice_details';
    protected $fillable = [
        'outlet_invoice_id',
        'stock_id',
        'medicine_id',
        'medicine_name',
        'expiry_date',
        'available_qty',
        'quantity',
        'rate',
        'discount',
        'total_price',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(OutletInvoice::class, 'outlet_invoice_id', 'id');
    }

    public function outletStock()
    {
        return $this->hasOne(OutletStock::class, 'id', 'stock_id');
    }
}
