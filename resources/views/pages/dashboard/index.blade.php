@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $bulan = $bulan ?? now()->format('Y-m');
    $bulanCarbon = \Carbon\Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
@endphp

    {{-- Header Banner --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">
        <div class="card-body p-4 p-md-5 position-relative"
             style="background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #374151 100%);">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index:1;">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&size=64&background=ffffff&color=111827&bold=true"
                         class="rounded-circle border border-2 border-white border-opacity-25" alt="avatar">

                    <div>
                        <div class="text-white-50 small mb-1">
                            <i class="fa-regular fa-calendar"></i> {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                        <h3 class="text-white fw-bold mb-0">
                            @php
                                $jam = now()->hour;
                                $sapaan = $jam < 11 ? 'Selamat pagi' : ($jam < 15 ? 'Selamat siang' : ($jam < 18 ? 'Selamat sore' : 'Selamat malam'));
                            @endphp
                            {{ $sapaan }}, {{ explode(' ', auth()->user()->name)[0] }}
                        </h3>
                        <p class="text-white-50 mb-0 small">Berikut ringkasan keuanganmu hari ini.</p>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-shrink-0">
                    <a href="{{ route('uang.create') }}" class="btn btn-light fw-semibold px-3">
                        <i class="fa-solid fa-plus"></i> Transaksi
                    </a>
                    <a href="{{ route('catatan.create') }}" class="btn btn-outline-light fw-semibold px-3">
                        <i class="fa-solid fa-plus"></i> Catatan
                    </a>
                </div>
            </div>

            <div class="position-absolute top-0 end-0" style="width:180px; height:180px; background: rgba(255,255,255,0.05); border-radius:50%; transform: translate(40%, -40%); z-index:0;"></div>
            <div class="position-absolute bottom-0 end-0" style="width:120px; height:120px; background: rgba(255,255,255,0.04); border-radius:50%; transform: translate(20%, 40%); z-index:0;"></div>
        </div>
    </div>

    {{-- ===== Uang Masuk / Keluar ===== --}}
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <h6 class="fw-bold mb-0">
            <i class="fa-solid fa-money-bill-transfer text-secondary"></i>
            Uang Masuk / Keluar — {{ $bulanCarbon->translatedFormat('F Y') }}
        </h6>
        <div class="d-flex gap-2">
            <form method="GET" action="{{ route('dashboard') }}">
                <input type="month" name="bulan" value="{{ $bulan }}"
                       class="form-control form-control-sm" onchange="this.form.submit()">
            </form>
            <a href="{{ route('uang.index', ['bulan' => $bulan]) }}" class="btn btn-sm btn-outline-dark">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px;height:48px;">
                        <i class="fa-solid fa-arrow-down text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Uang Masuk</div>
                        <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($uangMasuk, 0, ',', '.') }}</h5>
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
                        <h5 class="mb-0 fw-bold text-danger">Rp {{ number_format($uangKeluar, 0, ',', '.') }}</h5>
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
                        <div class="small text-white-50">Sisa Saldo</div>
                        <h5 class="mb-0 fw-bold">Rp {{ number_format($saldoUang, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-column text-secondary"></i> Masuk vs Keluar per Hari</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartHarian" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-pie text-secondary"></i> Pengeluaran per Kategori</h6>
                </div>
                <div class="card-body">
                    @if($kategoriKeluar->count())
                        <canvas id="chartKategori" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-5 small">Belum ada pengeluaran bulan ini.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-3">
            <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left text-secondary"></i> Transaksi Terbaru</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-end pe-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td class="ps-3">{{ $t->tanggal->format('d-m-Y') }}</td>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $t->kategori }}</span></td>
                            <td>{{ $t->keterangan ?? '-' }}</td>
                            <td class="text-end pe-3 fw-semibold {{ $t->jenis === 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $t->jenis === 'masuk' ? '+' : '-' }} Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <hr class="my-4">
    <h6 class="fw-bold mb-3"><i class="fa-solid fa-book text-secondary"></i> Catatan Gaji</h6>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px;">
                            <i class="fa-solid fa-book text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Total Catatan</div>
                            <h4 class="mb-0 fw-bold">{{ $totalCatatan }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #111827, #1f2937);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px;">
                            <i class="fa-solid fa-sack-dollar fs-5"></i>
                        </div>
                        <div>
                            <div class="small text-white-50">Total Pendapatan</div>
                            <h5 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px;">
                            <i class="fa-solid fa-circle-check text-success fs-5"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Sudah Bayar</div>
                            <h5 class="mb-0 fw-bold text-success">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px;">
                            <i class="fa-solid fa-circle-xmark text-danger fs-5"></i>
                        </div>
                        <div>
                            <div class="small text-muted">Belum Bayar</div>
                            <h5 class="mb-0 fw-bold text-danger">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fa-solid fa-chart-line text-secondary"></i> Tren Pendapatan per Minggu
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartTren" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fa-solid fa-chart-pie text-secondary"></i> Status Pembayaran
                    </h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <canvas id="chartStatus" height="200"></canvas>
                    <div class="d-flex justify-content-center gap-4 mt-3">
                        <div class="text-center">
                            <span class="badge bg-success-subtle text-success-emphasis mb-1">●</span>
                            <div class="small text-muted">Sudah ({{ $jumlahSudahBayar }})</div>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-danger-subtle text-danger-emphasis mb-1">●</span>
                            <div class="small text-muted">Belum ({{ $jumlahBelumBayar }})</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info tambahan --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px;">
                        <i class="fa-solid fa-calculator text-info fs-5"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Rata-rata Pendapatan / Catatan</div>
                        <h5 class="mb-0 fw-bold">Rp {{ number_format($rataRataPendapatan, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width:48px; height:48px;">
                        <i class="fa-solid fa-percent text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Persentase Sudah Bayar</div>
                        <h5 class="mb-0 fw-bold">
                            {{ $totalCatatan > 0 ? round(($jumlahSudahBayar / $totalCatatan) * 100) : 0 }}%
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Catatan Terbaru --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 pt-3">
            <h6 class="fw-bold mb-0">
                <i class="fa-solid fa-clock-rotate-left text-secondary"></i> Catatan Terbaru
            </h6>
            <a href="{{ route('catatan.index') }}" class="btn btn-sm btn-outline-dark">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama</th>
                        <th>Hari</th>
                        <th>Tanggal</th>
                        <th>Pendapatan</th>
                        <th class="pe-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($catatanTerbaru as $catatan)
                        <tr>
                            <td class="ps-3">{{ $catatan->nama }}</td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    {{ $catatan->hari }}
                                </span>
                            </td>
                            <td>{{ $catatan->tanggal->format('d-m-Y') }}</td>
                            <td class="fw-semibold text-success">
                                Rp {{ number_format($catatan->pendapatan, 0, ',', '.') }}
                            </td>
                            <td class="pe-3">
                                @if($catatan->status === 'sudah_bayar')
                                    <span class="badge bg-success-subtle text-success-emphasis">Sudah Bayar</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger-emphasis">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada catatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const rupiah = v => 'Rp ' + Number(v).toLocaleString('id-ID');

    // ===== Uang Masuk vs Keluar per hari =====
    new Chart(document.getElementById('chartHarian'), {
        type: 'bar',
        data: {
            labels: {{ Js::from(range(1, $bulanCarbon->daysInMonth)) }},
            datasets: [
                { label: 'Masuk',  data: {{ Js::from($harianMasuk) }},  backgroundColor: '#198754', borderRadius: 4 },
                { label: 'Keluar', data: {{ Js::from($harianKeluar) }}, backgroundColor: '#dc3545', borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        title: items => 'Tanggal ' + items[0].label,
                        label: ctx => ctx.dataset.label + ': ' + rupiah(ctx.parsed.y),
                    }
                }
            },
            scales: { y: { ticks: { callback: v => rupiah(v) } } }
        }
    });

    // ===== Pengeluaran per kategori =====
    @if($kategoriKeluar->count())
    new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: {{ Js::from($kategoriKeluar->pluck('kategori')) }},
            datasets: [{
                data: {{ Js::from($kategoriKeluar->pluck('total')->map(fn($v) => (int) $v)) }},
                backgroundColor: ['#dc3545','#fd7e14','#ffc107','#0d6efd','#6f42c1','#20c997','#6c757d'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } },
                tooltip: { callbacks: { label: ctx => ctx.label + ': ' + rupiah(ctx.parsed) } }
            }
        }
    });
    @endif

    // ===== Tren Pendapatan Mingguan =====
    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'],
            datasets: [{
                label: 'Pendapatan',
                data: {{ Js::from($trenMingguan) }},
                borderColor: '#111827',
                backgroundColor: 'rgba(17, 24, 39, 0.08)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#111827',
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => rupiah(ctx.parsed.y) } }
            },
            scales: { y: { ticks: { callback: v => rupiah(v) } } }
        }
    });

    // ===== Status Pembayaran =====
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Sudah Bayar', 'Belum Bayar'],
            datasets: [{
                data: [{{ $jumlahSudahBayar }}, {{ $jumlahBelumBayar }}],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            cutout: '70%',
        }
    });
</script>
@endpush