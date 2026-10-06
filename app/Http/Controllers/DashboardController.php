<?php

namespace App\Http\Controllers;

use App\Models\PaddyPurchase;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\Sale;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        return view('dashboard', [
            'monthlySales' => Sale::query()
                ->whereBetween('sale_date', [$monthStart, $monthEnd])
                ->sum('total_amount'),
            'monthlyPurchases' => PaddyPurchase::query()
                ->whereBetween('purchase_date', [$monthStart, $monthEnd])
                ->sum('total_amount'),
            'productCount' => Product::query()->count(),
            'openBatchCount' => ProductionBatch::query()
                ->whereNull('end_date')
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'recentSales' => Sale::query()
                ->with('customer:id,name')
                ->latest('sale_date')
                ->limit(6)
                ->get(),
            'recentPurchases' => PaddyPurchase::query()
                ->with(['supplier:id,name', 'product:id,name,unit'])
                ->latest('purchase_date')
                ->latest('id')
                ->limit(5)
                ->get(),
            'stockProducts' => Product::query()
                ->orderBy('current_stock')
                ->limit(6)
                ->get(),
            'openBatches' => ProductionBatch::query()
                ->with('rawProduct:id,name,unit')
                ->whereNull('end_date')
                ->whereIn('status', ['pending', 'processing'])
                ->latest('start_date')
                ->limit(5)
                ->get(),
        ]);
    }
}
