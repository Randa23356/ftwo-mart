<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingReply extends Model
{
    protected $fillable = [
        'rating_id',
        'user_id',
        'reply_text',
    ];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
