<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ["conversation_id", "user_id", "body", "read_at"];

    protected $casts = [
        "read_at" => "datetime",
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is a guest message
     */
    public function isGuest()
    {
        return $this->user_id === null;
    }

    /**
     * Get sender name for display
     */
    public function getSenderNameAttribute()
    {
        if ($this->isGuest()) {
            return 'Guest';
        }
        
        return $this->user->name ?? 'Unknown User';
    }
}
