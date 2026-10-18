@extends('layouts.app')

@section('title', 'Input Transaksi Penjualan (Kasir / Sales)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Input Transaksi Penjualan (Sales)</h4>
        <p class="text-muted small mb-0">Catat transaksi penjualan kasir. Sistem akan memverifikasi ketersediaan stok aktual dan mengunci baris produk di database.</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.sales.index') : route('staf.sales.index') }}" class="btn btn-sm btn-light border">
        &larr; Kembali
    </a>
</div>

<div class="card-custom p-4">
    <form action="{{ Auth::user()->isOwner() ? route('owner.sales.store') : route('staf.sales.store') }}" method="POST" id="saleForm">
        @csrf

        <!-- Informasi Header Penjualan (Slide 7, 9 & 16) -->
        <div class="row g-3 mb-4 p-3 bg-light rounded-3">
            <div class="col-md-4">
                <label for="customer_name" class="form-label fw-semibold small">Nama Pelanggan / Konsumen</label>
                <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name', 'Pelanggan Umum') }}" placeholder="Contoh: Bpk. Joko">
            </div>

            <div class="col-md-4">
                <label for="sold_at" class="form-label fw-semibold small">Tanggal Penjualan <span class="text-danger">*</span></label>
                <input type="datetime-local" name="sold_at" id="sold_at" class="form-control @error('sold_at') is-invalid @enderror" value="{{ old('sold_at', date('Y-m-d\TH:i')) }}" required>
                @error('sold_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="notes" class="form-label fw-semibold small">Catatan Transaksi</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}" placeholder="Contoh: Pembayaran tunai lunas">
            </div>
        </div>

        <!-- Tabel Multi-Item Penjualan -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-check me-1 text-success"></i> Rincian Produk yang Dijual</h6>
            <button type="button" class="btn btn-sm btn-outline-success" id="addRowBtn">
                <i class="bi bi-plus-circle me-1"></i> Tambah Baris Produk
            </button>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 300px;">Produk (Stok Tersedia) <span class="text-danger">*</span></th>
                        <th style="width: 140px;">Kuantitas Jual <span class="text-danger">*</span></th>
                        <th style="width: 180px;">Harga Satuan (Rp)</th>
                        <th style="width: 180px;">Subtotal (Rp)</th>
                        <th style="width: 50px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select product-select" required onchange="handleProductChange(this)">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}" data-stock="{{ $prod->stock }}">
                                        {{ $prod->sku }} - {{ $prod->name }} (Sisa Stok: {{ $prod->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="stock-warning text-danger d-none fw-semibold" style="font-size: 0.75rem;">
                                Jumlah jual melebihi stok yang tersedia!
                            </small>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateRow(this)">
                        </td>
                        <td>
                            <input type="text" class="form-control bg-light price-display" value="0" readonly disabled>
                        </td>
                        <td>
                            <input type="text" class="form-control bg-light subtotal-display" value="0" readonly disabled>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="3" class="text-end fw-bold">Estimasi Total Penjualan:</td>
                        <td colspan="2">
                            <h5 class="fw-bold text-success mb-0" id="grandTotalDisplay">Rp 0</h5>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ Auth::user()->isOwner() ? route('owner.sales.index') : route('staf.sales.index') }}" class="btn btn-light border">Batal</a>
            <button type="submit" class="btn btn-success px-4" id="submitBtn">
                <i class="bi bi-cart-check-fill me-1"></i> Proses Penjualan & Kurangi Stok
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let rowIndex = 1;
    const productsData = @json($products);

    document.getElementById('addRowBtn').addEventListener('click', function() {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';

        let optionsHtml = '<option value="">-- Pilih Produk --</option>';
        productsData.forEach(p => {
            optionsHtml += `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.stock}">${p.sku} - ${p.name} (Sisa Stok: ${p.stock})</option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" required onchange="handleProductChange(this)">
                    ${optionsHtml}
                </select>
                <small class="stock-warning text-danger d-none fw-semibold" style="font-size: 0.75rem;">
                    Jumlah jual melebihi stok yang tersedia!
                </small>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateRow(this)">
            </td>
            <td>
                <input type="text" class="form-control bg-light price-display" value="0" readonly disabled>
            </td>
            <td>
                <input type="text" class="form-control bg-light subtotal-display" value="0" readonly disabled>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        rowIndex++;
        updateRemoveButtons();
    });

    function handleProductChange(select) {
        const row = select.closest('tr');
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

        row.querySelector('.price-display').value = 'Rp ' + price.toLocaleString('id-ID');
        row.dataset.currentStock = stock;
        row.dataset.price = price;

        const qtyInput = row.querySelector('.qty-input');
        qtyInput.max = stock;
        calculateRow(qtyInput);
    }

    function calculateRow(input) {
        const row = input.closest('tr');
        const price = parseFloat(row.dataset.price) || 0;
        const stock = parseInt(row.dataset.currentStock) || 0;
        const qty = parseInt(row.querySelector('.qty-input').value) || 0;

        const warning = row.querySelector('.stock-warning');
        if (qty > stock && stock > 0) {
            warning.classList.remove('d-none');
            row.querySelector('.qty-input').classList.add('is-invalid');
        } else {
            warning.classList.add('d-none');
            row.querySelector('.qty-input').classList.remove('is-invalid');
        }

        const subtotal = qty * price;
        row.querySelector('.subtotal-display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const price = parseFloat(row.dataset.price) || 0;
            const qty = parseInt(row.querySelector('.qty-input').value) || 0;
            grandTotal += (qty * price);
        });
        document.getElementById('grandTotalDisplay').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function removeRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calculateGrandTotal();
            updateRemoveButtons();
        }
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(r => {
            const btn = r.querySelector('.btn-outline-danger');
            btn.disabled = rows.length === 1;
        });
    }
</script>
@endsection
