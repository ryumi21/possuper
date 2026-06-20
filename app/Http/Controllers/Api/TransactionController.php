<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'Table_No'     => 'nullable|string|max:100',
            'Status'       => 'required|integer',
            'items'        => 'required|array|min:1',
            'items.*.id'   => 'required|integer',
            'items.*.qty'  => 'required|integer|min:1',
            'items.*.note' => 'nullable|string|max:200',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'Table_No' => $request->input('Table_No') ?? 'TBA',
                'Status'   => $request->input('Status', 1),
            ]);

            foreach ($request->input('items') as $item) {
                $transaction->details()->create([
                    'Product_Id' => $item['id'],
                    'Note'       => $item['note'] ?? null,
                    'Value'      => $item['qty'],
                ]);

                // Reduce stock of raw materials if the item has recipe details
                $menuItem = \App\Models\Product::find($item['id']);
                if (!$menuItem) {
                    $menuItem = \App\Models\Food::find($item['id']);
                }

                if ($menuItem) {
                    foreach ($menuItem->productMaterials as $mat) {
                        $rawMat = \App\Models\RawMaterial::where('Name', $mat->Name)
                            ->where('status', 'active')
                            ->first();
                        if ($rawMat) {
                            $totalUsed = $mat->Create_Cost * $item['qty'];
                            $rawMat->current_stock -= $totalUsed;
                            $rawMat->save();
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaction saved successfully.',
                'data'    => $transaction->load('details')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to save transaction: ' . $e->getMessage()
            ], 500);
        }
    }
}
