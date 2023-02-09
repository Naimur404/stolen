<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stock_requests';
    protected $fillable = [
        'date',
        'outlet_id',
        'warehouse_id',
        'added_by',
        'has_sent',
        'has_accepted',
        'remarks',
        'accepted_by',
        'accepted_on',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
