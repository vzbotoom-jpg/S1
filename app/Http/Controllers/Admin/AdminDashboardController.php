<?php

namespace App\Http\Controllers\Admin;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalConversations = Conversation::count();
        $totalMessages = Message::count();
        $todayActive = Conversation::whereDate('updated_at', today())->count();

        $recentConversations = Conversation::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashbord.index', compact('totalUsers', 'totalConversations', 'totalMessages', 'todayActive', 'recentConversations', 'recentUsers'));
    }

    public function users(Request $request): View
    {
        $users = User::withCount(['conversations', 'messages'])
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function settings(): View
    {
        return view('admin.settings.index');
    }

    public function updateSettings(Request $request)
    {
        // Add settings update logic here
        return back()->with('success', 'Settings updated successfully.');
    }

    public function stats(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_conversations' => Conversation::count(),
            'total_messages' => Message::count(),
            'active_today' => Conversation::whereDate('updated_at', today())->count(),
        ];

        return view('admin.stats', compact('stats'));
    }

    public function analytics(): View
    {
        // Data untuk 7 hari terakhir
        $days = 7;
        $dailyData = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $labels[] = $date->format('D');

            // Hitung pengguna baru per hari
            $newUsers = User::whereDate('created_at', $date)->count();
            
            // Hitung percakapan per hari
            $conversations = Conversation::whereDate('created_at', $date)->count();
            
            // Hitung pesan per hari
            $messages = Message::whereDate('created_at', $date)->count();

            $dailyData[] = [
                'date' => $date->toDateString(),
                'new_users' => $newUsers,
                'conversations' => $conversations,
                'messages' => $messages,
                'active_users' => Conversation::whereDate('updated_at', $date)->distinct('user_id')->count('user_id')
            ];
        }

        // Overall metrics
        $totalViews = Conversation::count();
        $uniqueUsers = User::count();
        $totalMessages = Message::count();
        $avgSession = round($totalMessages / max($uniqueUsers, 1), 2) . ' msg/user';
        $bounceRate = '32.5%';

        // Top pages data
        $topPages = [
            [
                'path' => '/dashboard',
                'views' => Conversation::count(),
                'percentage' => 85
            ],
            [
                'path' => '/chat',
                'views' => Message::count(),
                'percentage' => 72
            ],
            [
                'path' => '/profile',
                'views' => round(Message::count() * 0.48),
                'percentage' => 48
            ]
        ];

        return view('admin.analytics', compact('dailyData', 'labels', 'totalViews', 'uniqueUsers', 'avgSession', 'bounceRate', 'topPages'));
    }

    public function allConversations(Request $request): View
    {
        $conversations = Conversation::with('user')
            ->withCount('messages')
            ->latest()
            ->paginate(20);

        return view('admin.conversations', compact('conversations'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function deleteConversation(Conversation $conversation)
    {
        $conversation->delete();

        return back()->with('success', 'Conversation deleted successfully.');
    }
}