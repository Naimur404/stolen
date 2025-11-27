<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendMessageLog extends Model
{
    use HasFactory;
    protected $table = 'send_message_logs';
    protected $fillable = [
        'message', 'response', 'recipient_type', 'outlet_id', 'outlet_name', 'recipients_count',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
