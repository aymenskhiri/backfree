<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    // Include the 'status' field in the $fillable array
    protected $fillable = [
        'client_id',
        'freelancer_id',
        'status', // Add status to fillable so it can be mass-assigned
    ];

    // Define relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(FreelancerProfile::class);
    }

    // Optionally, you can define default values for attributes
    protected $attributes = [
        'status' => 'open', // Default value for status
    ];
}
