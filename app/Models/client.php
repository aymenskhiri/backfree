<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'demands_history'];

    protected $casts = [
        'demands_history' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
