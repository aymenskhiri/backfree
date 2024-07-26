<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'service_date',
        'description',
        'post_id',
        'freelancer_id',
        'client_id',
    ];

    public function client()
    {
        return $this->belongsTo(client::class);
    }
}
