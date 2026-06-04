<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Get top 10 users by points
        $topUsers = User::orderBy('points', 'desc')->take(10)->get();

        return view('leaderboard.index', compact('topUsers'));
    }
}
