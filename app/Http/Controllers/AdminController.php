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

        $tasks = Task::with('requester')->where('status', '!=', 'Pending Approval')->latest()->get();
        $pendingTasks = Task::with('requester')->where('status', 'Pending Approval')->latest()->get();
        
        $totalUsers = User::count();
        $totalTasks = Task::count();
        $activeTasks = Task::where('status', 'Open')->count();

        return view('admin.dashboard', compact('tasks', 'pendingTasks', 'totalUsers', 'totalTasks', 'activeTasks'));
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
        if (in_array($task->status, ['Open', 'open', 'in_progress', 'Pending Approval'])) {
            $task->requester->points += $task->reward_points;
            $task->requester->save();
        }

        // We can add a notification to the user here about the deletion
        // (Optional for now, but we can store it in DB notifications)

        $task->delete();

        return back()->with('success', 'Task berhasil dihapus karena: ' . $request->reason);
    }

    public function approveTask($id)
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        $task = Task::findOrFail($id);
        
        if ($task->status === 'Pending Approval') {
            $task->status = 'Open';
            $task->save();
            return back()->with('success', 'Task berhasil disetujui dan sekarang tayang di Beranda!');
        }

        return back()->with('error', 'Task ini tidak dalam status menunggu persetujuan.');
    }
}
