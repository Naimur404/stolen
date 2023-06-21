<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineDistributeDetail extends Model
{
    use HasFactory;
    protected $table = 'medicine_distribute_details';
    protected $fillable = [
        'medicine_distribute_id',
        'medicine_id',
        'warehouse_stock_id',
        'barcode_text',
        'medicine_name',
        'rack_no',
        'size',
        'create_date',
        'quantity',
        'rate',
        'has_sent',
        'has_received',
    ];
}
