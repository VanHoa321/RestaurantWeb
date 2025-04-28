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

    public function getRecommendations(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');

        $response = Http::get('http://localhost:5001/recommend', [
            'id' => $id,
            'type' => $type
        ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } 
        else {
            return response()->json(['error' => 'Không thể nhận được dữ liệu từ Python API'], 500);
        }
    }
}
