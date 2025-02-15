<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'outlets';
    protected $fillable = [
        'outlet_name',
        'address',
        'mobile',
        'details',
        'is_active_courier_gateway',
        'is_active',
    ];

    public static function userHasoutlet($role, $permissions, $id)
    {
        $hasPermisison = true;

        foreach ($role as $roles) {
            if (($roles->outlet_id == $id && $permissions->id == $roles->user_id)) {

                $hasPermisison = true;
                return $hasPermisison;
            }
        }

    }

    public static function getOutletName($id)
    {
        if ($id != null) {
            return Outlet::find($id)->outlet_name;
        }
        else
            return null;
    }


}


