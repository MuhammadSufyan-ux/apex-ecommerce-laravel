<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'type',
        'event',
        'recipient_type',
        'recipient',
        'message',
        'status',
        'error'
    ];
}
