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
];

    public function freelancerProfile(){
        return $this->belongsTo(FreelancerProfile::class);
    }
}
