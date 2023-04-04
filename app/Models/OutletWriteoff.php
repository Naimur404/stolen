<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletWriteoff extends Model
{
    use HasFactory;

    protected $table = 'outlet_writeoffs';
    protected $fillable = [
        'outlet_id',
        'outlet_stock_id',
        'medicine_id',
        'medicine_name',
        'previous_stock',
        'quantity',
        'reason',
        'remarks',
        'added_by',
    ];
}
