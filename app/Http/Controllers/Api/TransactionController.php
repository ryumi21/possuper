<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    private function checkPermission()
    {
        $user = auth()->user();
        $posMenu = \App\Models\Menu::where('Fitur', 'Mesin Kasir (POS)')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$posMenu || !is_array($user->allowed_menus) || !in_array($posMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request)
    {
        $this->checkPermission();
        $request->validate([
            'Table_No'     => 'required|string|max:100',
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
