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
        'previous_stock',
        'quantity',
        'reason',
        'remarks',
        'added_by',
    ];
    public function stock(){
        return $this->belongsTo(WarehouseStock::class, 'warehouse_stock_id', 'id');
 }
 public function user(){
    return $this->belongsTo(User::class, 'added_by', 'id');
}
public function warehouse(){
    return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
}
}
