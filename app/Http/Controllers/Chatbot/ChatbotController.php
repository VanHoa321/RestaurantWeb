<?php

namespace App\Http\Controllers\Chatbot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function askAI(Request $request)
    {
        $question = $request->input('question');

        $response = Http::post('http://127.0.0.1:5000/ask', [
            'question' => $question
        ]);

        $answer = $response->json()['answer'] ?? 'Không có phản hồi';

        $formattedAnswer = nl2br(htmlentities($answer));

        return response()->json(['question' => $question, 'answer' => $formattedAnswer]);
    }
}
