@php
    $jenisAwal = old('jenis', $transaksi->jenis ?? 'keluar');
    $kategoriAwal = old('kategori', $transaksi->kategori ?? '');
@endphp

@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
               value="{{ old('tanggal', isset($transaksi) ? $transaksi->tanggal->format('Y-m-d') : date('Y-m-d')) }}" required>
        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label d-block">Jenis</label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="jenis" id="jenisKeluar" value="keluar"
                   {{ $jenisAwal === 'keluar' ? 'checked' : '' }}>
            <label class="btn btn-outline-danger" for="jenisKeluar">Uang Keluar</label>

            <input type="radio" class="btn-check" name="jenis" id="jenisMasuk" value="masuk"
                   {{ $jenisAwal === 'masuk' ? 'checked' : '' }}>
            <label class="btn btn-outline-success" for="jenisMasuk">Uang Masuk</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <select name="kategori" id="kategori" class="form-select @error('kategori') is-invalid @enderror" required></select>
        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Jumlah (Rp)</label>
        <input type="number" name="jumlah" min="1" class="form-control @error('jumlah') is-invalid @enderror"
               value="{{ old('jumlah', $transaksi->jumlah ?? '') }}" required>
        @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Keterangan</label>
        <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
               value="{{ old('keterangan', $transaksi->keterangan ?? '') }}" placeholder="Contoh: nasi goreng, beli sabun...">
        @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    <a href="{{ route('uang.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>

@push('scripts')
<script>
    const kategoriList = @json(\App\Models\Transaksi::KATEGORI);
    const kategoriAwal = @json($kategoriAwal);
    const selectKategori = document.getElementById('kategori');

    function isiKategori(jenis, pilih = null) {
        selectKategori.innerHTML = '';
        kategoriList[jenis].forEach(k => {
            const opt = new Option(k, k, false, k === pilih);
            selectKategori.add(opt);
        });
    }

    document.querySelectorAll('input[name="jenis"]').forEach(r => {
        r.addEventListener('change', () => isiKategori(r.value));
    });

    isiKategori(document.querySelector('input[name="jenis"]:checked').value, kategoriAwal);
</script>
@endpush