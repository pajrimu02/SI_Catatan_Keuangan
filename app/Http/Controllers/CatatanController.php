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

        $totalCatatan    = Catatan::where('user_id', $userId)->count();
        $totalPendapatan = Catatan::where('user_id', $userId)->sum('pendapatan');

        $catatanTerbaru = Catatan::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('pages.dashboard.index', compact(
            'totalCatatan',
            'totalPendapatan',
            'catatanTerbaru'
        ));
    }

    /**
     * List catatan + search + filter minggu + pagination
     */
    public function index(Request $request)
    {
        $query = Catatan::where('user_id', auth()->id());

        // Search berdasarkan hari_ke atau tanggal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('hari_ke', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%");
            });
        }

        // Filter per minggu (minggu ke-1 = hari 1-7, minggu ke-2 = hari 8-14, dst)
        if ($request->filled('minggu')) {
            $minggu = (int) $request->minggu;
            $awal   = ($minggu - 1) * 7 + 1;
            $akhir  = $minggu * 7;
            $query->whereBetween('hari_ke', [$awal, $akhir]);
        }

        // Total pendapatan berdasarkan filter yang aktif
        $totalPendapatan = (clone $query)->sum('pendapatan');

        $catatans = $query->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.catatan.index', compact('catatans', 'totalPendapatan'));
    }

    public function create()
    {
        return view('pages.catatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari_ke'    => 'required|integer|min:1',
            'tanggal'    => 'required|date',
            'pendapatan' => 'required|integer|min:0',
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
            'hari_ke'    => 'required|integer|min:1',
            'tanggal'    => 'required|date',
            'pendapatan' => 'required|integer|min:0',
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

   

    // ... di dalam class CatatanController

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