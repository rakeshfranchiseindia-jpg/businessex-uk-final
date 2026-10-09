<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ChatbotController extends Controller
{
    public function respond(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:500'],
        ]);
        $question = Str::lower(trim($validated['message']));
        $bestMatch = null;
        $bestScore = 0;

        $knowledgeBase = Cache::remember(
            'chatbot.active-knowledge-base.v1',
            now()->addMinutes(5),
            fn () => DB::table('chatbot_knowledge_base')
                ->where('is_active', true)
                ->get(['keywords', 'answer', 'answer_url'])
        );

        foreach ($knowledgeBase as $entry) {
            $keywords = json_decode($entry->keywords, true, 512, JSON_THROW_ON_ERROR);
            $score = 0;
            foreach ($keywords as $keyword) {
                $keyword = Str::lower(trim($keyword));
                if ($keyword !== '' && str_contains($question, $keyword)) {
                    $score += mb_strlen($keyword);
                }
            }

            if ($score > $bestScore) {
                $bestMatch = $entry;
                $bestScore = $score;
            }
        }

        if ($bestMatch) {
            return response()->json([
                'answer' => $bestMatch->answer,
                'link_url' => $bestMatch->answer_url,
                'matched' => true,
            ]);
        }

        return response()->json([
            'answer' => 'I could not find a direct answer. Leave your contact details and our team can follow up with you.',
            'matched' => false,
        ]);
    }

    public function storeLead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:32'],
            'message' => ['required', 'string', 'min:2', 'max:2000'],
        ]);
        $message = trim($validated['message']);
        if (preg_match('/[\p{Cc}\p{Cf}]/u', $message)) {
            throw ValidationException::withMessages([
                'message' => 'The message contains unsupported characters.',
            ]);
        }

        DB::table('chatbot_leads')->insert([
            'name' => trim($validated['name']),
            'email' => Str::lower(trim($validated['email'])),
            'mobile' => trim($validated['mobile']),
            'message' => $message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Thanks — your details have been sent to our team. We will be in touch.',
        ], 201);
    }
}
