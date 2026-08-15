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
                                <i class="fa-solid fa-calendar-day text-secondary"></i> Tanggal
                            </label>
                            <input type="date" name="tanggal" id="inputTanggal"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   value="{{ old('tanggal', $catatan->tanggal->format('Y-m-d')) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-calendar-week text-secondary"></i> Hari
                            </label>
                            <input type="text" name="hari" id="inputHari"
                                   class="form-control bg-light @error('hari') is-invalid @enderror"
                                   value="{{ old('hari', $catatan->hari) }}" readonly required>
                            @error('hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
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

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fa-solid fa-circle-check text-secondary"></i> Status Pembayaran
                            </label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="belum_bayar" {{ old('status', $catatan->status) == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="sudah_bayar" {{ old('status', $catatan->status) == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

@push('scripts')
<script>
    document.getElementById('inputTanggal').addEventListener('change', function () {
        if (!this.value) return;

        const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
        const [tahun, bulan, tanggal] = this.value.split('-').map(Number);
        const dateObj = new Date(tahun, bulan - 1, tanggal);

        // Hanya update field Hari, pendapatan & status TIDAK di-overwrite di halaman edit
        document.getElementById('inputHari').value = namaHari[dateObj.getDay()];
    });
</script>
@endpush