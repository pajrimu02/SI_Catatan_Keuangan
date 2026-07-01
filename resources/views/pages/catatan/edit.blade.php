@extends('layouts.app')

@section('title', 'Edit Catatan')

@section('content')

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('catatan.index') }}" class="btn btn-light border">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="mb-0">Edit Catatan</h4>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('catatan.update', $catatan) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-user text-secondary"></i> Nama
                            </label>
                            <input type="text" class="form-control bg-light" value="{{ $catatan->nama }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-hashtag text-secondary"></i> Hari Ke
                            </label>
                            <input type="number" name="hari_ke" min="1"
                                   class="form-control @error('hari_ke') is-invalid @enderror"
                                   value="{{ old('hari_ke', $catatan->hari_ke) }}" required>
                            @error('hari_ke') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-calendar-day text-secondary"></i> Tanggal
                            </label>
                            <input type="date" name="tanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', $catatan->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-sack-dollar text-secondary"></i> Pendapatan
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="pendapatan" min="0"
                                       class="form-control @error('pendapatan') is-invalid @enderror"
                                       value="{{ old('pendapatan', $catatan->pendapatan) }}" required>
                                @error('pendapatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark flex-fill">
                                <i class="fa-solid fa-floppy-disk"></i> Update
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