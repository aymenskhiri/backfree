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
        'competences',
        'tarif_horaires',
        'reviews',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
