<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'role',
        'email',
        'password',
        'client_id',
        'freelancer_id',
    ];


    public function getClientIdAttribute() {
        return $this->attributes['client_id'] ?? null;
    }

    public function getFreelancerIdAttribute() {
        return $this->attributes['freelancer_id'] ?? null;
    }
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
     * @return HasOne
     */
    public function freelancerProfile()
    {
        return $this->hasOne(FreelancerProfile::class);
    }

    /**
     * @return HasOne
     */

    public function client()
    {
        return $this->hasOne(Client::class);
    }

}
