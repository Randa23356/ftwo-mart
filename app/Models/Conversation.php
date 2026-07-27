<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id", 
        "seller_id",
        "subject", 
        "status", 
        "visibility",
        "last_activity_at",
        "has_unread_admin",
        "has_unread_operator", 
        "has_unread_user",
        "has_unread_seller",
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'has_unread_admin' => 'boolean',
        'has_unread_operator' => 'boolean',
        'has_unread_user' => 'boolean',
        'has_unread_seller' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Check if this is a guest conversation
     */
    public function isGuest()
    {
        return $this->user_id === null;
    }

    /**
     * Get display name for conversation
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isGuest()) {
            return 'Guest User';
        }
        
        return $this->user->name ?? 'Unknown User';
    }

    /**
     * Mark as read for specific role
     */
    public function markAsRead($role)
    {
        $field = "has_unread_{$role}";
        if (in_array($field, $this->getFillable())) {
            $this->update([$field => false]);
        }
    }

    /**
     * Mark as unread for other roles when new message
     */
    public function markAsUnreadForOthers($senderRole)
    {
        $updates = [];
        
        if ($senderRole !== 'admin') {
            $updates['has_unread_admin'] = true;
        }
        if ($senderRole !== 'operator') {
            $updates['has_unread_operator'] = true;
        }
        if ($senderRole !== 'user') {
            $updates['has_unread_user'] = true;
        }
        if ($senderRole !== 'seller') {
            $updates['has_unread_seller'] = true;
        }
        
        $updates['last_activity_at'] = now();
        
        $this->update($updates);
    }

    /**
     * Get conversation type based on participants
     */
    public function getTypeAttribute()
    {
        if ($this->isGuest()) {
            return 'guest';
        }
        
        if ($this->visibility === 'seller_buyer') {
            return 'seller_buyer';
        }
        
        if ($this->visibility === 'internal') {
            return 'internal'; // Admin ↔ Operator
        }
        
        if ($this->visibility === 'admin_only') {
            return 'admin_user'; // Admin ↔ User
        }
        
        return 'operator_user'; // Operator ↔ User
    }

    /**
     * Get unread count for role
     */
    public static function getUnreadCount($role)
    {
        $field = "has_unread_{$role}";
        return static::where($field, true)->count();
    }
}
