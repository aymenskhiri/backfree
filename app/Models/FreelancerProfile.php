<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerProfile extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'bio',
        'skills',
        'hourly_price',
        'reviews',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'freelancer_profile_id');
    }
}
