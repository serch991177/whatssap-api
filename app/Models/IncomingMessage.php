<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingMessage extends Model
{
    use HasFactory;
    protected $table = 'incoming_messages';
    protected $fillable = [
        'from',
        'body',
        'received_at'
        
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
