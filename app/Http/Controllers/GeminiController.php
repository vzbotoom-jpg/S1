<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;

class GeminiController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);

        $result = Gemini::geminiFlash()->generateContent($request->prompt);

        return response()->json([
            'response' => $result->text()
        ]);
    }
}