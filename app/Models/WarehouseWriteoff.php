<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseWriteoff extends Model
{
    use HasFactory;

    protected $table = 'warehouse_writeoffs';
    protected $fillable = [
        'warehouse_id',
        'warehouse_stock_id',
        'medicine_id',
        'medicine_name',
        'quantity',
        'reason',
        'remarks',
        'added_by',
    ];
}
