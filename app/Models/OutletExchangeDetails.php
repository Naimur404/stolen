<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletExchangeDetails extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'outlet_exchange_details';
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
        'outlet_exchange_id',
        'medicine_id',
        'medicine_name',
        'size',
        'available_qty',
        'quantity',
        'rate',
        'total_price',
        'is_exchange'
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id', 'id');
    }

    public function outletEchange()
    {
        return $this->belongsTo(OutletExchange::class, 'outlet_exchange_id', 'id');
    }

    public function outletStock()
    {
        return $this->hasOne(OutletStock::class, 'id', 'stock_id');
    }
}
