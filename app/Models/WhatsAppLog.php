<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppLog extends Model
{
    use HasFactory;
    protected $table = 'whats_app_logs';
    protected $fillable = [
        'phone',
        'message',
        'type',
        'response',
        
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
