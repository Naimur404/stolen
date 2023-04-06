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

    public function stock(){
        return $this->belongsTo(OutletStock::class, 'outlet_stock_id', 'id');
 }
 public function user(){
    return $this->belongsTo(User::class, 'added_by', 'id');
}
public function outlet(){
    return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
}
}
