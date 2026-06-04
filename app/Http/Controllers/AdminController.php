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

        $totalUsers = User::count();
        $totalTasks = Task::count();
        $activeTasks = Task::whereIn('status', ['Open', 'In Progress'])->count();

        $pendingTasks = Task::with('requester')->where('status', 'Pending Approval')->latest()->get();
        $allTasks = Task::with('requester')->latest()->get();

        return view('admin.dashboard', compact('totalUsers', 'totalTasks', 'activeTasks', 'pendingTasks', 'allTasks'));
    }

    public function getPendingTasksHtml()
    {
        $pendingTasks = Task::with('requester')->where('status', 'Pending Approval')->latest()->get();
        
        $html = '';
        foreach ($pendingTasks as $task) {
            $html .= '<tr class="border-b border-surface-bright last:border-0 hover:bg-surface-bright transition-colors">';
            $html .= '<td class="p-3"><a href="' . route('task.show', $task->id) . '" class="text-primary font-bold hover:underline">' . e($task->title) . '</a></td>';
            $html .= '<td class="p-3 text-on-surface-variant">' . e($task->requester->name) . '</td>';
            $html .= '<td class="p-3"><span class="bg-surface-container text-on-surface-variant px-2 py-1 rounded-full text-[10px]">' . e($task->category) . '</span></td>';
            $html .= '<td class="p-3 text-right whitespace-nowrap">';
            $html .= '<div class="flex items-center justify-end gap-2">';
            $html .= '<form action="' . route('admin.task.approve', $task->id) . '" method="POST" class="inline">';
            $html .= csrf_field();
            $html .= '<button type="submit" class="bg-primary text-on-primary px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">check_circle</span> ACC</button>';
            $html .= '</form>';
            $html .= '<form action="' . route('admin.task.destroy', $task->id) . '" method="POST" class="inline" onsubmit="return confirmReject(this)">';
            $html .= csrf_field();
            $html .= '<input type="hidden" name="reason" class="reject-reason-input">';
            $html .= '<button type="button" onclick="promptReject(this.form)" class="bg-error-container text-error px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-error hover:text-on-error transition-colors flex items-center gap-1 border border-error"><span class="material-symbols-outlined text-[14px]">cancel</span> Tolak</button>';
            $html .= '</form>';
            $html .= '</div></td></tr>';
        }

        if ($pendingTasks->isEmpty()) {
            $html = '<tr><td colspan="4" class="p-4 text-center text-on-surface-variant text-sm">Tidak ada task yang butuh persetujuan.</td></tr>';
        }

        return response()->json(['html' => $html]);
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

        $task->status = 'Rejected';
        $task->reject_reason = $request->reason;
        $task->save();

        return back()->with('success', 'Task berhasil ditolak karena: ' . $request->reason);
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
