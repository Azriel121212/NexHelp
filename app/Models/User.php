<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'major', 'faculty', 'points', 'nim', 'skills', 'bio', 'avatar', 'banned_until'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'banned_until' => 'datetime',
        ];
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            
            if (!env('VERCEL')) {
                return asset('storage/' . $this->avatar);
            }
        }

        // Generate default avatar using ui-avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff&bold=true';
    }

    public function taskApplications()
    {
        return $this->hasMany(TaskApplication::class);
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'helper_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function tasksHelped()
    {
        return $this->hasMany(Task::class, 'helper_id');
    }

    public function isBanned()
    {
        if ($this->is_banned) {
            return true;
        }
        if ($this->banned_until && $this->banned_until->isFuture()) {
            return true;
        }
        return false;
    }
}
