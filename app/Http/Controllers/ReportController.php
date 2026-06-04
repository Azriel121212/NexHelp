<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        Report::create([
            'reporter_id' => Auth::id(),
            'reported_id' => $user->id,
            'reason' => $request->reason,
            // task_id is optional, we could pass it if needed, but for MVP, reporting user from chat is enough.
        ]);

        return back()->with('success', 'Laporan berhasil dikirim. Admin akan segera meninjau akun ini.');
    }
}
