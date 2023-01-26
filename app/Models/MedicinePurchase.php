<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicinePurchase extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'medicine_purchases';
    protected $fillable = [
        'warehouse_id',
        'supplier_id',
        'invoice_no',
        'invoice_image',
        'sub_total',
        'vat',
        'total_discount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'purchase_date',
        'purchase_details',
        'payment_method_id',
        'added_by',
    ];
}
