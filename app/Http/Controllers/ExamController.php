<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use App\Models\UserAnswer;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    // Show all exams
    public function index()
    {
        $exams = Exam::withCount('questions')->get();
        return response()->json($exams);
    }

    // Show single exam with questions and options
    public function show($id)
    {
        $exam = Exam::with(['questions.options'])->findOrFail($id);
        return response()->json($exam);
    }

    // Submit answers for an exam
    // public function submit(Request $request, $examId)
    // {
    //     $user = Auth::user();
    //     $exam = Exam::with('questions.options')->findOrFail($examId);

    //     $submittedAnswers = $request->input('answers'); // ['question_id' => option_id]

    //     if (!$submittedAnswers || !is_array($submittedAnswers)) {
    //         return response()->json(['message' => 'Invalid submission data.'], 422);
    //     }

    //     $totalScore = 0;
    //     $results = [];

    //     foreach ($exam->questions as $question) {
    //         $questionId = $question->id;

    //         $givenOptionId = $submittedAnswers[$questionId] ?? null;

    //         $correctOption = $question->options->where('is_correct', true)->first();

    //         $isCorrect = ($givenOptionId && $correctOption && $correctOption->id == $givenOptionId);
    //         if ($isCorrect) {
    //             $totalScore += $question->points;
    //         }

    //         UserAnswer::create([
    //             'user_id' => $user->id,
    //             'exam_id' => $exam->id,
    //             'question_id' => $question->id,
    //             'option_id' => $givenOptionId,
    //         ]);

    //         $results[] = [
    //             'question_id' => $question->id,
    //             'question_text' => $question->question_text,
    //             'your_answer' => $givenOptionId ? Option::find($givenOptionId)->option_text : null,
    //             'correct_answer' => $correctOption ? $correctOption->option_text : null,
    //             'is_correct' => $isCorrect,
    //         ];
    //     }

    //     Result::updateOrCreate(
    //         ['user_id' => $user->id, 'exam_id' => $exam->id],
    //         ['score' => $totalScore]
    //     );

    //     return response()->json([
    //         'message' => 'Exam submitted successfully.',
    //         'score' => $totalScore,
    //         'details' => $results,
    //     ]);
    // }



    public function submit(Request $request, $examId)
{
    $user = auth()->user(); // or $request->user()

    $exam = Exam::with('questions.options')->findOrFail($examId);

    $score = 0;
    $answers = $request->input('answers', []);

    foreach ($exam->questions as $question) {
        $correctOption = $question->options->where('is_correct', true)->first();

        $userAnswer = new UserAnswer([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'question_id' => $question->id,
            'option_id' => $answers[$question->id] ?? null,
        ]);
        $userAnswer->save();

        if (
            isset($answers[$question->id]) &&
            $answers[$question->id] == $correctOption->id
        ) {
            $score++;
        }
    }

    $result = Result::create([
        'user_id' => $user->id,
        'exam_id' => $exam->id,
        'score' => $score,
    ]);

    return response()->json([
        'result_id' => $result->id,
    ]);
}



}

