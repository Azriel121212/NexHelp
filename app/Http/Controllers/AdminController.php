<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $tasks = Task::with('requester')->latest()->get();
        
        $totalUsers = User::count();
        $totalTasks = Task::count();
        $activeTasks = Task::where('status', 'open')->count();

        return view('admin.dashboard', compact('tasks', 'totalUsers', 'totalTasks', 'activeTasks'));
    }

    public function destroyTask(Request $request, $id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $task = Task::findOrFail($id);
        
        // Return points to requester
        if ($task->status == 'open' || $task->status == 'in_progress') {
            $task->requester->points += $task->reward_points;
            $task->requester->save();
        }

        // We can add a notification to the user here about the deletion
        // (Optional for now, but we can store it in DB notifications)

        $task->delete();

        return back()->with('success', 'Task berhasil dihapus karena: ' . $request->reason);
    }
}
