<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappEnvio extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_envios';
    protected $fillable = [
        'phone',
        'caption',
        'link',
        'media_filename',
        'status',
        'response'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
