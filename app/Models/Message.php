<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'sender', 'message'];

    // Define relationship to Conversation model if needed
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
