<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catatan Keuangan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            min-height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(17,24,39,0.92), rgba(31,41,55,0.85));
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .hero-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .btn-outline-light:hover {
            color: #111827;
        }
    </style>
</head>
<body>

    <div class="container text-center py-5">
        <div class="hero-icon">
            <i class="fa-solid fa-wallet fs-1"></i>
        </div>

        <h1 class="fw-bold mb-3">Catatan Keuangan</h1>
        <p class="text-white-50 mb-5 fs-5">
            Kelola pendapatan harianmu dengan mudah — catat, pantau, dan lihat<br class="d-none d-md-block">
            ringkasan keuanganmu kapan saja.
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4">
                    <i class="fa-solid fa-gauge"></i> Ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4">
                    <i class="fa-solid fa-right-to-bracket"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="fa-solid fa-user-plus"></i> Daftar
                </a>
            @endauth
        </div>

        <div class="row justify-content-center mt-5 pt-4 g-4">
            <div class="col-6 col-md-3">
                <i class="fa-solid fa-book fs-2 mb-2 text-white-50"></i>
                <p class="small text-white-50 mb-0">Catat Pendapatan</p>
            </div>
            <div class="col-6 col-md-3">
                <i class="fa-solid fa-magnifying-glass fs-2 mb-2 text-white-50"></i>
                <p class="small text-white-50 mb-0">Cari & Filter</p>
            </div>
            <div class="col-6 col-md-3">
                <i class="fa-solid fa-file-excel fs-2 mb-2 text-white-50"></i>
                <p class="small text-white-50 mb-0">Import/Export Excel</p>
            </div>
            <div class="col-6 col-md-3">
                <i class="fa-solid fa-file-pdf fs-2 mb-2 text-white-50"></i>
                <p class="small text-white-50 mb-0">Export PDF</p>
            </div>
        </div>
    </div>

</body>
</html>