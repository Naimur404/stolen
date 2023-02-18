<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'medicines';
    protected $fillable = [
        'manufacturer_id',
        'category_id',
        'unit_id',
        'type_id',
        'medicine_name',
        'generic_name',
        'strength',
        'box_size',
        'price',
        'tax',
        'manufacturer_price',
        'medicine_details',
        'image',
    ];
    public static function get_medicine_name($id){
        return Medicine::where('id', $id)->value('medicine_name');
 }
 public static function get_manufacturer_price($id){
    return Medicine::where('id', $id)->value('manufacturer_price');
}
public function medicine_stock(){
    return $this->belongsTo(OutletStock::class, 'id', 'medicine_id');
}
}
