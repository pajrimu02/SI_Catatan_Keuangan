<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UangController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m')); // format 2026-11
        [$tahun, $bln] = explode('-', $bulan);

        $query = Transaksi::where('user_id', auth()->id())
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bln);

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('keterangan', 'like', "%{$s}%")
                  ->orWhere('kategori', 'like', "%{$s}%");
            });
        }

        // Ringkasan (mengikuti bulan + search, tidak mengikuti filter jenis)
        $base = Transaksi::where('user_id', auth()->id())
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bln);

        $totalMasuk  = (clone $base)->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = (clone $base)->where('jenis', 'keluar')->sum('jumlah');
        $saldo       = $totalMasuk - $totalKeluar;

        // Rincian pengeluaran per kategori
        $perKategori = (clone $base)->where('jenis', 'keluar')
            ->selectRaw('kategori, SUM(jumlah) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $transaksis = $query->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pages.uang.index', compact(
            'transaksis', 'bulan', 'totalMasuk', 'totalKeluar', 'saldo', 'perKategori'
        ));
    }

    public function create()
    {
        return view('pages.uang.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $validated['user_id'] = auth()->id();

        Transaksi::create($validated);

        return redirect()->route('uang.index', ['bulan' => date('Y-m', strtotime($validated['tanggal']))])
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaksi $transaksi)
    {
        $this->authorizeOwner($transaksi);
        return view('pages.uang.edit', compact('transaksi'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $this->authorizeOwner($transaksi);
        $transaksi->update($this->validateData($request));

        return redirect()->route('uang.index', ['bulan' => $transaksi->tanggal->format('Y-m')])
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $this->authorizeOwner($transaksi);
        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'tanggal'    => 'required|date',
            'jenis'      => ['required', Rule::in(['masuk', 'keluar'])],
            'kategori'   => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
            'jumlah'     => 'required|integer|min:1',
        ]);
    }

    private function authorizeOwner(Transaksi $transaksi): void
    {
        abort_if($transaksi->user_id !== auth()->id(), 403);
    }
}