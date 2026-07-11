<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "phone",
        "address",
        "is_active",
        "last_seen_at",
        "profile_photo_path",
        "bio",
        "birth_date",
        "gender",
        "username",
        "slug",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ["profile_photo_url", "presence_status"];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "is_active" => "boolean",
            "last_seen_at" => "datetime",
            "birth_date" => "date",
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function isAdmin()
    {
        return $this->hasRole("admin");
    }

    public function isOperator()
    {
        return $this->hasRole("operator");
    }

    public function isUser()
    {
        return $this->hasRole("user");
    }

    public function getCartTotalAttribute()
    {
        return $this->cartItems->sum("subtotal");
    }

    public function getFormattedCartTotalAttribute()
    {
        return "Rp " . number_format($this->cart_total, 0, ",", ".");
    }

    /**
     * Get the user's presence status.
     */
    public function getPresenceStatusAttribute()
    {
        if (!$this->last_seen_at) {
            return "Offline";
        }

        if ($this->last_seen_at->gt(now()->subMinutes(5))) {
            return "Online";
        }

        return "Terakhir online " . $this->last_seen_at->locale('id')->diffForHumans();
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        // Use local default avatars for better performance and consistency
        $avatars = [
            '/images/default-avatar.svg',        // Blue
            '/images/default-avatar-yellow.svg', // Yellow  
            '/images/default-avatar-green.svg',  // Green
        ];

        // Use user ID to consistently pick an avatar
        $avatarIndex = $this->id % count($avatars);
        
        return $avatars[$avatarIndex];
    }

    /**
     * Get the user's age based on birth date.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->slug = $user->generateSlug();
            $user->saveQuietly();
        });

        static::updated(function ($user) {
            if ($user->isDirty('name') && !$user->isDirty('slug')) {
                $user->slug = $user->generateSlug();
                $user->saveQuietly();
            }
        });
    }

    /**
     * Generate a unique slug from the user's name.
     */
    private function generateSlug(): string
    {
        $slug = Str::slug($this->name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        try {
            $this->notify(new \App\Notifications\VerifyEmailNotification());
        } catch (\Exception $e) {
            \Log::error('Failed to send email verification notification', [
                'user_id' => $this->id,
                'email' => $this->email,
                'error' => $e->getMessage()
            ]);
        }
    }
}
