<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Purchase::with(['supplier', 'user', 'items.product'])->latest('purchased_at');

        if ($search) {
            $query->where('purchase_number', 'like', "%{$search}%");
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('purchased_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        $purchases = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        return view('purchases.index', compact('purchases', 'suppliers', 'search', 'supplierId', 'status', 'startDate', 'endDate'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
        ], [
            'supplier_id.required' => 'Pemasok wajib dipilih.',
            'items.required' => 'Minimal harus menambahkan satu produk dalam pembelian.',
            'items.min' => 'Minimal harus menambahkan satu produk dalam pembelian.',
            'items.*.quantity.min' => 'Kuantitas produk minimal 1.',
            'items.*.unit_cost.min' => 'Biaya per unit tidak boleh bernilai negatif.',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                $total = 0;
                $purchaseNumber = 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'user_id' => Auth::id(),
                    'purchase_number' => $purchaseNumber,
                    'total' => 0,
                    'status' => 'completed',
                    'notes' => $request->notes,
                    'purchased_at' => Carbon::parse($request->purchased_at),
                ]);

                foreach ($request->items as $itemData) {
                    $productId = $itemData['product_id'];
                    $qty = (int) $itemData['quantity'];
                    $unitCost = (float) $itemData['unit_cost'];
                    $subtotal = $unitCost * $qty;
                    $total += $subtotal;

                    // Lock produk untuk memastikan konsistensi transaksi
                    $product = Product::lockForUpdate()->findOrFail($productId);

                    // Tambah stok produk
                    $product->increment('stock', $qty);

                    // Update harga beli terakhir di produk jika diinginkan
                    $product->update(['purchase_price' => $unitCost]);

                    // Simpan detail item
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'unit_cost' => $unitCost,
                        'subtotal' => $subtotal,
                    ]);
                }

                $purchase->update(['total' => $total]);

                return $purchase;
            });

            $redirectRoute = Auth::user()->role === 'owner' ? 'owner.purchases.index' : 'staf.purchases.index';
            return redirect()->route($redirectRoute)->with('success', 'Transaksi pembelian ' . $purchase->purchase_number . ' berhasil disimpan dan stok produk telah ditambahkan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi pembelian: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Membatalkan transaksi pembelian (Khusus Owner / Otorisasi Berwenang)
     * Mengembalikan stok produk secara konsisten (Slide 8)
     */
    public function cancel(Purchase $purchase)
    {
        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah pernah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($purchase) {
                $purchase->load('items.product');

                // Validasi apakah stok mencukupi untuk dikurangi kembali
                foreach ($purchase->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    if ($product->stock < $item->quantity) {
                        throw ValidationException::withMessages([
                            'cancel' => "Pembatalan gagal! Stok produk '{$product->name}' tersisa {$product->stock}, tidak cukup untuk dikurangi {$item->quantity}.",
                        ]);
                    }
                }

                // Kurangi stok yang sebelumnya ditambah oleh pembelian ini
                foreach ($purchase->items as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item->product_id);
                    $product->decrement('stock', $item->quantity);
                }

                $purchase->update(['status' => 'cancelled']);
            });

            return back()->with('success', 'Transaksi pembelian berhasil dibatalkan dan penambahan stok telah ditarik kembali secara konsisten.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
