<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        // Fetch transactions that are either Paid/Cooking (Status = 1) or Going to Serve (Status = 2)
        $orders = Transaction::with(['details.product', 'details.food'])
            ->whereIn('Status', [1, 2])
            ->orderBy('Oid', 'asc') // First in, first out (FIFO)
            ->get();

        if ($request->ajax()) {
            return view('backend.kitchen.orders_grid', compact('orders'));
        }

        return view('backend.kitchen.index', compact('orders'));
    }

    // Step 1: Cook completed -> status becomes 2 (Going to Serve)
    public function serve($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->Status = 2;
        $transaction->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status berubah menjadi Sedang Disajikan (Going to Serve).'
        ]);
    }

    // Step 2: Waiter returns -> status becomes 3 (Order Done / Selesai)
    public function complete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->Status = 3;
        $transaction->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan selesai disajikan (Order Done).'
        ]);
    }
}
