<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedeemPointLog extends Model
{
    use HasFactory;

    protected $table = 'redeem_point_logs';
    protected $fillable = [
        'outlet_id',
        'customer_id',
        'invoice_id',
        'previous_point',
        'redeem_point',
    ];
}
