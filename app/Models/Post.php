<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
     protected  $fillable = [
        'freelancer_profile_id',
         'title',
         'description',
         'image',
];

    public function freelancerProfile()
    {
        return $this->belongsTo(FreelancerProfile::class, 'freelancer_profile_id');
    }
}
