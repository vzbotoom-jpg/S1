<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Hitung total messages melalui conversations
        $totalMessages = Message::whereIn('conversation_id', 
            $user->conversations()->pluck('id')
        )->count();
        
        $stats = [
            'total_conversations' => $user->conversations()->count(),
            'total_messages' => $totalMessages,
            'recent_conversations' => $user->conversations()->latest()->take(5)->get(),
        ];
        
        return view('dashboard.index', compact('user', 'stats'));
    }
}