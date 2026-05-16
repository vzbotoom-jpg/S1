<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman riwayat semua conversation
     */
    public function index(): View
    {
        $conversations = auth()->user()
            ->conversations()
            ->withCount('messages')
            ->with(['messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->latest()
            ->paginate(20);

        return view('chat.history', compact('conversations'));
    }

    /**
     * Redirect ke ChatController@show (chat.show)
     */
    public function show(Conversation $conversation): RedirectResponse
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect()->route('chat.show', $conversation->id);
    }

    /**
     * Update judul conversation (AJAX rename dari history.blade.php)
     */
    public function update(Request $request, Conversation $conversation)
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:100',
        ]);

        $conversation->update(['title' => $request->title]);

        return response()->json([
            'success' => true,
            'title'   => $conversation->title,
        ]);
    }

    /**
     * Hapus satu conversation
     */
    public function destroy(Conversation $conversation): RedirectResponse
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $conversation->delete();

        return redirect()->route('conversations.index')
            ->with('success', 'Percakapan berhasil dihapus.');
    }

    /**
     * Hapus semua conversation milik user
     */
    public function bulkDestroy(): RedirectResponse
    {
        auth()->user()->conversations()->delete();

        return redirect()->route('conversations.index')
            ->with('success', 'Semua percakapan berhasil dihapus.');
    }
}