@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

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

            <a href="{{ route('catatan.create') }}" class="btn btn-light fw-semibold px-4 flex-shrink-0">
                <i class="fa-solid fa-plus"></i> Tambah Catatan
            </a>
        </div>

        {{-- Dekorasi lingkaran transparan --}}
        <div class="position-absolute top-0 end-0" style="width:180px; height:180px; background: rgba(255,255,255,0.05); border-radius:50%; transform: translate(40%, -40%); z-index:0;"></div>
        <div class="position-absolute bottom-0 end-0" style="width:120px; height:120px; background: rgba(255,255,255,0.04); border-radius:50%; transform: translate(20%, 40%); z-index:0;"></div>

    </div>
</div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px; height:48px;">
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
                        <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px; height:48px;">
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
                        <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px; height:48px;">
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
                        <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px; height:48px;">
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
                    <div class="rounded-circle bg-info-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px; height:48px;">
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
                    <div class="rounded-circle bg-warning-subtle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:48px; height:48px;">
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
                        <th>Hari Ke</th>
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
                                    Hari {{ $catatan->hari_ke }}
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
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada catatan.
                            </td>
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
    // Grafik Tren Pendapatan Mingguan
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
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Grafik Status Pembayaran
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
            plugins: {
                legend: { display: false }
            },
            cutout: '70%',
        }
    });
</script>
@endpush