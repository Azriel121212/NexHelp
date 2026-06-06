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

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function getMessages(Task $task)
    {
        $user = Auth::user();

        if ($task->requester_id !== $user->id && $task->helper_id !== $user->id) {
            return response()->json(['error' => 'Akses Ditolak.'], 403);
        }

        $messages = $task->messages()->with('sender')->oldest()->get();
        
        $html = '';
        foreach ($messages as $msg) {
            $isMe = $msg->sender_id === $user->id;
            if ($isMe) {
                $html .= '<div class="flex items-end justify-end gap-2">';
                $html .= '<div class="max-w-[75%] bg-primary text-on-primary p-3 rounded-2xl rounded-tr-sm shadow-sm">';
                $html .= '<p class="text-sm whitespace-pre-wrap">' . e($msg->message) . '</p>';
                $html .= '<span class="text-[9px] text-primary-fixed-dim mt-1 block text-right">' . $msg->created_at->format('H:i') . '</span>';
                $html .= '</div></div>';
            } else {
                $html .= '<div class="flex items-end gap-2">';
                $html .= '<img src="' . $msg->sender->avatar_url . '" alt="Avatar" class="w-8 h-8 rounded-full object-cover mb-1">';
                $html .= '<div class="max-w-[75%] bg-surface-container text-on-surface p-3 rounded-2xl rounded-tl-sm shadow-sm border border-surface-bright">';
                $html .= '<p class="text-xs font-bold text-on-surface-variant mb-1">' . e($msg->sender->name) . '</p>';
                $html .= '<p class="text-sm whitespace-pre-wrap">' . e($msg->message) . '</p>';
                $html .= '<span class="text-[9px] text-outline mt-1 block text-right">' . $msg->created_at->format('H:i') . '</span>';
                $html .= '</div></div>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
