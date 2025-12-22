<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    protected $connection = 'mysql';
    public $table = 'users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uid)) {
                $user->uid = static::generateUid();
            }
        });
    }

    /**
     * Generate a unique UID.
     * 
     * @return string
     */
    protected static function generateUid(): string
    {
        // Characters allowed: a-z, A-Z (excluding o and O), 1-9 (excluding 0)
        $characters = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        
        do {
            // First character: cannot be 0, o, or O
            $firstChar = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
            $uid = $firstChar[random_int(0, strlen($firstChar) - 1)];
            
            // Remaining 14 characters: can be any from the full character set
            for ($i = 0; $i < 14; $i++) {
                $uid .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (static::where('uid', $uid)->exists());
        
        return $uid;
    }
}
