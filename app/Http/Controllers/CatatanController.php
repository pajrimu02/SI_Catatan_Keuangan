<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\CatatanExport;
use App\Imports\CatatanImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CatatanController extends Controller
{
    /**
     * Dashboard ringkasan
     */
    public function dashboard(Request $request)
{
    $userId = auth()->id();

    // ===== Catatan Gaji =====
    $totalCatatan     = Catatan::where('user_id', $userId)->count();
    $totalPendapatan  = Catatan::where('user_id', $userId)->sum('pendapatan');
    $totalSudahBayar  = Catatan::where('user_id', $userId)->where('status', 'sudah_bayar')->sum('pendapatan');
    $totalBelumBayar  = Catatan::where('user_id', $userId)->where('status', 'belum_bayar')->sum('pendapatan');

    $jumlahSudahBayar = Catatan::where('user_id', $userId)->where('status', 'sudah_bayar')->count();
    $jumlahBelumBayar = Catatan::where('user_id', $userId)->where('status', 'belum_bayar')->count();

    $rataRataPendapatan = $totalCatatan > 0 ? $totalPendapatan / $totalCatatan : 0;

    // Grafik tren per minggu (minggu 1-5, berdasarkan tanggal)
    $trenMingguan = [];
    for ($minggu = 1; $minggu <= 5; $minggu++) {
        $trenMingguan[] = Catatan::where('user_id', $userId)
            ->whereRaw('CEIL(DAYOFMONTH(tanggal) / 7) = ?', [$minggu])
            ->sum('pendapatan');
    }

    $catatanTerbaru = Catatan::where('user_id', $userId)
        ->orderBy('tanggal', 'desc')
        ->take(5)
        ->get();

    // ===== Uang Masuk / Keluar (per bulan terpilih) =====
    $bulan = $request->input('bulan', now()->format('Y-m'));
    $now   = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();

    $trx = Transaksi::where('user_id', $userId)
        ->whereYear('tanggal', $now->year)
        ->whereMonth('tanggal', $now->month);

    $uangMasuk  = (clone $trx)->where('jenis', 'masuk')->sum('jumlah');
    $uangKeluar = (clone $trx)->where('jenis', 'keluar')->sum('jumlah');
    $saldoUang  = $uangMasuk - $uangKeluar;

    $kategoriKeluar = (clone $trx)->where('jenis', 'keluar')
        ->selectRaw('kategori, SUM(jumlah) as total')
        ->groupBy('kategori')
        ->orderByDesc('total')
        ->get();

    // Grafik harian (tanggal 1 s/d akhir bulan)
    $jumlahHari   = $now->daysInMonth;
    $harianMasuk  = array_fill(1, $jumlahHari, 0);
    $harianKeluar = array_fill(1, $jumlahHari, 0);

    $harian = (clone $trx)
        ->selectRaw('DAY(tanggal) as hari, jenis, SUM(jumlah) as total')
        ->groupBy('hari', 'jenis')
        ->get();

    foreach ($harian as $h) {
        if ($h->jenis === 'masuk') {
            $harianMasuk[$h->hari] = (int) $h->total;
        } else {
            $harianKeluar[$h->hari] = (int) $h->total;
        }
    }

    $harianMasuk  = array_values($harianMasuk);
    $harianKeluar = array_values($harianKeluar);

    // Transaksi terbaru ikut bulan yang dipilih
    $transaksiTerbaru = (clone $trx)
        ->orderBy('tanggal', 'desc')
        ->orderBy('id', 'desc')
        ->take(5)
        ->get();

    return view('pages.dashboard.index', compact(
        'totalCatatan',
        'totalPendapatan',
        'totalSudahBayar',
        'totalBelumBayar',
        'jumlahSudahBayar',
        'jumlahBelumBayar',
        'rataRataPendapatan',
        'trenMingguan',
        'catatanTerbaru',
        'uangMasuk',
        'uangKeluar',
        'saldoUang',
        'kategoriKeluar',
        'harianMasuk',
        'harianKeluar',
        'transaksiTerbaru',
        'bulan'
    ));
}

    /**
     * List catatan + search + filter minggu + filter status + pagination
     */
    public function index(Request $request)
    {
        $query = Catatan::where('user_id', auth()->id());

        // Search berdasarkan hari atau tanggal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('hari', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%");
            });
        }

        // Filter per minggu (minggu ke-1 = tanggal 1-7, dst)
        if ($request->filled('minggu')) {
            $minggu = (int) $request->minggu;
            $query->whereRaw('CEIL(DAYOFMONTH(tanggal) / 7) = ?', [$minggu]);
        }

        // Filter status pembayaran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Total pendapatan mengikuti filter yang aktif
        $totalPendapatan = (clone $query)->sum('pendapatan');

        // Total per status (mengikuti search & minggu, bukan filter status)
        $totalSudahBayar = (clone $query)->where('status', 'sudah_bayar')->sum('pendapatan');
        $totalBelumBayar = (clone $query)->where('status', 'belum_bayar')->sum('pendapatan');

        $catatans = $query->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.catatan.index', compact(
            'catatans',
            'totalPendapatan',
            'totalSudahBayar',
            'totalBelumBayar'
        ));
    }

    public function create()
    {
        return view('pages.catatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari'       => 'required|string|max:20',
            'tanggal'    => 'required|date',
            'pendapatan' => 'required|integer|min:0',
            'status'     => 'required|in:sudah_bayar,belum_bayar',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['nama']    = 'Pajri'; // permanen

        Catatan::create($validated);

        return redirect()
            ->route('catatan.index')
            ->with('success', 'Catatan berhasil ditambahkan.');
    }

    public function show(Catatan $catatan)
    {
        $this->authorizeOwner($catatan);
        return view('pages.catatan.show', compact('catatan'));
    }

    public function edit(Catatan $catatan)
    {
        $this->authorizeOwner($catatan);
        return view('pages.catatan.edit', compact('catatan'));
    }

    public function update(Request $request, Catatan $catatan)
    {
        $this->authorizeOwner($catatan);

        $validated = $request->validate([
            'hari'       => 'required|string|max:20',
            'tanggal'    => 'required|date',
            'pendapatan' => 'required|integer|min:0',
            'status'     => 'required|in:sudah_bayar,belum_bayar',
        ]);

        $catatan->update($validated); // nama tidak diubah

        return redirect()
            ->route('catatan.index')
            ->with('success', 'Catatan berhasil diperbarui.');
    }

    public function destroy(Catatan $catatan)
    {
        $this->authorizeOwner($catatan);
        $catatan->delete();

        return redirect()
            ->route('catatan.index')
            ->with('success', 'Catatan berhasil dihapus.');
    }

    private function authorizeOwner(Catatan $catatan): void
    {
        abort_if($catatan->user_id !== auth()->id(), 403);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new CatatanImport, $request->file('file'));

        return redirect()
            ->route('catatan.index')
            ->with('success', 'Data berhasil diimport.');
    }

    public function exportExcel()
    {
        return Excel::download(new CatatanExport, 'catatan-keuangan.xlsx');
    }

    public function exportPdf()
    {
        $catatans = Catatan::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPendapatan = $catatans->sum('pendapatan');

        $pdf = Pdf::loadView('pages.catatan.pdf', compact('catatans', 'totalPendapatan'));

        return $pdf->download('catatan-keuangan.pdf');
    }
}