<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Catatan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background: #111827; color: white; }
        h2 { margin-bottom: 0; }
        .total { margin-top: 15px; font-weight: bold; font-size: 14px; }
        .badge-sudah { color: #198754; font-weight: bold; }
        .badge-belum { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Laporan Catatan Keuangan</h2>
    <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Hari Ke</th>
                <th>Tanggal</th>
                <th>Pendapatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($catatans as $index => $catatan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $catatan->nama }}</td>
                    <td>{{ $catatan->hari_ke }}</td>
                    <td>{{ $catatan->tanggal->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($catatan->pendapatan, 0, ',', '.') }}</td>
                    <td class="{{ $catatan->status === 'sudah_bayar' ? 'badge-sudah' : 'badge-belum' }}">
                        {{ $catatan->status === 'sudah_bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada catatan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="total">Total Pendapatan: Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
</body>
</html>