<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'event',
        'description',
        'ip_address',
        'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($type, $event, $description)
    {
        return self::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'event' => $event,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
