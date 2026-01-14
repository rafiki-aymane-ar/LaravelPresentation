<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Cache;

/**
 * Handles quiz-related operations including displaying the quiz,
 * storing scores, and managing the leaderboard.
 */
class QuizController extends Controller
{
    /**
     * Display the quiz welcome/login page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return view('auth-login');
    }

    /**
     * Start the game session.
     * 
     * Loads questions from the JSON file and renders the game interface.
     *
     * @return \Illuminate\View\View
     */
    public function play()
    {
        $json = File::get(resource_path('data/quiz.json'));
        $questions = json_decode($json, true);

        return view('quiz.play', compact('questions'));
    }

    /**
     * Save the user's score to the database.
     * 
     * - Validates the input score.
     * - Updates the user's high score only if the new score is better.
     * - Invalidates the leaderboard cache if an update occurs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:10',
        ]);

        $user = auth()->user();

        // Only update if the new score is higher
        if ($request->score > $user->score) {
            $user->score = $request->score;
            $user->save();

            // Invalidate leaderboard cache
            Cache::forget('leaderboard_top_5');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Retrieve the top 5 players for the leaderboard.
     * 
     * UTilizes caching to reduce database load.
     * Cache duration: 5 minutes (300 seconds).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaderboard()
    {
        // Cache the leaderboard for 5 minutes (or until a new high score is set)
        $topUsers = Cache::remember('leaderboard_top_5', 300, function () {
            return \App\Models\User::orderByDesc('score')
                ->whereNotNull('score') // Optimization: Ignore users without score
                ->take(5)
                ->get();
        });

        return response()->json($topUsers);
    }
}
