@extends('layouts.app')

@section('title', 'Tambah Catatan')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('catatan.index') }}" class="btn btn-light border">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="mb-0">Tambah Catatan</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('catatan.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-user text-secondary"></i> Nama
                            </label>
                            <input type="text" class="form-control bg-light" value="Pajri" disabled>
                            <small class="text-muted">Nama otomatis terisi, tidak perlu diketik.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-hashtag text-secondary"></i> Hari Ke
                            </label>
                            <input type="number" name="hari_ke" min="1"
                                   class="form-control @error('hari_ke') is-invalid @enderror"
                                   value="{{ old('hari_ke') }}" placeholder="Contoh: 1" required>
                            @error('hari_ke') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-calendar-day text-secondary"></i> Tanggal
                            </label>
                            <input type="date" name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal') }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-sack-dollar text-secondary"></i> Pendapatan
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="pendapatan" min="0"
                                       class="form-control @error('pendapatan') is-invalid @enderror"
                                       value="{{ old('pendapatan') }}" placeholder="0" required>
                                @error('pendapatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-circle-check text-secondary"></i> Status Pembayaran
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="belum_bayar" {{ old('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="sudah_bayar" {{ old('status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-fill">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan
                            </button>
                            <a href="{{ route('catatan.index') }}" class="btn btn-outline-secondary flex-fill">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection