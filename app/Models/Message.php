<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'conversation_id',
        'role',
        'content',
        'metadata',
    ];
    
    protected $casts = [
        'metadata' => 'array',
    ];
    
    // Relasi ke Conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
    
    // HAPUS relasi user_id karena tidak ada
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}