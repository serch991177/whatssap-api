<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappEstados extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_estados';
    protected $fillable = [
        'message_id',
        'phone',
        'ack',
        'estado_at'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
