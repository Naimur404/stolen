<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'categories';
    protected $fillable = [
        'category_name',
        'alert_limit',
    ];


    public static function get_category_name($id){
        return Category::where('id', $id)->value('category_name');
    }
}
