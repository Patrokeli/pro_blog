<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Added role to mass assignable fields
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Consider adding if you need boolean checks:
        // 'is_admin' => 'boolean',
    ];

    /**
     * Determine if the user has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

 /**
 * Get the user's initials for avatar display.
 */
    public function initials(): string
    {
        $nameParts = explode(' ', trim($this->name));
        $initials = '';
        
        foreach ($nameParts as $part) {
            if ($part) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Determine if the user has regular user role.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Set the user's password (hashed automatically).
     */
    

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relationship with posts.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}