<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message'         => ['required', 'string', 'min:1', 'max:4000'],
            'conversation_id' => ['sometimes', 'string', 'exists:conversations,session_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max'      => 'Pesan tidak boleh melebihi 4000 karakter.',
        ];
    }
}