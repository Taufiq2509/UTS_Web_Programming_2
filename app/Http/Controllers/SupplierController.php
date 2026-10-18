<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::withCount('purchases')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama pemasok wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Supplier::create($request->only('name', 'contact_person', 'phone', 'email', 'address'));

        return redirect()->route('suppliers.index')->with('success', 'Data pemasok baru berhasil disimpan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama pemasok wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $supplier->update($request->only('name', 'contact_person', 'phone', 'email', 'address'));

        return redirect()->route('suppliers.index')->with('success', 'Data pemasok berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->purchases()->count() > 0) {
            return back()->with('error', 'Pemasok tidak dapat dihapus karena memiliki riwayat transaksi pembelian.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Data pemasok berhasil dihapus.');
    }
}
