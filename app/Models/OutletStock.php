<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletStock extends Model
{
    use HasFactory;
    protected $table = 'outlet_stocks';
    protected $fillable = [
        'outlet_id',
        'medicine_id',
        'expiry_date',
        'quantity',
        'price',
    ];
    public function medicine(){
        return $this->belongsTo(Medicine::class,  'medicine_id','id');
    }
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'id');
    }
}
