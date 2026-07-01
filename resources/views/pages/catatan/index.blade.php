@extends('layouts.app')

@section('title', 'Catatan')

@section('content')

    {{-- Toolbar --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-stretch align-items-lg-center mb-3 gap-2">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('catatan.create') }}" class="btn btn-dark btn-action flex-fill flex-sm-grow-0">
                <i class="fa-solid fa-plus"></i>
                <span class="d-none d-sm-inline">Tambah Catatan</span>
                <span class="d-inline d-sm-none">Tambah</span>
            </a>

            <button type="button" class="btn btn-outline-success btn-action flex-fill flex-sm-grow-0"
                    data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i>
                <span class="d-none d-md-inline">Import Excel</span>
            </button>

            <a href="{{ route('catatan.export.excel') }}" class="btn btn-outline-success btn-action flex-fill flex-sm-grow-0">
                <i class="fa-solid fa-file-excel"></i>
                <span class="d-none d-md-inline">Export Excel</span>
            </a>

            <a href="{{ route('catatan.export.pdf') }}" class="btn btn-outline-danger btn-action flex-fill flex-sm-grow-0">
                <i class="fa-solid fa-file-pdf"></i>
                <span class="d-none d-md-inline">Export PDF</span>
            </a>
        </div>

        <form method="GET" action="{{ route('catatan.index') }}" class="d-flex gap-2">
            @if(request('minggu'))
                <input type="hidden" name="minggu" value="{{ request('minggu') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari hari ke / tanggal..." style="min-width: 0;">
            <button type="submit" class="btn btn-outline-secondary btn-action flex-shrink-0">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('catatan.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-file-import text-success"></i> Import Data Excel
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">
                            File harus berformat <code>.xlsx</code> / <code>.csv</code> dengan kolom:
                            <code>nama</code>, <code>hari_ke</code>, <code>tanggal</code>, <code>pendapatan</code>
                        </p>
                        <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                        @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-upload"></i> Import Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Filter Minggu --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('catatan.index') }}"
                  class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <label class="form-label mb-0 fw-semibold text-nowrap">
                    <i class="fa-solid fa-calendar-week text-secondary"></i> Kategori per Minggu
                </label>
                <select name="minggu" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">Semua Minggu</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('minggu') == $i ? 'selected' : '' }}>
                            Minggu ke-{{ $i }}
                        </option>
                    @endfor
                </select>

                @if(request('minggu') || request('search'))
                    <a href="{{ route('catatan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-xmark"></i> Reset Filter
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Total Pendapatan --}}
    <div class="card border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #111827, #1f2937);">
        <div class="card-body d-flex justify-content-between align-items-center text-white flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:48px; height:48px;">
                    <i class="fa-solid fa-sack-dollar fs-5"></i>
                </div>
                <div>
                    <div class="small text-white-50">Total Pendapatan</div>
                    <h4 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Catatan (desktop/tablet) --}}
    <div class="card border-0 shadow-sm d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama</th>
                        <th>Hari Ke</th>
                        <th>Tanggal</th>
                        <th>Pendapatan</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($catatans as $index => $catatan)
                        <tr>
                            <td class="ps-3">{{ $catatans->firstItem() + $index }}</td>
                            <td>{{ $catatan->nama }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    Hari {{ $catatan->hari_ke }}
                                </span>
                            </td>
                            <td>{{ $catatan->tanggal->format('d-m-Y') }}</td>
                            <td class="fw-semibold text-success">
                                Rp {{ number_format($catatan->pendapatan, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('catatan.edit', $catatan) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('catatan.destroy', $catatan) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-regular fa-folder-open fs-2 d-block mb-2"></i>
                                Belum ada catatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card List (mobile only) --}}
    <div class="d-md-none">
        @forelse ($catatans as $index => $catatan)
            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="fw-semibold">{{ $catatan->nama }}</div>
                            <div class="text-muted small">{{ $catatan->tanggal->format('d-m-Y') }}</div>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">
                            Hari {{ $catatan->hari_ke }}
                        </span>
                    </div>

                    <div class="fw-bold text-success fs-5 mb-3">
                        Rp {{ number_format($catatan->pendapatan, 0, ',', '.') }}
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('catatan.edit', $catatan) }}"
                           class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                        <form action="{{ route('catatan.destroy', $catatan) }}"
                              method="POST" class="flex-fill"
                              onsubmit="return confirm('Yakin hapus catatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    <i class="fa-regular fa-folder-open fs-2 d-block mb-2"></i>
                    Belum ada catatan.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3 overflow-auto">
        {{ $catatans->links() }}
    </div>

@endsection

@push('styles')
<style>
    .btn-action {
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-action:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    }
    .btn-action:active:not(:disabled) {
        transform: translateY(0);
    }
    .table-hover tbody tr {
        transition: background 0.15s ease;
    }
    .card {
        transition: box-shadow 0.2s ease;
    }
</style>
@endpush