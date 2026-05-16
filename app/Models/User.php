<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'gemini_api_key',
        'preferences',
        'last_active_at',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'preferences' => 'array',
        'last_active_at' => 'datetime',
    ];
    
    // Relasi ke Conversations
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    // Relasi ke Messages melalui Conversations
    public function messages()
    {
        return $this->hasManyThrough(Message::class, Conversation::class);
    }
    
    // Alternative: Get messages through conversations
    public function getMessagesAttribute()
    {
        return Message::whereIn('conversation_id', $this->conversations()->pluck('id'))->get();
    }

    
}