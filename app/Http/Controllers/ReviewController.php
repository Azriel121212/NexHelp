<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Task $task)
    {
        $user = Auth::user();

        // Hanya requester yang bisa ngasih review
        if ($task->requester_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        // Cek kalau udah pernah review
        $existingReview = Review::where('task_id', $task->id)->first();
        if ($existingReview) {
            return redirect()->route('activity.index')->with('success', 'Anda sudah memberikan rating untuk tugas ini!');
        }

        return view('review.create', compact('task'));
    }

    public function store(Request $request, Task $task)
    {
        $user = Auth::user();

        if ($task->requester_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'task_id' => $task->id,
            'reviewer_id' => $user->id,
            'helper_id' => $task->helper_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('activity.index')->with('success', 'Mantap! Ulasan Anda telah tersimpan. Terima kasih telah menggunakan KawanKampus!');
    }
}
