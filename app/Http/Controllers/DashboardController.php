<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Real logic from existing Product model
        $totalProduct = Product::count();
        
        // Placeholder data pending database tables
        $totalSales = 0;
        $totalPurchase = 0;
        $bestSellingProduct = 'Belum Ada';

        return view('backend.dashboard.index', compact(
            'totalProduct',
            'totalSales',
            'totalPurchase',
            'bestSellingProduct'
        ));
    }
}
