<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create()
    {
        return view('request.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'reward_points' => 'required|integer|min:1',
            'location' => 'required|string',
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $user = Auth::user();

        if ($user->points < $validated['reward_points']) {
            return back()->withInput()->withErrors(['reward_points' => 'Poin lu nggak cukup buat ngasih reward segini! Sisa poin lu: ' . $user->points]);
        }

        // Potong poin
        $user->points -= $validated['reward_points'];
        $user->save();

        $validated['requester_id'] = $user->id;
        $validated['status'] = 'Pending Approval';
        // Simpan ke deadline juga buat sorting kalau perlu
        $validated['deadline'] = $validated['schedule_date'] . ' ' . $validated['end_time'] . ':00';
        
        Task::create($validated);

        return redirect()->route('home')->with('success', 'Request jasa berhasil diposting dan sedang menunggu persetujuan Admin!');
    }

    public function edit(Task $task)
    {
        if ($task->requester_id !== Auth::id()) {
            return back()->with('error', 'Ini bukan request lu der!');
        }

        if (!in_array($task->status, ['Open', 'Pending Approval'])) {
            return back()->with('error', 'Request ini udah jalan atau selesai, nggak bisa diedit lagi.');
        }

        if ($task->applications()->exists()) {
            return back()->with('error', 'Udah ada yang nawarin bantuan, lu nggak bisa edit lagi.');
        }

        return view('task.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->requester_id !== Auth::id() || !in_array($task->status, ['Open', 'Pending Approval']) || $task->applications()->exists()) {
            return back()->with('error', 'Lu nggak berhak edit task ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'reward_points' => 'required|integer|min:1',
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Hitung selisih poin baru dan lama
        $diff = $validated['reward_points'] - $task->reward_points;

        if ($diff > 0 && $user->points < $diff) {
            return back()->withInput()->withErrors(['reward_points' => 'Poin lu nggak cukup buat nambahin reward! Sisa poin lu: ' . $user->points]);
        }

        // Potong/tambah poin dari selisih
        if ($diff !== 0) {
            $user->points -= $diff;
            $user->save();
        }

        $validated['deadline'] = $validated['schedule_date'] . ' ' . $validated['end_time'] . ':00';
        
        $task->update($validated);

        return redirect()->route('task.show', $task->id)->with('success', 'Request jasa lu berhasil di-update!');
    }

    public function cancel(Task $task)
    {
        $user = Auth::user();

        // Pastikan task milik user ini dan statusnya masih Open
        if ($task->requester_id !== $user->id) {
            return back()->with('error', 'Ini bukan request lu der!');
        }

        if ($task->status !== 'Open') {
            return back()->with('error', 'Request ini udah nggak bisa dibatalin.');
        }

        // Refund poin
        $user->points += $task->reward_points;
        $user->save();

        // Update status task jadi Cancelled
        $task->status = 'Cancelled';
        $task->save();

        return redirect()->route('home')->with('success', 'Request berhasil dibatalkan dan poin lu udah balik 100%!');
    }

    public function apply(Task $task)
    {
        $user = Auth::user();

        // Jangan izinkan ambil task sendiri
        if ($task->requester_id === $user->id) {
            return back()->with('error', 'Nggak bisa ngelamar request lu sendiri der!');
        }

        // Pastikan masih open
        if ($task->status !== 'Open') {
            return back()->with('error', 'Yah telat, request ini udah diambil orang atau dibatalkan.');
        }

        // Cek kalau udah pernah apply
        if (\App\Models\TaskApplication::where('task_id', $task->id)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Lu udah nawarin bantuan ke sini, tunggu aja di-acc!');
        }

        \App\Models\TaskApplication::create([
            'task_id' => $task->id,
            'user_id' => $user->id
        ]);

        return back()->with('success', 'Bantuan lu udah ditawarin! Tinggal nunggu si pembuat request milih lu.');
    }

    public function show(Task $task)
    {
        // Load relasi applications dan user nya biar gampang ditampilin
        $task->load(['applications.user']);
        $user = Auth::user();
        
        return view('request.show', compact('task', 'user'));
    }

    public function accept(Task $task, \App\Models\TaskApplication $application)
    {
        $user = Auth::user();

        // Cuma pembuat task yang bisa acc
        if ($task->requester_id !== $user->id) {
            return back()->with('error', 'Hanya pembuat request yang bisa milih helper!');
        }

        // Pastikan task masih open
        if ($task->status !== 'Open') {
            return back()->with('error', 'Task udah nggak Open.');
        }

        // Assign ke user yang dipilih
        $task->helper_id = $application->user_id;
        $task->status = 'In Progress';
        $task->save();

        return redirect()->route('activity.index')->with('success', 'Kandidat berhasil dipilih! Task lu sekarang berstatus In Progress.');
    }

    public function complete(Task $task)
    {
        $user = Auth::user();

        // Hanya helper yang bisa tandai selesai
        if ($task->helper_id !== $user->id) {
            return back()->with('error', 'Hanya helper yang bisa menandai selesai!');
        }

        if ($task->status !== 'In Progress') {
            return back()->with('error', 'Status task tidak valid.');
        }

        // Validasi: Nggak boleh selesai sebelum waktunya mulai
        $startDateTime = \Carbon\Carbon::parse($task->schedule_date . ' ' . $task->start_time);
        if (now()->lt($startDateTime)) {
            return back()->with('error', 'Sabar der! Jadwal tugasnya aja belum mulai, masa udah lapor selesai?');
        }

        $task->status = 'Pending Verification';
        $task->save();

        return back()->with('success', 'Berhasil ditandai selesai! Menunggu verifikasi dari pembuat request.');
    }

    public function verify(Task $task)
    {
        $user = Auth::user();

        // Hanya requester yang bisa verifikasi
        if ($task->requester_id !== $user->id) {
            return back()->with('error', 'Hanya pembuat request yang bisa memverifikasi!');
        }

        if ($task->status !== 'Pending Verification') {
            return back()->with('error', 'Status task tidak valid.');
        }

        // Verifikasi dan transfer poin ke helper
        $task->status = 'Completed';
        $task->save();

        $helper = User::find($task->helper_id);
        if ($helper) {
            $helper->points += $task->reward_points;
            $helper->save();
        }

        return redirect()->route('review.create', $task->id)->with('success', 'Mantap! Tugas berhasil diverifikasi dan poin udah ditransfer ke helper. Jangan lupa kasih rating ya!');
    }

    public function cancelProgress(Task $task)
    {
        $user = Auth::user();

        // Hanya requester yang bisa membatalkan saat in progress/pending
        if ($task->requester_id !== $user->id) {
            return back()->with('error', 'Hanya pembuat request yang bisa membatalkan!');
        }

        if (!in_array($task->status, ['In Progress', 'Pending Verification'])) {
            return back()->with('error', 'Tugas tidak bisa dibatalkan pada status ini.');
        }

        // Batal dan kembalikan poin
        $task->status = 'Cancelled';
        $task->save();

        $user->points += $task->reward_points;
        $user->save();

        return back()->with('success', 'Tugas dibatalkan (Helper gagal). Poin lu udah dikembalikan 100%.');
    }
}