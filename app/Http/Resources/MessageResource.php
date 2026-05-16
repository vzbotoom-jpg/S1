<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'role'        => $this->role instanceof \BackedEnum
                ? $this->role->value
                : $this->role,
            'content'     => $this->content,
            'created_at'  => $this->created_at->toISOString(),
            'time_human'  => $this->created_at->format('H:i'),
        ];
    }
}