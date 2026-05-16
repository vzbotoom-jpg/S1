{{-- Component: message-bubble --}}
{{-- Usage: @include('components.message-bubble', ['message' => $message]) --}}

@php
    $isUser = $message->role === 'user';
    $content = $message->content ?? '';
@endphp

@if($isUser)
{{-- User Message --}}
<div class="flex justify-end animate-fade-in">
    <div class="max-w-[75%]">
        <div class="bg-brand-500/20 border border-brand-500/25 rounded-2xl rounded-tr-md px-4 py-3 text-sm text-slate-200 leading-relaxed whitespace-pre-wrap break-words">
            {{ $content }}
        </div>
        <p class="text-xs text-slate-600 text-right mt-1.5 pr-1">
            {{ $message->created_at?->format('H:i') }}
        </p>
    </div>
</div>
@else
{{-- AI Message --}}
<div class="flex items-start gap-3 animate-fade-in">
    <div class="w-8 h-8 rounded-full bg-brand-500/20 border border-brand-500/30 flex items-center justify-center flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>
        </svg>
    </div>
    <div class="max-w-[75%] min-w-0">
        <div class="glass rounded-2xl rounded-tl-md px-4 py-3 text-sm text-slate-200 prose-ai break-words">
            {!! nl2br(e($content)) !!}
        </div>
        <p class="text-xs text-slate-600 mt-1.5 pl-1">
            NexusAI · {{ $message->created_at?->format('H:i') }}
        </p>
    </div>
</div>
@endif