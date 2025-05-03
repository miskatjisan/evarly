<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function show($id)
    {
        $result = Result::with([
            'exam.questions.options',
            'user'
        ])->findOrFail($id);
    
        $userAnswers = $result->exam->questions->map(function ($q) use ($result) {
            $correct = $q->options->where('is_correct', true)->first();
            $userAnswer = $q->userAnswers()
                ->where('user_id', $result->user_id)
                ->first();
    
            return [
                'question_id' => $q->id,
                'question_text' => $q->question_text,
                'your_answer' => $userAnswer?->option?->option_text,
                'correct_answer' => $correct?->option_text,
                'is_correct' => $userAnswer && $userAnswer->option_id == $correct->id,
            ];
        });
    
        return response()->json([
            'score' => $result->score,
            'details' => $userAnswers,
        ]);
    }
    
}
