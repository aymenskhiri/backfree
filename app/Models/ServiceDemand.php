<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDemand extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'freelancer_id',
        'client_id',
        'additional_field',
        'status',
    ];


     public function post()
     {
         return $this->belongsTo(Post::class, 'post_id');
     }
}
