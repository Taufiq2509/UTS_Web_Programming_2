<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk Owner
     */
    public function owner()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // Query Agregat Ringkasan Real-Time (Sesuai slide 18)
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        $totalSalesCount = Sale::where('status', 'completed')->count();
        $totalPurchasesCount = Purchase::where('status', 'completed')->count();
        $totalTransactions = $totalSalesCount + $totalPurchasesCount;

        // Penjualan hari ini & bulan ini
        $salesToday = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->sum('total');

        $salesThisMonth = Sale::where('status', 'completed')
            ->whereMonth('sold_at', $thisMonth)
            ->whereYear('sold_at', $thisYear)
            ->sum('total');

        $purchasesThisMonth = Purchase::where('status', 'completed')
            ->whereMonth('purchased_at', $thisMonth)
            ->whereYear('purchased_at', $thisYear)
            ->sum('total');

        // Daftar & Jumlah Produk Stok Rendah (stock <= minimum_stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        $lowStockCount = $lowStockProducts->count();

        // 5 Transaksi Penjualan Terakhir
        $recentSales = Sale::with(['user', 'items.product'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        // 5 Transaksi Pembelian Terakhir
        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->latest('purchased_at')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalTransactions',
            'salesToday',
            'salesThisMonth',
            'purchasesThisMonth',
            'lowStockProducts',
            'lowStockCount',
            'recentSales',
            'recentPurchases'
        ));
    }

    /**
     * Dashboard untuk Staf Operasional
     */
    public function staf()
    {
        $today = Carbon::today();

        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        $todaySalesCount = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->count();

        $todaySalesTotal = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->sum('total');

        $todayPurchasesCount = Purchase::where('status', 'completed')
            ->whereDate('purchased_at', $today)
            ->count();

        $recentSales = Sale::with(['items.product', 'user'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->latest('purchased_at')
            ->take(5)
            ->get();

        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return view('staf.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockCount',
            'todaySalesCount',
            'todaySalesTotal',
            'todayPurchasesCount',
            'recentSales',
            'recentPurchases',
            'lowStockProducts'
        ));
    }

    /**
     * Dashboard untuk Pembeli / Customer
     */
    public function customer(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = Product::with('category')->where('stock', '>', 0);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(8)->withQueryString();
        $categories = Category::all();

        // Riwayat belanja pribadi customer yang sedang login
        $myOrders = Sale::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest('sold_at')
            ->paginate(5, ['*'], 'orders_page');

        $myTotalSpent = Sale::where('user_id', Auth::id())->where('status', 'completed')->sum('total');
        $myOrdersCount = Sale::where('user_id', Auth::id())->where('status', 'completed')->count();
        $totalAvailableProducts = Product::where('stock', '>', 0)->count();

        return view('customer.dashboard', compact(
            'products',
            'categories',
            'myOrders',
            'myTotalSpent',
            'myOrdersCount',
            'totalAvailableProducts'
        ));
    }
}
