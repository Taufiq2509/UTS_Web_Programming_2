<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data matching mockup exactly.
     */
    public function run(): void
    {
        // 1. Akun Pengguna 3 Role
        $owner = User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'owner',
            ]
        );

        $staf = User::updateOrCreate(
            ['email' => 'staf@example.com'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('password123'),
                'role' => 'staf',
            ]
        );

        $pembeli = User::updateOrCreate(
            ['email' => 'pembeli@example.com'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
            ]
        );

        // 2. Kategori Produk (5 Kategori)
        $categoriesData = [
            ['name' => 'Sembako', 'description' => 'Bahan pokok pangan harian seperti beras, gula, dan minyak.'],
            ['name' => 'Minuman', 'description' => 'Minuman kemasan, air mineral, teh, kopi, dan jus.'],
            ['name' => 'Makanan & Minuman', 'description' => 'Kopi, teh celup, susu, biskuit, dan camilan.'],
            ['name' => 'Bumbu & Bahan Dapur', 'description' => 'Garam, penyedap rasa, saus, kecap, dan bumbu instan.'],
            ['name' => 'Perlengkapan Rumah', 'description' => 'Sabun cuci, detergen, pembersih lantai, dan tisu.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // 3. Pemasok (3 Suppliers)
        $suppliersData = [
            [
                'name' => 'PT Sumber Makmur Logistik',
                'contact_person' => 'Hendra Gunawan',
                'phone' => '081234567890',
                'email' => 'sales@sumbermakmur.co.id',
                'address' => 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur',
            ],
            [
                'name' => 'CV Sejahtera Distribusi Pangan',
                'contact_person' => 'Dewi Lestari',
                'phone' => '082198765432',
                'email' => 'order@sejahterapangan.com',
                'address' => 'Jl. Soekarno-Hatta No. 45, Bandung',
            ],
            [
                'name' => 'Distributor Berkah Abadi',
                'contact_person' => 'Rian Hidayat',
                'phone' => '085712345678',
                'email' => 'info@berkahabadi.id',
                'address' => 'Jl. Rungkut Industri III No. 8, Surabaya',
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sup) {
            $suppliers[] = Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }

        // 4. Produk (8 Produk Total, 3 Produk Stok Rendah Persis Seperti Mockup)
        $productsData = [
            [
                'sku' => 'PRD-RMH-001',
                'name' => 'Detergen Bubuk Konsentrat 800gr',
                'category_id' => $categories['Perlengkapan Rumah']->id,
                'purchase_price' => 19500,
                'selling_price' => 25000,
                'stock' => 2, // Kritis (stok <= 3)
                'minimum_stock' => 6,
            ],
            [
                'sku' => 'PRD-MNM-002',
                'name' => 'Teh Botol Melati 350ml (Karton)',
                'category_id' => $categories['Minuman']->id,
                'purchase_price' => 55000,
                'selling_price' => 68000,
                'stock' => 3, // Kritis (stok <= 3)
                'minimum_stock' => 8,
            ],
            [
                'sku' => 'PRD-MAK-003',
                'name' => 'Kopi Bubuk Special 250gr',
                'category_id' => $categories['Makanan & Minuman']->id,
                'purchase_price' => 18000,
                'selling_price' => 25000,
                'stock' => 5, // Waspada (stok <= minimum_stock)
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'PRD-SMB-001',
                'name' => 'Beras Pandan Wangi 5 Kg',
                'category_id' => $categories['Sembako']->id,
                'purchase_price' => 68000,
                'selling_price' => 78000,
                'stock' => 35,
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'PRD-SMB-002',
                'name' => 'Minyak Goreng Sawit 2 Liter',
                'category_id' => $categories['Sembako']->id,
                'purchase_price' => 30000,
                'selling_price' => 36000,
                'stock' => 18,
                'minimum_stock' => 10,
            ],
            [
                'sku' => 'PRD-SMB-003',
                'name' => 'Gula Pasir Kristal 1 Kg',
                'category_id' => $categories['Sembako']->id,
                'purchase_price' => 14500,
                'selling_price' => 17500,
                'stock' => 50,
                'minimum_stock' => 15,
            ],
            [
                'sku' => 'PRD-MNM-001',
                'name' => 'Air Mineral Botol 600ml (Karton)',
                'category_id' => $categories['Minuman']->id,
                'purchase_price' => 42000,
                'selling_price' => 52000,
                'stock' => 20,
                'minimum_stock' => 5,
            ],
            [
                'sku' => 'PRD-BMB-001',
                'name' => 'Kecap Manis Refill 550ml',
                'category_id' => $categories['Bumbu & Bahan Dapur']->id,
                'purchase_price' => 18000,
                'selling_price' => 23000,
                'stock' => 18,
                'minimum_stock' => 8,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $prod) {
            $createdProducts[$prod['sku']] = Product::firstOrCreate(['sku' => $prod['sku']], $prod);
        }

        // 5. Riwayat Pembelian (Stock-in) Awal
        $purchase1 = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-20261010-001'],
            [
                'supplier_id' => $suppliers[0]->id,
                'user_id' => $staf->id,
                'total' => 2060000,
                'status' => 'completed',
                'notes' => 'Pemasukan stok awal dari PT Sumber Makmur',
                'purchased_at' => Carbon::now()->subDays(5),
            ]
        );

        if (!$purchase1->items()->exists()) {
            PurchaseItem::create([
                'purchase_id' => $purchase1->id,
                'product_id' => $createdProducts['PRD-SMB-001']->id,
                'quantity' => 20,
                'unit_cost' => 68000,
                'subtotal' => 1360000,
            ]);
            PurchaseItem::create([
                'purchase_id' => $purchase1->id,
                'product_id' => $createdProducts['PRD-SMB-003']->id,
                'quantity' => 50,
                'unit_cost' => 14000,
                'subtotal' => 700000,
            ]);
        }

        $purchase2 = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-20261018-21B52'],
            [
                'supplier_id' => $suppliers[2]->id,
                'user_id' => $staf->id,
                'total' => 220000,
                'status' => 'cancelled',
                'notes' => 'Pembelian dibatalkan oleh supplier karena stok kosong',
                'purchased_at' => Carbon::now()->subDays(1),
            ]
        );

        // 6. Riwayat Penjualan (Sales) - Total 3 Transaksi = Rp 437.000 persis mockup
        // Transaksi 1: Rp 312.000
        $sale1 = Sale::firstOrCreate(
            ['sale_number' => 'TRX-20261015-001'],
            [
                'user_id' => $staf->id,
                'customer_name' => 'Toko Kelontong Berdikari',
                'total' => 312000,
                'status' => 'completed',
                'notes' => 'Penjualan grosir sembako',
                'sold_at' => Carbon::now()->subDays(3),
            ]
        );

        if (!$sale1->items()->exists()) {
            SaleItem::create([
                'sale_id' => $sale1->id,
                'product_id' => $createdProducts['PRD-SMB-001']->id,
                'quantity' => 2,
                'price' => 78000,
                'subtotal' => 156000,
            ]);
            SaleItem::create([
                'sale_id' => $sale1->id,
                'product_id' => $createdProducts['PRD-MNM-001']->id,
                'quantity' => 3,
                'price' => 52000,
                'subtotal' => 156000,
            ]);
        }

        // Transaksi 2: Rp 125.000
        $sale2 = Sale::firstOrCreate(
            ['sale_number' => 'TRX-20261017-002'],
            [
                'user_id' => $pembeli->id,
                'customer_name' => $pembeli->name . ' (Pembeli)',
                'total' => 125000,
                'status' => 'completed',
                'notes' => 'Pesanan belanja mandiri pelanggan',
                'sold_at' => Carbon::now()->subDays(1),
            ]
        );

        if (!$sale2->items()->exists()) {
            SaleItem::create([
                'sale_id' => $sale2->id,
                'product_id' => $createdProducts['PRD-SMB-002']->id,
                'quantity' => 2,
                'price' => 36000,
                'subtotal' => 72000,
            ]);
            SaleItem::create([
                'sale_id' => $sale2->id,
                'product_id' => $createdProducts['PRD-MAK-003']->id,
                'quantity' => 2,
                'price' => 25000,
                'subtotal' => 50000,
            ]);
            $sale2->update(['total' => 125000]);
        }
    }
}
