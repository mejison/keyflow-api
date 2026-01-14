<?php

namespace App\Http\Controllers\Api;

use App\Models\TypingTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypingTestController extends BaseController
{
    /**
     * Save a completed typing test
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wpm' => 'required|integer|min:0',
            'accuracy' => 'required|numeric|min:0|max:100',
            'duration' => 'required|integer|min:1',
            'correct_words' => 'required|integer|min:0',
            'incorrect_words' => 'required|integer|min:0',
            'total_words' => 'required|integer|min:0',
            'text_content' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors()->toArray(), 422);
        }

        $typingTest = TypingTest::create([
            'user_id' => $request->user()->id,
            'wpm' => $request->wpm,
            'accuracy' => $request->accuracy,
            'duration' => $request->duration,
            'correct_words' => $request->correct_words,
            'incorrect_words' => $request->incorrect_words,
            'total_words' => $request->total_words,
            'text_content' => $request->text_content,
            'completed_at' => now(),
        ]);

        return $this->sendResponse($typingTest, 'Typing test saved successfully', 201);
    }

    /**
     * Get user's typing test statistics
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        $tests = $user->typingTests();

        $stats = [
            'best_wpm' => $tests->max('wpm') ?? 0,
            'avg_wpm' => round($tests->avg('wpm') ?? 0, 0),
            'avg_accuracy' => round($tests->avg('accuracy') ?? 0, 2),
            'tests_taken' => $tests->count(),
            'total_words_typed' => $tests->sum('total_words'),
            'total_time_spent' => $tests->sum('duration'), // in seconds
            'recent_tests' => $tests->orderBy('completed_at', 'desc')->take(5)->get(),
        ];

        return $this->sendResponse($stats, 'Statistics retrieved successfully');
    }

    /**
     * Get recent activity (typing test history)
     */
    public function recentActivity(Request $request)
    {
        $limit = $request->query('limit', 10);
        
        $activities = $request->user()
            ->typingTests()
            ->orderBy('completed_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($test) {
                return [
                    'id' => $test->id,
                    'wpm' => $test->wpm,
                    'accuracy' => $test->accuracy,
                    'duration' => $test->duration,
                    'completed_at' => $test->completed_at,
                    'completed_at_human' => $test->completed_at->diffForHumans(),
                ];
            });

        return $this->sendResponse($activities, 'Recent activity retrieved successfully');
    }

    /**
     * Get user's progress over time
     */
    public function progress(Request $request)
    {
        $days = $request->query('days', 30);
        
        $progress = $request->user()
            ->typingTests()
            ->where('completed_at', '>=', now()->subDays($days))
            ->orderBy('completed_at', 'asc')
            ->get()
            ->map(function ($test) {
                return [
                    'date' => $test->completed_at->format('Y-m-d'),
                    'wpm' => $test->wpm,
                    'accuracy' => $test->accuracy,
                    'completed_at' => $test->completed_at->toIso8601String(),
                ];
            });

        return $this->sendResponse($progress, 'Progress data retrieved successfully');
    }

    /**
     * Get all typing tests for the authenticated user
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        
        $tests = $request->user()
            ->typingTests()
            ->orderBy('completed_at', 'desc')
            ->paginate($perPage);

        return $this->sendResponse($tests, 'Typing tests retrieved successfully');
    }

    /**
     * Get a specific typing test
     */
    public function show(Request $request, $id)
    {
        $test = $request->user()
            ->typingTests()
            ->find($id);

        if (!$test) {
            return $this->sendError('Typing test not found', [], 404);
        }

        return $this->sendResponse($test, 'Typing test retrieved successfully');
    }

    /**
     * Delete a typing test
     */
    public function destroy(Request $request, $id)
    {
        $test = $request->user()
            ->typingTests()
            ->find($id);

        if (!$test) {
            return $this->sendError('Typing test not found', [], 404);
        }

        $test->delete();

        return $this->sendResponse([], 'Typing test deleted successfully');
    }

    /**
     * Get personal bests
     */
    public function personalBests(Request $request)
    {
        $user = $request->user();

        $bests = [
            'highest_wpm' => $user->typingTests()
                ->orderBy('wpm', 'desc')
                ->first(),
            'highest_accuracy' => $user->typingTests()
                ->orderBy('accuracy', 'desc')
                ->first(),
            'longest_test' => $user->typingTests()
                ->orderBy('duration', 'desc')
                ->first(),
            'most_words' => $user->typingTests()
                ->orderBy('total_words', 'desc')
                ->first(),
        ];

        return $this->sendResponse($bests, 'Personal bests retrieved successfully');
    }
}
