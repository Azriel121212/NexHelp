<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Tasks where I am the requester (but not 'Open' since they are in Home)
        // Wait, maybe we should show 'Open' tasks here too? Yes, it's my request.
        $requestedTasks = Task::with(['helper'])
            ->where('requester_id', $user->id)
            ->latest()
            ->get();

        // Tasks where I am the helper
        $helperTasks = Task::with(['requester'])
            ->where('helper_id', $user->id)
            ->latest()
            ->get();

        return view('activity.index', compact('user', 'requestedTasks', 'helperTasks'));
    }
}
