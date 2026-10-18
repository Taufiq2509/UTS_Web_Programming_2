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

class CustomerOrderController extends Controller
{
    /**
     * Memproses pesanan langsung dari dashboard Pembeli
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'quantity.min' => 'Jumlah pembelian minimal 1 unit.',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $qty = (int) $request->quantity;

                // Lock row di database
                $product = Product::lockForUpdate()->findOrFail($request->product_id);

                if ($product->stock < $qty) {
                    throw ValidationException::withMessages([
                        'quantity' => "Stok produk '{$product->name}' tidak mencukupi (sisa: {$product->stock}).",
                    ]);
                }

                $price = (float) $product->selling_price;
                $subtotal = $price * $qty;
                $saleNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'sale_number' => $saleNumber,
                    'customer_name' => Auth::user()->name,
                    'total' => $subtotal,
                    'status' => 'completed',
                    'notes' => $request->notes ?: 'Pesanan langsung pembeli',
                    'sold_at' => Carbon::now(),
                ]);

                // Kurangi stok
                $product->decrement('stock', $qty);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                return $sale;
            });

            return redirect()->route('customer.dashboard')->with('success', "Pesanan #{$sale->sale_number} berhasil dibuat! Total belanja: Rp " . number_format($sale->total, 0, ',', '.'));

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan pemesanan: ' . $e->getMessage());
        }
    }
}
