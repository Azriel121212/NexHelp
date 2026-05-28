<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil user yang lagi login
        $user = Auth::user();

        // Hitung statistik dari database
        $completedTasks = Task::where('helper_id', $user->id)->where('status', 'Completed')->count();
        $requestedTasks = Task::where('requester_id', $user->id)->count();
        
        // Ambil reviews
        $reviews = Review::where('helper_id', $user->id)->latest()->get();

        // Hitung Achievement (Pangkat)
        if ($completedTasks >= 10) {
            $achievementTitle = 'Pahlawan Kampus';
            $achievementColor = 'bg-[#6750A4] text-white'; // Ungu/Legend
        } elseif ($completedTasks >= 5) {
            $achievementTitle = 'Super Helper';
            $achievementColor = 'bg-[#FFB400] text-[#4A3400]'; // Oren/Emas
        } elseif ($completedTasks >= 1) {
            $achievementTitle = 'Helper Aktif';
            $achievementColor = 'bg-primary text-on-primary'; // Biru
        } else {
            $achievementTitle = 'Pemula';
            $achievementColor = 'bg-surface-container-high text-on-surface-variant'; // Abu-abu
        }

        return view('profile.index', compact('user', 'completedTasks', 'requestedTasks', 'reviews', 'achievementTitle', 'achievementColor'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'bio' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->bio = $validated['bio'] ?? null;
        $user->skills = $validated['skills'] ?? null;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil lu berhasil di-update!');
    }
}