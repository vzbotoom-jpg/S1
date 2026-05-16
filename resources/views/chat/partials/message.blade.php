{{-- resources/views/chat/partials/message.blade.php --}}
@if($message->role === 'user' || (isset($message->is_from_user) && $message->is_from_user))
    {{-- User bubble: kanan, max-width terbatas --}}
    <div class="flex justify-center px-6">
        <div class="w-full max-w-4xl flex justify-end">
            <div class="bg-violet-600/90 text-white rounded-2xl rounded-tr-sm w-fit max-w-[75%] px-5 py-3.5 text-base leading-relaxed break-words whitespace-pre-wrap">
                {{ $message->content }}
            </div>
        </div>
    </div>
@else
    {{-- AI response: centered, tanpa bubble box --}}
    <div class="flex justify-center px-6">
        <div class="w-full max-w-4xl flex items-start gap-4">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shrink-0 mt-1">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0 py-1">
                <div class="ai-message-content prose-ai text-slate-200 text-base leading-relaxed"
                     data-raw="{{ $message->content }}">
                    {{ $message->content }}
                </div>
            </div>
        </div>
    </div>
@endif