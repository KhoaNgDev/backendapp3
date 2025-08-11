<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'is_delete',
        'group_role',
        'status',
        'photo',
        'phone',
        'last_login_at',
        'last_login_ip',
        'otp_context',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
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
             'otp_expires_at' => 'datetime',
        ];
    }




    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }
}
