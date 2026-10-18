@extends('layouts.app')

@section('title', 'Input Transaksi Pembelian Stok (Purchase / Stock-In)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Input Transaksi Pembelian Stok</h4>
        <p class="text-muted small mb-0">Catat penerimaan barang dari pemasok. Stok produk akan bertambah otomatis di database.</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="btn btn-sm btn-light border">
        &larr; Kembali
    </a>
</div>

<div class="card-custom p-4">
    <form action="{{ Auth::user()->isOwner() ? route('owner.purchases.store') : route('staf.purchases.store') }}" method="POST" id="purchaseForm">
        @csrf

        <!-- Informasi Header Pembelian (Slide 7 & 9) -->
        <div class="row g-3 mb-4 p-3 bg-light rounded-3">
            <div class="col-md-4">
                <label for="supplier_id" class="form-label fw-semibold small">Pilih Mitra Pemasok <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Pemasok --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }} ({{ $supplier->contact_person ?? 'Tanpa PIC' }})
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="purchased_at" class="form-label fw-semibold small">Tanggal Pembelian <span class="text-danger">*</span></label>
                <input type="datetime-local" name="purchased_at" id="purchased_at" class="form-control @error('purchased_at') is-invalid @enderror" value="{{ old('purchased_at', date('Y-m-d\TH:i')) }}" required>
                @error('purchased_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="notes" class="form-label fw-semibold small">Catatan / No. Surat Jalan</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}" placeholder="Catatan penerimaan barang...">
            </div>
        </div>

        <!-- Tabel Multi-Item Pembelian (Slide 13: Add multiple items per transaction) -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-1 text-primary"></i> Rincian Produk yang Dibeli</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                <i class="bi bi-plus-circle me-1"></i> Tambah Baris Produk
            </button>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle" id="itemsTable">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 280px;">Produk <span class="text-danger">*</span></th>
                        <th style="width: 140px;">Kuantitas Masuk <span class="text-danger">*</span></th>
                        <th style="width: 180px;">Biaya Beli Satuan (Rp) <span class="text-danger">*</span></th>
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
                                    <option value="{{ $prod->id }}" data-cost="{{ $prod->purchase_price }}" data-stock="{{ $prod->stock }}">
                                        {{ $prod->sku }} - {{ $prod->name }} (Stok Saat Ini: {{ $prod->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateRow(this)">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="items[0][unit_cost]" class="form-control cost-input" min="0" value="0" required oninput="calculateRow(this)">
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
                        <td colspan="3" class="text-end fw-bold">Estimasi Total Pembelian:</td>
                        <td colspan="2">
                            <h5 class="fw-bold text-primary mb-0" id="grandTotalDisplay">Rp 0</h5>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="btn btn-light border">Batal</a>
            <button type="submit" class="btn btn-primary-custom px-4">
                <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi & Tambah Stok
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
            optionsHtml += `<option value="${p.id}" data-cost="${p.purchase_price}" data-stock="${p.stock}">${p.sku} - ${p.name} (Stok: ${p.stock})</option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" required onchange="handleProductChange(this)">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateRow(this)">
            </td>
            <td>
                <input type="number" step="0.01" name="items[${rowIndex}][unit_cost]" class="form-control cost-input" min="0" value="0" required oninput="calculateRow(this)">
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
        const cost = selectedOption.getAttribute('data-cost') || 0;
        const costInput = row.querySelector('.cost-input');
        costInput.value = cost;
        calculateRow(costInput);
    }

    function calculateRow(input) {
        const row = input.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const subtotal = qty * cost;
        row.querySelector('.subtotal-display').value = 'Rp ' + subtotal.toLocaleString('id-ID');
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            grandTotal += (qty * cost);
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
