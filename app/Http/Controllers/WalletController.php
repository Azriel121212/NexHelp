<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->get();
        return view('wallet.index', compact('user', 'transactions'));
    }

    public function topup(Request $request)
    {
        $validated = $request->validate([
            'package' => 'required|in:20,50,100,150'
        ]);

        $points = (int) $validated['package'];
        // 1 Point = 1000 IDR + 3000 IDR Flat Admin Fee
        $amount = ($points * 1000) + 3000;
        
        $user = Auth::user();

        // Simulasi sukses
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'topup',
            'points' => $points,
            'amount' => $amount,
            'status' => 'success'
        ]);

        $user->points += $points;
        $user->save();

        return back()->with('success', "Top-Up {$points} Pts berhasil! Saldo Anda telah bertambah.");
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'points' => 'required|integer|min:50|max:' . $user->points,
            'payment_method' => 'required|string',
            'account_number' => 'required|string'
        ]);

        $points = $validated['points'];
        // 1 Point = 1000 IDR, nggak ada admin fee buat narik
        $amount = $points * 1000;

        // Simulasi sukses
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdraw',
            'points' => $points,
            'amount' => $amount,
            'status' => 'success',
            'payment_method' => $validated['payment_method'],
            'account_number' => $validated['account_number']
        ]);

        $user->points -= $points;
        $user->save();

        return back()->with('success', "Tarik tunai Rp " . number_format($amount, 0, ',', '.') . " ke " . $validated['payment_method'] . " berhasil diproses!");
    }
}
