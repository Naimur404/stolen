<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicineDistribute extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'medicine_distributes';
    protected $fillable = [
        'date',
        'warehouse_id',
        'outlet_id',
        'added_by',
        'remarks',
    ];
    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
 }
 public function warehouse(){
    return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
}
}
