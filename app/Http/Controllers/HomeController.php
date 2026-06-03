<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Hanya tampilkan task yang statusnya Open dan deadline-nya belum lewat
        $query = Task::with('requester')
            ->where('status', 'Open')
            ->where('deadline', '>=', now());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%")
                  ->orWhere('location', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->input('category') !== 'Semua') {
            $query->where('category', $request->input('category'));
        }

        $tasks = $query->latest()->get();
        $currentCategory = $request->input('category', 'Semua');
        $currentSearch = $request->input('search', '');

        $activeTaskCount = Task::where(function($q) use ($user) {
            $q->where('requester_id', $user->id)->orWhere('helper_id', $user->id);
        })->whereIn('status', ['In Progress', 'Pending Verification'])->count();

        return view('home', compact('user', 'tasks', 'currentCategory', 'currentSearch', 'activeTaskCount'));
    }
}