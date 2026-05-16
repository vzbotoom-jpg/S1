<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'session_id'     => $this->session_id,
            'title'          => $this->title,
            'messages_count' => $this->whenCounted('messages'),
            'last_message'   => $this->when(
                $this->relationLoaded('messages') && $this->messages->isNotEmpty(),
                fn() => new MessageResource($this->messages->last())
            ),
            'created_at'     => $this->created_at->toISOString(),
            'updated_at'     => $this->updated_at->toISOString(),
            'created_human'  => $this->created_at->diffForHumans(),
        ];
    }
}