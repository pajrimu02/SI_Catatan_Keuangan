<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
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
    public function dashboard()
    {
        $userId = auth()->id();

        $totalCatatan     = Catatan::where('user_id', $userId)->count();
        $totalPendapatan  = Catatan::where('user_id', $userId)->sum('pendapatan');
        $totalSudahBayar  = Catatan::where('user_id', $userId)->where('status', 'sudah_bayar')->sum('pendapatan');
        $totalBelumBayar  = Catatan::where('user_id', $userId)->where('status', 'belum_bayar')->sum('pendapatan');

        $jumlahSudahBayar = Catatan::where('user_id', $userId)->where('status', 'sudah_bayar')->count();
        $jumlahBelumBayar = Catatan::where('user_id', $userId)->where('status', 'belum_bayar')->count();

        $rataRataPendapatan = $totalCatatan > 0 ? $totalPendapatan / $totalCatatan : 0;

        // Data untuk grafik tren per minggu (minggu 1-5, berdasarkan tanggal)
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

        return view('pages.dashboard.index', compact(
            'totalCatatan',
            'totalPendapatan',
            'totalSudahBayar',
            'totalBelumBayar',
            'jumlahSudahBayar',
            'jumlahBelumBayar',
            'rataRataPendapatan',
            'trenMingguan',
            'catatanTerbaru'
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

        // Filter per minggu (berdasarkan tanggal, minggu ke-1 = tanggal 1-7, dst)
        if ($request->filled('minggu')) {
            $minggu = (int) $request->minggu;
            $query->whereRaw('CEIL(DAYOFMONTH(tanggal) / 7) = ?', [$minggu]);
        }

        // Filter status pembayaran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Total pendapatan berdasarkan filter yang aktif (semua status)
        $totalPendapatan = (clone $query)->sum('pendapatan');

        // Total per status (tetap mengikuti filter search & minggu, tapi bukan filter status)
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