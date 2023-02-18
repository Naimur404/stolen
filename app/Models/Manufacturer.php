<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'manufacturers';
    protected $fillable = [
        'manufacturer_name',
        'mobile',
        'address',
        'is_active',
    ];

    public static function get_manufacturer_name($id){
        return Manufacturer::where('id', $id)->value('manufacturer_name');
 }
}
