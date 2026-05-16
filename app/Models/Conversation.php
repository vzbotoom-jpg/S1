<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'model',
        'settings',
    ];
    
    protected $casts = [
        'settings' => 'array',
    ];
    
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke Messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}