<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private function checkPermission()
    {
        $user = auth()->user();
        $txMenu = \App\Models\Menu::where('Fitur', 'Log Transaksi')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$txMenu || !is_array($user->allowed_menus) || !in_array($txMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkPermission();
        $user = auth()->user();
        if ($user->IsPos == 1) {
            $transactions = Transaction::whereHas('details.product')
                ->with(['details.product', 'details.food'])
                ->orderBy('Oid', 'desc')
                ->get();
        } else {
            $transactions = Transaction::whereHas('details.food')
                ->with(['details.product', 'details.food'])
                ->orderBy('Oid', 'desc')
                ->get();
        }
        return view('backend.transactions.index', compact('transactions'));
    }

    public function destroy($id)
    {
        $this->checkPermission();
        $transaction = Transaction::findOrFail($id);
        $transaction->details()->delete();
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus dari log.');
    }
}
