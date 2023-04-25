<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';

    protected $fillable = [
          'logo',
          'favicon',
          'app_name',
          'address',
          'phone_no',
          'description',
          'website',
          'print',
          'footer_text'


    ];
}
