<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\AI\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    // Menampilkan daftar chat (sidebar)
    public function index(): View
    {
        $conversations = auth()->user()->conversations()->latest()->get();
        return view('chat.index', compact('conversations'));
    }

    // Membuat chat baru dan redirect ke halaman chat
    public function store(): RedirectResponse
    {
        $conversation = auth()->user()->conversations()->create([
            'title'    => 'Chat Baru',
            'model'    => config('services.gemini.model', 'gemini-1.5-flash'), // Ambil dari config
            'settings' => [
                'temperature' => 0.7,
                'max_tokens'  => 2048,
            ],
        ]);

    return redirect()->route('chat.show', $conversation);
    }

    // Menampilkan halaman chat dengan input field
    public function show(Conversation $conversation): View
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $messages      = $conversation->messages()->oldest()->get();
        $conversations = auth()->user()->conversations()->latest()->get();

        return view('chat.show', compact('conversation', 'messages', 'conversations'));
    }

    // Mengirim pesan — return JSON untuk AJAX di chat/show.blade.php
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $this->geminiService->setModel($conversation->model);

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        // Simpan pesan user
        $conversation->messages()->create([
            'role'    => 'user',
            'content' => $request->message,
        ]);

        // Ambil riwayat chat
        $history = $conversation->messages()
            ->oldest()
            ->get()
            ->map(fn($msg) => [
                'role'    => $msg->role,
                'content' => $msg->content,
            ])
            ->toArray();

        // Set API key dari user jika ada
        if (auth()->user()->gemini_api_key) {
            $this->geminiService->setApiKey(auth()->user()->gemini_api_key);
        }

        // Dapatkan respons dari AI
        $aiResponse = $this->geminiService->sendConversation($history);

        // Simpan respons AI
        $conversation->messages()->create([
            'role'    => 'assistant',
            'content' => $aiResponse,
        ]);

        // Update judul chat jika masih default
        if ($conversation->title === 'Chat Baru') {
            $conversation->update([
                'title' => substr($request->message, 0, 50),
            ]);
        }

        // Return JSON — bukan redirect
        return response()->json([
            'message' => $aiResponse,
        ]);
    }

    // Menghapus chat
    public function destroy(Conversation $conversation): RedirectResponse
    {
        if ($conversation->user_id !== auth()->id()) {
            abort(403);
        }

        $conversation->delete();

        return redirect()->route('chat.index')
            ->with('success', 'Chat berhasil dihapus.');
    }
}