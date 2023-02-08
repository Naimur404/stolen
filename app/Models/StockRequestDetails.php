<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequestDetails extends Model
{
    use HasFactory;

    protected $table = 'stock_request_details';
    protected $fillable = [
        'stock_request_id',
        'medicine_id',
        'medicine_name',
        'quantity',
        'has_accepted',
    ];
}
