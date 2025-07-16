<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappEnvioLog extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_envios_logs';
    protected $fillable = [
        'phone',
        'status',
        'caption',
        'link',
        'media_filename',
        'message_id',
        'response',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
