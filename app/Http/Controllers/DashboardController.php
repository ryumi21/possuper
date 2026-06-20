<?php
 
namespace App\Http\Controllers;
 
use App\Models\Product;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Product (sum of products and foods)
        $totalProduct = Product::count() + Food::count();
        
        // 2. Total Sales (Paid Transactions where Status = 1)
        $totalSales = DB::table('transaction_detail')
            ->join('transaction', 'transaction_detail.Transaction_Id', '=', 'transaction.Oid')
            ->leftJoin('product', 'transaction_detail.Product_Id', '=', 'product.Oid')
            ->leftJoin('food', 'transaction_detail.Product_Id', '=', 'food.Oid')
            ->where('transaction.Status', '>=', 1)
            ->sum(DB::raw('transaction_detail.Value * COALESCE(product.Price, food.Price, 0)'));
 
        // 3. Total Purchase / HPP / COGS (Cost of goods sold based on BuyPrice of items)
        $totalPurchase = DB::table('transaction_detail')
            ->join('transaction', 'transaction_detail.Transaction_Id', '=', 'transaction.Oid')
            ->leftJoin('product', 'transaction_detail.Product_Id', '=', 'product.Oid')
            ->leftJoin('food', 'transaction_detail.Product_Id', '=', 'food.Oid')
            ->where('transaction.Status', '>=', 1)
            ->sum(DB::raw('transaction_detail.Value * COALESCE(product.BuyPrice, food.BuyPrice, 0)'));
 
        // 4. Profit
        $profit = $totalSales - $totalPurchase;
 
        // 5. Best Selling Product
        $bestSelling = DB::table('transaction_detail')
            ->join('transaction', 'transaction_detail.Transaction_Id', '=', 'transaction.Oid')
            ->where('transaction.Status', '>=', 1)
            ->select('transaction_detail.Product_Id', DB::raw('SUM(transaction_detail.Value) as total_qty'))
            ->groupBy('transaction_detail.Product_Id')
            ->orderBy('total_qty', 'desc')
            ->first();
 
        $bestSellingProduct = 'Belum Ada';
        if ($bestSelling) {
            $prod = Product::find($bestSelling->Product_Id);
            if (!$prod) {
                $prod = Food::find($bestSelling->Product_Id);
            }
            if ($prod) {
                $bestSellingProduct = $prod->Name;
            }
        }
 
        // 6. Additional metrics
        $totalTransactions = DB::table('transaction')->where('Status', '>=', 1)->count();
        $totalItemsSold = DB::table('transaction_detail')
            ->join('transaction', 'transaction_detail.Transaction_Id', '=', 'transaction.Oid')
            ->where('transaction.Status', '>=', 1)
            ->sum('transaction_detail.Value');
 
        $rawMaterialStockValue = DB::table('raw_material')
            ->where('status', 'active')
            ->sum(DB::raw('current_stock * purchase_price'));
 
        $todayOrders = DB::table('transaction')
            ->whereDate('created_at', today())
            ->count();
 
        // 7. Low Stock Raw Material Alert
        $lowStockMaterial = DB::table('raw_material')
            ->leftJoin('itemunit', 'raw_material.unit', '=', 'itemunit.Oid')
            ->where('raw_material.status', 'active')
            ->whereRaw('raw_material.current_stock < raw_material.minimum_stock')
            ->select('raw_material.*', 'itemunit.Name as unit_name')
            ->first();
 
        // 8. Line Chart: Last 7 Days (Transactions vs Items Sold Qty)
        $transactionsLast7Days = [];
        $qtyLast7Days = [];
        $labelsLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->translatedFormat('D');
            
            $txCount = DB::table('transaction')
                ->where('Status', '>=', 1)
                ->whereDate('created_at', $date)
                ->count();
                
            $qtySum = DB::table('transaction_detail')
                ->join('transaction', 'transaction_detail.Transaction_Id', '=', 'transaction.Oid')
                ->where('transaction.Status', '>=', 1)
                ->whereDate('transaction.created_at', $date)
                ->sum('transaction_detail.Value');
 
            $transactionsLast7Days[] = $txCount;
            $qtyLast7Days[] = (int)$qtySum;
            $labelsLast7Days[] = $label;
        }
 
        // 9. Doughnut Chart: Top 5 Best Selling Items
        $topItems = DB::table('transaction_detail')
            ->join('transaction', 'transaction_detail.Transaction_Id', '=', 'transaction.Oid')
            ->where('transaction.Status', '>=', 1)
            ->select('transaction_detail.Product_Id', DB::raw('SUM(transaction_detail.Value) as total_qty'))
            ->groupBy('transaction_detail.Product_Id')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();
 
        $topItemNames = [];
        $topItemQtys = [];
        foreach ($topItems as $item) {
            $prod = Product::find($item->Product_Id);
            if (!$prod) {
                $prod = Food::find($item->Product_Id);
            }
            if ($prod) {
                $topItemNames[] = $prod->Name;
                $topItemQtys[] = (int)$item->total_qty;
            }
        }
        // Pad for visual consistency in Chart.js if fewer than 5 items
        while (count($topItemNames) < 5) {
            $topItemNames[] = '-';
            $topItemQtys[] = 0;
        }
 
        return view('backend.dashboard.index', compact(
            'totalProduct',
            'totalSales',
            'totalPurchase',
            'profit',
            'bestSellingProduct',
            'totalTransactions',
            'totalItemsSold',
            'rawMaterialStockValue',
            'todayOrders',
            'lowStockMaterial',
            'transactionsLast7Days',
            'qtyLast7Days',
            'labelsLast7Days',
            'topItemNames',
            'topItemQtys'
        ));
    }
}
