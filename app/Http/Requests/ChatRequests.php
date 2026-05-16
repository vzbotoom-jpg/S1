<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequests extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message'         => ['required', 'string', 'min:1', 'max:4000'],
            'conversation_id' => ['required', 'string', 'exists:conversations,session_id'],
            'stream'          => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required'         => 'Pesan tidak boleh kosong.',
            'message.max'              => 'Pesan tidak boleh melebihi 4000 karakter.',
            'conversation_id.required' => 'ID percakapan diperlukan.',
            'conversation_id.exists'   => 'Percakapan tidak ditemukan.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'message' => trim($this->message ?? ''),
        ]);
    }
}