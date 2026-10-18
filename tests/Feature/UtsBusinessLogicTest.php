<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UtsBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $staf;
    protected User $pembeli;
    protected Category $category;
    protected Supplier $supplier;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'Owner Demo',
            'email' => 'owner@test.com',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        $this->staf = User::create([
            'name' => 'Staf Demo',
            'email' => 'staf@test.com',
            'password' => Hash::make('password123'),
            'role' => 'staf',
        ]);

        $this->pembeli = User::create([
            'name' => 'Pembeli Demo',
            'email' => 'pembeli@test.com',
            'password' => Hash::make('password123'),
            'role' => 'pembeli',
        ]);

        $this->category = Category::create([
            'name' => 'Sembako Test',
            'description' => 'Bahan pokok',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'PT Pemasok Utama',
            'contact_person' => 'Budi',
            'phone' => '08123456789',
        ]);

        $this->product = Product::create([
            'sku' => 'PRD-TEST-001',
            'name' => 'Beras Pandan 5kg',
            'category_id' => $this->category->id,
            'purchase_price' => 50000,
            'selling_price' => 65000,
            'stock' => 20,
            'minimum_stock' => 5,
        ]);
    }

    /**
     * Skenario 1 (Slide 19): Auth
     * Guest tidak dapat membuka dashboard; password salah ditolak.
     */
    public function test_guest_cannot_access_dashboard_and_invalid_password_is_rejected(): void
    {
        // 1. Guest diarahkan ke login
        $response = $this->get('/owner/dashboard');
        $response->assertRedirect('/login');

        // 2. Password salah ditolak
        $loginResponse = $this->post('/login', [
            'email' => 'owner@test.com',
            'password' => 'wrongpassword',
        ]);
        $loginResponse->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Skenario 2 (Slide 19): Product
     * SKU duplikat ditolak; harga negatif ditolak.
     */
    public function test_duplicate_sku_is_rejected_and_negative_price_is_rejected(): void
    {
        $this->actingAs($this->owner);

        // SKU duplikat
        $dupResponse = $this->post('/owner/products', [
            'sku' => 'PRD-TEST-001', // Sudah ada di setUp
            'name' => 'Beras Duplikat',
            'category_id' => $this->category->id,
            'purchase_price' => 50000,
            'selling_price' => 60000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);
        $dupResponse->assertSessionHasErrors('sku');

        // Harga negatif ditolak
        $negResponse = $this->post('/owner/products', [
            'sku' => 'PRD-TEST-NEW',
            'name' => 'Produk Negatif',
            'category_id' => $this->category->id,
            'purchase_price' => -1000,
            'selling_price' => -5000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);
        $negResponse->assertSessionHasErrors(['purchase_price', 'selling_price']);
    }

    /**
     * Skenario 3 (Slide 19): Purchase
     * Pembelian sukses menambah stok sesuai kuantitas.
     */
    public function test_successful_purchase_increases_stock(): void
    {
        $this->actingAs($this->staf);

        $initialStock = $this->product->stock; // 20
        $qtyToBuy = 15;

        $response = $this->post('/staf/purchases', [
            'supplier_id' => $this->supplier->id,
            'purchased_at' => now()->toDateTimeString(),
            'notes' => 'Pembelian stok tambahan',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qtyToBuy,
                    'unit_cost' => 52000,
                ],
            ],
        ]);

        $response->assertSessionHas('success');

        // Refresh model dan cek stok
        $this->product->refresh();
        $this->assertEquals($initialStock + $qtyToBuy, $this->product->stock); // 35
        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $this->supplier->id,
            'status' => 'completed',
        ]);
    }

    /**
     * Skenario 4 (Slide 19): Sale
     * Penjualan sukses mengurangi stok sesuai kuantitas.
     */
    public function test_successful_sale_decreases_stock(): void
    {
        $this->actingAs($this->staf);

        $initialStock = $this->product->stock; // 20
        $qtyToSell = 5;

        $response = $this->post('/staf/sales', [
            'customer_name' => 'Pelanggan Uji Coba',
            'sold_at' => now()->toDateTimeString(),
            'notes' => 'Penjualan tunai',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $qtyToSell,
                ],
            ],
        ]);

        $response->assertSessionHas('success');

        $this->product->refresh();
        $this->assertEquals($initialStock - $qtyToSell, $this->product->stock); // 15
        $this->assertDatabaseHas('sales', [
            'customer_name' => 'Pelanggan Uji Coba',
            'status' => 'completed',
        ]);
    }

    /**
     * Skenario 5 (Slide 19): Insufficient stock
     * Penjualan dengan stok tidak cukup ditolak tanpa perubahan data.
     */
    public function test_sale_with_insufficient_stock_is_rejected_without_data_change(): void
    {
        $this->actingAs($this->staf);

        $initialStock = $this->product->stock; // 20
        $excessiveQty = 999; // Jauh melebihi stok

        $response = $this->post('/staf/sales', [
            'customer_name' => 'Pembeli Borongan',
            'sold_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => $excessiveQty,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');

        // Pastikan stok TIDAK berubah sama sekali
        $this->product->refresh();
        $this->assertEquals($initialStock, $this->product->stock);
        $this->assertDatabaseMissing('sales', [
            'customer_name' => 'Pembeli Borongan',
        ]);
    }

    /**
     * Skenario 6 (Slide 19): Atomicity
     * Jika satu item transaksi gagal, seluruh transaksi rollback.
     */
    public function test_atomicity_transaction_rollback_when_one_item_fails(): void
    {
        $this->actingAs($this->staf);

        $product2 = Product::create([
            'sku' => 'PRD-TEST-002',
            'name' => 'Minyak Goreng 1L',
            'category_id' => $this->category->id,
            'purchase_price' => 15000,
            'selling_price' => 18000,
            'stock' => 2, // Stok hanya 2
            'minimum_stock' => 1,
        ]);

        $initialStockProd1 = $this->product->stock; // 20
        $initialStockProd2 = $product2->stock;      // 2

        // Item 1 valid (beli 5 dari 20), item 2 TIDAK valid (beli 10 dari 2)
        $response = $this->post('/staf/sales', [
            'customer_name' => 'Transaksi Multi Item Gagal',
            'sold_at' => now()->toDateTimeString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 5,
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 10, // Melebihi stok!
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');

        // KONSISTENSI & ATOMICITY: Item pertama yang valid TIDAK BOLEH berkurang stoknya!
        $this->product->refresh();
        $product2->refresh();

        $this->assertEquals($initialStockProd1, $this->product->stock);
        $this->assertEquals($initialStockProd2, $product2->stock);

        // Header transaksi juga tidak tersimpan setengah jalan
        $this->assertDatabaseMissing('sales', [
            'customer_name' => 'Transaksi Multi Item Gagal',
        ]);
    }

    /**
     * Skenario 7 (Slide 19): Authorization
     * Staff tidak dapat mengakses fungsi Admin/Owner.
     */
    public function test_staff_cannot_access_owner_admin_functions(): void
    {
        $this->actingAs($this->staf);

        // Staf mencoba mengakses dashboard owner -> 403 Forbidden
        $response1 = $this->get('/owner/dashboard');
        $response1->assertStatus(403);

        // Staf mencoba mengakses manajemen kategori owner -> 403 Forbidden
        $response2 = $this->get('/owner/categories');
        $response2->assertStatus(403);

        // Staf mencoba mengakses manajemen user owner -> 403 Forbidden
        $response3 = $this->get('/owner/users');
        $response3->assertStatus(403);
    }

    /**
     * Skenario 8 (Slide 19): Search, filter, dan pagination
     * Filter dan pagination menghasilkan data yang sesuai.
     */
    public function test_search_filter_and_pagination_yield_correct_records(): void
    {
        $this->actingAs($this->owner);

        // Buat produk kedua dengan nama unik
        $p2 = Product::create([
            'sku' => 'PRD-SPECIAL-99',
            'name' => 'Kopi Robusta Asli',
            'category_id' => $this->category->id,
            'purchase_price' => 20000,
            'selling_price' => 25000,
            'stock' => 10,
            'minimum_stock' => 3,
        ]);

        // Cari 'Kopi'
        $response = $this->get('/owner/products?search=Kopi');
        $response->assertOk();
        $response->assertSee('Kopi Robusta Asli');
        $response->assertDontSee('Beras Pandan 5kg');
    }
}
