@extends('layouts.app')

@section('title', 'Uang Masuk / Keluar')

@section('content')

    {{-- Toolbar --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-2 mb-3">
        <a href="{{ route('uang.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Transaksi
        </a>

        <form method="GET" action="{{ route('uang.index') }}" class="d-flex flex-wrap gap-2">
            <input type="month" name="bulan" value="{{ $bulan }}" class="form-control w-auto"
                   onchange="this.form.submit()">
            <select name="jenis" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="masuk"  {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Uang Masuk</option>
                <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Uang Keluar</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto"
                   placeholder="Cari keterangan / kategori...">
            <button class="btn btn-outline-secondary"><i class="fa-solid fa-magnifying-glass"></i></button>
            @if(request('jenis') || request('search'))
                <a href="{{ route('uang.index', ['bulan' => $bulan]) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="fa-solid fa-arrow-down text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Uang Masuk</div>
                        <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="fa-solid fa-arrow-up text-danger fs-5"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Uang Keluar</div>
                        <h5 class="mb-0 fw-bold text-danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #111827, #1f2937);">
                <div class="card-body d-flex align-items-center gap-3 text-white">
                    <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="fa-solid fa-scale-balanced fs-5"></i>
                    </div>
                    <div>
                        <div class="small text-white-50">Sisa Saldo Bulan Ini</div>
                        <h5 class="mb-0 fw-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengeluaran per kategori --}}
    @if($perKategori->count())
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="fw-semibold mb-2"><i class="fa-solid fa-chart-pie text-secondary"></i> Pengeluaran per Kategori</div>
                @foreach($perKategori as $k)
                    @php $persen = $totalKeluar > 0 ? round($k->total / $totalKeluar * 100) : 0; @endphp
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small">
                            <span>{{ $k->kategori }}</span>
                            <span>Rp {{ number_format($k->total, 0, ',', '.') }} ({{ $persen }}%)</span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-danger" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Tabel (desktop) --}}
    <div class="card border-0 shadow-sm d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $t)
                        <tr>
                            <td class="ps-3">{{ $t->tanggal->format('d-m-Y') }}</td>
                            <td>
                                @if($t->jenis === 'masuk')
                                    <span class="badge bg-success-subtle text-success-emphasis">Masuk</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger-emphasis">Keluar</span>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $t->kategori }}</span></td>
                            <td>{{ $t->keterangan ?? '-' }}</td>
                            <td class="text-end fw-semibold {{ $t->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('uang.edit', $t) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('uang.destroy', $t) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-regular fa-folder-open fs-2 d-block mb-2"></i>
                                Belum ada transaksi bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Card list (mobile) --}}
    <div class="d-md-none">
        @forelse($transaksis as $t)
            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <div class="fw-semibold">{{ $t->keterangan ?? $t->kategori }}</div>
                        <div class="fw-bold {{ $t->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                            {{ $t->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">{{ $t->tanggal->format('d-m-Y') }}</span>
                        <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $t->kategori }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('uang.edit', $t) }}" class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form action="{{ route('uang.destroy', $t) }}" method="POST" class="flex-fill"
                              onsubmit="return confirm('Yakin hapus transaksi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger w-100"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center text-muted py-5">Belum ada transaksi bulan ini.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-3 overflow-auto">
        {{ $transaksis->links('vendor.pagination.custom') }}
    </div>

@endsection