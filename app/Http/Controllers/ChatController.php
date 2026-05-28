<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all tasks where I am either the requester or the helper AND a helper is already selected
        $chatTasks = Task::with(['requester', 'helper', 'messages' => function($q) {
            $q->latest()->limit(1); // Get latest message for preview
        }])
        ->whereNotNull('helper_id')
        ->where(function ($q) use ($user) {
            $q->where('requester_id', $user->id)
              ->orWhere('helper_id', $user->id);
        })
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('chat.index', compact('user', 'chatTasks'));
    }

    public function show(Task $task)
    {
        $user = Auth::user();

        // Validate access
        if ($task->requester_id !== $user->id && $task->helper_id !== $user->id) {
            abort(403, 'Akses Ditolak.');
        }

        if (!$task->helper_id) {
            return back()->with('error', 'Belum ada helper yang terpilih untuk tugas ini.');
        }

        $messages = $task->messages()->with('sender')->oldest()->get();

        $partner = $task->requester_id === $user->id ? $task->helper : $task->requester;

        return view('chat.show', compact('user', 'task', 'messages', 'partner'));
    }

    public function store(Request $request, Task $task)
    {
        $user = Auth::user();

        // Validate access
        if ($task->requester_id !== $user->id && $task->helper_id !== $user->id) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'task_id' => $task->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        return back();
    }
}
