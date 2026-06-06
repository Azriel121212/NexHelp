<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Get top 10 users by completed tasks (Completed)
        $topUsers = User::withCount(['tasksHelped' => function ($query) {
            $query->where('status', 'Completed');
        }])->orderBy('tasks_helped_count', 'desc')
          ->orderBy('points', 'desc') // fallback kalo ada yang sama
          ->take(10)->get();

        return view('leaderboard.index', compact('topUsers'));
    }
}
