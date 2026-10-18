<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $stockFilter = $request->input('stock_filter');

        // Mencegah N+1 query dengan eager loading 'category' (Sesuai slide 10)
        $query = Product::with('category')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($stockFilter === 'low') {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        } elseif ($stockFilter === 'out') {
            $query->where('stock', '<=', 0);
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'stockFilter'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi server-side sesuai slide 15
        $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ], [
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'purchase_price.min' => 'Harga beli tidak boleh negatif.',
            'selling_price.min' => 'Harga jual tidak boleh negatif.',
            'stock.min' => 'Stok awal tidak boleh bernilai negatif.',
            'minimum_stock.min' => 'Batas minimum stok tidak boleh bernilai negatif.',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'purchaseItems.purchase.supplier', 'saleItems.sale.user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ], [
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'purchase_price.min' => 'Harga beli tidak boleh bernilai negatif.',
            'selling_price.min' => 'Harga jual tidak boleh bernilai negatif.',
            'minimum_stock.min' => 'Batas minimum stok tidak boleh bernilai negatif.',
        ]);

        $product->update($request->only('sku', 'name', 'category_id', 'purchase_price', 'selling_price', 'minimum_stock'));

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Business Rule slide 8: Produk yang sudah ada transaksi tidak boleh di-hard delete
        $hasTransactions = $product->purchaseItems()->exists() || $product->saleItems()->exists();

        $product->delete(); // Soft delete diterapkan di model Product

        if ($hasTransactions) {
            return redirect()->route('products.index')->with('success', 'Produk memiliki riwayat transaksi, berhasil di-arsipkan (soft delete) untuk menjaga integritas data.');
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
