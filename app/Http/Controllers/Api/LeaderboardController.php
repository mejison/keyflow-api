<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends BaseController
{
    /**
     * Get leaderboard by WPM
     */
    public function topByWpm(Request $request)
    {
        $period = $request->query('period', 'all'); // today, week, month, all
        $limit = (int) $request->query('limit', 50);
        
        $query = User::select('users.id', 'users.name', 'users.email')
            ->join('typing_tests', 'users.id', '=', 'typing_tests.user_id')
            ->selectRaw('MAX(typing_tests.wpm) as best_wpm')
            ->selectRaw('AVG(typing_tests.wpm) as avg_wpm')
            ->selectRaw('AVG(typing_tests.accuracy) as avg_accuracy')
            ->selectRaw('COUNT(typing_tests.id) as total_tests');
        
        // Apply period filter
        $query = $this->applyPeriodFilter($query, $period);
        
        $leaderboard = $query->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('best_wpm')
            ->limit($limit)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'best_wpm' => (int) $user->best_wpm,
                    'avg_wpm' => round($user->avg_wpm, 1),
                    'avg_accuracy' => round($user->avg_accuracy, 1),
                    'total_tests' => (int) $user->total_tests,
                ];
            });
        
        return $this->sendResponse([
            'leaderboard' => $leaderboard,
            'period' => $period,
            'type' => 'wpm',
        ], 'Leaderboard retrieved successfully');
    }
    
    /**
     * Get leaderboard by accuracy
     */
    public function topByAccuracy(Request $request)
    {
        $period = $request->query('period', 'all');
        $limit = (int) $request->query('limit', 50);
        $minTests = (int) $request->query('min_tests', 5); // Minimum tests required to appear
        
        $query = User::select('users.id', 'users.name', 'users.email')
            ->join('typing_tests', 'users.id', '=', 'typing_tests.user_id')
            ->selectRaw('MAX(typing_tests.accuracy) as best_accuracy')
            ->selectRaw('AVG(typing_tests.accuracy) as avg_accuracy')
            ->selectRaw('AVG(typing_tests.wpm) as avg_wpm')
            ->selectRaw('COUNT(typing_tests.id) as total_tests');
        
        $query = $this->applyPeriodFilter($query, $period);
        
        $leaderboard = $query->groupBy('users.id', 'users.name', 'users.email')
            ->havingRaw('COUNT(typing_tests.id) >= ?', [$minTests])
            ->orderByDesc('avg_accuracy')
            ->limit($limit)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'best_accuracy' => round($user->best_accuracy, 1),
                    'avg_accuracy' => round($user->avg_accuracy, 1),
                    'avg_wpm' => round($user->avg_wpm, 1),
                    'total_tests' => (int) $user->total_tests,
                ];
            });
        
        return $this->sendResponse([
            'leaderboard' => $leaderboard,
            'period' => $period,
            'type' => 'accuracy',
            'min_tests' => $minTests,
        ], 'Leaderboard retrieved successfully');
    }
    
    /**
     * Get leaderboard by total tests taken
     */
    public function topByTests(Request $request)
    {
        $period = $request->query('period', 'all');
        $limit = (int) $request->query('limit', 50);
        
        $query = User::select('users.id', 'users.name', 'users.email')
            ->join('typing_tests', 'users.id', '=', 'typing_tests.user_id')
            ->selectRaw('COUNT(typing_tests.id) as total_tests')
            ->selectRaw('AVG(typing_tests.wpm) as avg_wpm')
            ->selectRaw('AVG(typing_tests.accuracy) as avg_accuracy')
            ->selectRaw('MAX(typing_tests.wpm) as best_wpm');
        
        $query = $this->applyPeriodFilter($query, $period);
        
        $leaderboard = $query->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_tests')
            ->limit($limit)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'total_tests' => (int) $user->total_tests,
                    'avg_wpm' => round($user->avg_wpm, 1),
                    'avg_accuracy' => round($user->avg_accuracy, 1),
                    'best_wpm' => (int) $user->best_wpm,
                ];
            });
        
        return $this->sendResponse([
            'leaderboard' => $leaderboard,
            'period' => $period,
            'type' => 'tests',
        ], 'Leaderboard retrieved successfully');
    }
    
    /**
     * Get combined leaderboard (balanced score)
     */
    public function topCombined(Request $request)
    {
        $period = $request->query('period', 'all');
        $limit = (int) $request->query('limit', 50);
        $minTests = (int) $request->query('min_tests', 5);
        
        $query = User::select('users.id', 'users.name', 'users.email')
            ->join('typing_tests', 'users.id', '=', 'typing_tests.user_id')
            ->selectRaw('AVG(typing_tests.wpm) as avg_wpm')
            ->selectRaw('AVG(typing_tests.accuracy) as avg_accuracy')
            ->selectRaw('MAX(typing_tests.wpm) as best_wpm')
            ->selectRaw('COUNT(typing_tests.id) as total_tests')
            // Combined score: WPM * (Accuracy/100) - балансує швидкість та точність
            ->selectRaw('AVG(typing_tests.wpm * typing_tests.accuracy / 100) as score');
        
        $query = $this->applyPeriodFilter($query, $period);
        
        $leaderboard = $query->groupBy('users.id', 'users.name', 'users.email')
            ->havingRaw('COUNT(typing_tests.id) >= ?', [$minTests])
            ->orderByDesc('score')
            ->limit($limit)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                    'score' => round($user->score, 1),
                    'avg_wpm' => round($user->avg_wpm, 1),
                    'avg_accuracy' => round($user->avg_accuracy, 1),
                    'best_wpm' => (int) $user->best_wpm,
                    'total_tests' => (int) $user->total_tests,
                ];
            });
        
        return $this->sendResponse([
            'leaderboard' => $leaderboard,
            'period' => $period,
            'type' => 'combined',
            'min_tests' => $minTests,
        ], 'Leaderboard retrieved successfully');
    }
    
    /**
     * Get current user's rank in different leaderboards
     */
    public function myRank(Request $request)
    {
        $user = $request->user();
        $period = $request->query('period', 'all');
        $minTests = (int) $request->query('min_tests', 5);
        
        if (!$user) {
            return $this->sendError('Unauthorized', [], 401);
        }
        
        // Get user's best WPM rank
        $wpmRank = $this->getUserRank($user->id, 'wpm', $period, $minTests);
        
        // Get user's accuracy rank
        $accuracyRank = $this->getUserRank($user->id, 'accuracy', $period, $minTests);
        
        // Get user's tests rank
        $testsRank = $this->getUserRank($user->id, 'tests', $period, $minTests);
        
        // Get user's combined rank
        $combinedRank = $this->getUserRank($user->id, 'combined', $period, $minTests);
        
        return $this->sendResponse([
            'ranks' => [
                'wpm' => $wpmRank,
                'accuracy' => $accuracyRank,
                'tests' => $testsRank,
                'combined' => $combinedRank,
            ],
            'period' => $period,
            'min_tests' => $minTests,
        ], 'User ranks retrieved successfully');
    }
    
    /**
     * Apply period filter to query
     */
    private function applyPeriodFilter($query, $period)
    {
        switch ($period) {
            case 'today':
                $query->whereDate('typing_tests.completed_at', today());
                break;
            case 'week':
                $query->where('typing_tests.completed_at', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('typing_tests.completed_at', '>=', now()->subMonth());
                break;
            case 'year':
                $query->where('typing_tests.completed_at', '>=', now()->subYear());
                break;
            case 'all':
            default:
                // No filter
                break;
        }
        
        return $query;
    }
    
    /**
     * Get user's rank in specific leaderboard
     */
    private function getUserRank($userId, $type, $period, $minTests = 5)
    {
        $query = User::select('users.id')
            ->join('typing_tests', 'users.id', '=', 'typing_tests.user_id');
        
        $query = $this->applyPeriodFilter($query, $period);
        
        switch ($type) {
            case 'wpm':
                $query->selectRaw('MAX(typing_tests.wpm) as value')
                    ->groupBy('users.id')
                    ->orderByDesc('value');
                break;
            case 'accuracy':
                $query->selectRaw('AVG(typing_tests.accuracy) as value')
                    ->selectRaw('COUNT(typing_tests.id) as test_count')
                    ->groupBy('users.id')
                    ->havingRaw('COUNT(typing_tests.id) >= ?', [$minTests])
                    ->orderByDesc('value');
                break;
            case 'tests':
                $query->selectRaw('COUNT(typing_tests.id) as value')
                    ->groupBy('users.id')
                    ->orderByDesc('value');
                break;
            case 'combined':
                $query->selectRaw('AVG(typing_tests.wpm * typing_tests.accuracy / 100) as value')
                    ->selectRaw('COUNT(typing_tests.id) as test_count')
                    ->groupBy('users.id')
                    ->havingRaw('COUNT(typing_tests.id) >= ?', [$minTests])
                    ->orderByDesc('value');
                break;
        }
        
        $rankedUsers = $query->get()->pluck('id')->toArray();
        $rank = array_search($userId, $rankedUsers);
        
        return $rank !== false ? $rank + 1 : null;
    }
}

