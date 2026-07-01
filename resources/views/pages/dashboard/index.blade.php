@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h3 class="mb-4">Selamat datang, {{ auth()->user()->name }} 👋</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Catatan</h6>
                    <h2 class="mb-0">{{ $totalCatatan }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h6 class="card-title">Total Pendapatan</h6>
                    <h2 class="mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Catatan Terbaru</span>
            <a href="{{ route('catatan.index') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Hari Ke</th>
                        <th>Tanggal</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($catatanTerbaru as $catatan)
                        <tr>
                            <td>{{ $catatan->nama }}</td>
                            <td>{{ $catatan->hari_ke }}</td>
                            <td>{{ $catatan->tanggal->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format($catatan->pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                Belum ada catatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection