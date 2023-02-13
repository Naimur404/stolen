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
        'medicine_id',
        'medicine_name',
        'expiry_date',
        'available_qty',
        'quantity',
        'rate',
        'discount',
        'total_price',
    ];
}
