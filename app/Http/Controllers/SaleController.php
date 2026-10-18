<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Sale::with(['user', 'items.product'])->latest('sold_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('sold_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $sales = $query->paginate(10)->withQueryString();

        return view('sales.index', compact('sales', 'search', 'status', 'startDate', 'endDate'));
    }

    public function create()
    {
        // Hanya tampilkan produk yang stoknya > 0
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        // 1. Validasi struktur form request server-side
        $request->validate([
            'customer_name' => ['nullable', 'string', 'max:100'],
            'sold_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'items.required' => 'Minimal harus memilih 1 produk untuk dijual.',
            'items.min' => 'Minimal harus memilih 1 produk untuk dijual.',
            'items.*.quantity.min' => 'Jumlah beli setiap item minimal 1 unit.',
        ]);

        try {
            // 2. Database Transaction atomik (Slide 8 & 16)
            $sale = DB::transaction(function () use ($request) {
                $total = 0;
                $saleNumber = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'sale_number' => $saleNumber,
                    'customer_name' => $request->customer_name ?: (Auth::user()->isPembeli() ? Auth::user()->name : 'Pelanggan Umum'),
                    'total' => 0,
                    'status' => 'completed',
                    'notes' => $request->notes,
                    'sold_at' => Carbon::parse($request->sold_at),
                ]);

                foreach ($request->items as $itemData) {
                    $productId = $itemData['product_id'];
                    $qty = (int) $itemData['quantity'];

                    // Lock baris produk di database untuk mencegah overselling pada transaksi bersamaan (race condition)
                    $product = Product::lockForUpdate()->findOrFail($productId);

                    // Cek ketersediaan stok aktual
                    if ($product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Transaksi Ditolak: Stok produk '{$product->name}' tidak mencukupi! Tersedia: {$product->stock}, Diminta: {$qty}.",
                        ]);
                    }

                    // Ambil harga langsung dari database server, BUKAN dari input form browser
                    $price = (float) $product->selling_price;
                    $subtotal = $price * $qty;
                    $total += $subtotal;

                    // Kurangi stok produk secara langsung
                    $product->decrement('stock', $qty);

                    // Simpan detail item penjualan
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update total penjualan setelah seluruh item divalidasi
                $sale->update(['total' => $total]);

                return $sale;
            });

            $redirectRoute = Auth::user()->role === 'owner' ? 'owner.sales.index' : (Auth::user()->role === 'staf' ? 'staf.sales.index' : 'customer.dashboard');

            return redirect()->route($redirectRoute)->with('success', 'Transaksi penjualan ' . $sale->sale_number . ' berhasil diproses dan stok berhasil diperbarui!');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Membatalkan transaksi penjualan (Otorisasi: Owner)
     * Mengembalikan stok produk yang telah terjual secara konsisten (Slide 8)
     */
    public function cancel(Sale $sale)
    {
        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah pernah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($sale) {
                $sale->load('items');

                // Kembalikan stok untuk setiap item penjualan
                foreach ($sale->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    $product->increment('stock', $item->quantity);
                }

                $sale->update(['status' => 'cancelled']);
            });

            return back()->with('success', 'Transaksi penjualan ' . $sale->sale_number . ' berhasil dibatalkan dan stok produk telah dipulihkan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
